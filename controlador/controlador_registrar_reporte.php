<?php
// controlador_registrar_reporte.php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    header('Location: ../login/login.php');
    exit;
}

require_once '../modelo/conexion.php';

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre_planta = isset($_POST['nombre_planta']) ? trim($_POST['nombre_planta']) : '';
    $ubicacion = isset($_POST['ubicacion']) ? trim($_POST['ubicacion']) : '';
    $codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
    $fecha_mantenimiento = isset($_POST['fecha_mantenimiento']) ? trim($_POST['fecha_mantenimiento']) : '';
    $tipo_mantenimiento = isset($_POST['tipo_mantenimiento']) ? trim($_POST['tipo_mantenimiento']) : '';
    $responsable = isset($_POST['responsable']) ? trim($_POST['responsable']) : '';
    $descripcion_actividad = isset($_POST['descripcion_actividad']) ? trim($_POST['descripcion_actividad']) : '';
    $materiales = isset($_POST['materiales']) ? trim($_POST['materiales']) : '';
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';
    $estado_salud = isset($_POST['estado_salud']) ? trim($_POST['estado_salud']) : '';
    $fecha_proximo = isset($_POST['fecha_proximo']) ? trim($_POST['fecha_proximo']) : null;
    $firma = isset($_POST['firma']) ? trim($_POST['firma']) : '';
    
    // Validar campos obligatorios
    if (empty($nombre_planta) || empty($ubicacion) || empty($codigo) || empty($fecha_mantenimiento) || 
        empty($responsable) || empty($descripcion_actividad)) {
        $_SESSION['mensaje_error'] = "Por favor, complete todos los campos obligatorios.";
        header('Location: ../vista/registrar_mantenimiento.php');
        exit;
    }
    
    // Insertar en la base de datos
    $sql = "INSERT INTO mantenimientos (
                nombre_planta, ubicacion, codigo, fecha_mantenimiento,
                tipo_mantenimiento, responsable, descripcion_actividad,
                materiales, observaciones, estado_salud, fecha_proximo, firma
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conexion->prepare($sql);
    
    $stmt->bind_param("ssssssssssss", 
        $nombre_planta, $ubicacion, $codigo, $fecha_mantenimiento,
        $tipo_mantenimiento, $responsable, $descripcion_actividad,
        $materiales, $observaciones, $estado_salud, $fecha_proximo, $firma
    );
    
    if ($stmt->execute()) {
        $id_registro = $conexion->insert_id;
        $_SESSION['mensaje_exito'] = "Mantenimiento registrado correctamente.";
        
        // Redireccionar a la página de detalles con opciones de exportación
        header("Location: ../vista/ver_mantenimiento.php?id=$id_registro");
        exit;
    } else {
        $_SESSION['mensaje_error'] = "Error al registrar el mantenimiento: " . $conexion->error;
        header('Location: ../vista/registrar_mantenimiento.php');
        exit;
    }
} elseif (isset($_GET['accion'])) {
    // Procesar acciones de exportación
    $accion = $_GET['accion'];
    
    switch ($accion) {
        case 'exportar_pdf':
            exportarTodosPDF($conexion);
            break;
            
        case 'exportar_pdf_individual':
            if (isset($_GET['id'])) {
                exportarPDFIndividual($conexion, $_GET['id'], $_GET['method'] ?? 'server');
            } else {
                $_SESSION['mensaje_error'] = "ID no especificado.";
                header('Location: ../vista/listar_reportes.php');
            }
            break;
            
        case 'exportar_excel':
            if (isset($_GET['id'])) {
                exportarExcelIndividual($conexion, $_GET['id']);
            } else {
                exportarTodosExcel($conexion);
            }
            break;
            
        case 'enviar_email':
            if (isset($_GET['id']) && isset($_GET['tipo'])) {
                enviarPorEmail($conexion, $_GET['id'], $_GET['tipo']);
            } else {
                $_SESSION['mensaje_error'] = "Información insuficiente para enviar email.";
                header('Location: ../vista/listar_reportes.php');
            }
            break;
            
        default:
            $_SESSION['mensaje_error'] = "Acción no reconocida.";
            header('Location: ../vista/listar_reportes.php');
            break;
    }
}

// Función para exportar todos los mantenimientos a PDF
function exportarTodosPDF($conexion) {
    require_once '../vendor/autoload.php'; // Para TCPDF
    
    // Consultar datos
    $sql = "SELECT * FROM mantenimientos ORDER BY fecha_mantenimiento DESC";
    $resultado = $conexion->query($sql);
    
    // Crear nueva instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    
    // Establecer información del documento
    $pdf->SetCreator('Parque Industrial Santiváñez');
    $pdf->SetAuthor('Sistema de Mantenimiento');
    $pdf->SetTitle('Listado de Mantenimientos');
    $pdf->SetSubject('Reporte de Mantenimientos');
    
    // Eliminar encabezado y pie de página predeterminados
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Establecer márgenes
    $pdf->SetMargins(15, 15, 15);
    
    // Establecer auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 15);
    
    // Agregar página
    $pdf->AddPage();
    
    // Título
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'REPORTE DE MANTENIMIENTOS - PARQUE INDUSTRIAL SANTIVÁÑEZ', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
    $pdf->Ln(10);
    
    // Cabeceras de tabla
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(10, 7, 'ID', 1, 0, 'C');
    $pdf->Cell(50, 7, 'Planta', 1, 0, 'C');
    $pdf->Cell(30, 7, 'Ubicación', 1, 0, 'C');
    $pdf->Cell(30, 7, 'Tipo', 1, 0, 'C');
    $pdf->Cell(30, 7, 'Fecha', 1, 0, 'C');
    $pdf->Cell(25, 7, 'Estado', 1, 1, 'C');
    
    // Datos de la tabla
    $pdf->SetFont('helvetica', '', 9);
    
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $pdf->Cell(10, 6, $fila['id'], 1, 0, 'C');
            $pdf->Cell(50, 6, $fila['nombre_planta'], 1, 0, 'L');
            $pdf->Cell(30, 6, $fila['ubicacion'], 1, 0, 'L');
            $pdf->Cell(30, 6, $fila['tipo_mantenimiento'], 1, 0, 'L');
            $pdf->Cell(30, 6, date('d/m/Y', strtotime($fila['fecha_mantenimiento'])), 1, 0, 'C');
            $pdf->Cell(25, 6, $fila['estado_salud'], 1, 1, 'C');
        }
    } else {
        $pdf->Cell(175, 6, 'No hay registros disponibles', 1, 1, 'C');
    }
    
    // Generar el PDF
    $pdf->Output('Mantenimientos_' . date('Ymd_His') . '.pdf', 'D');
    exit;
}

// Función para exportar un mantenimiento individual a PDF
function exportarPDFIndividual($conexion, $id, $method = 'server') {
    $id = intval($id);
    
    // Consultar datos
    $sql = "SELECT * FROM mantenimientos WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 0) {
        $_SESSION['mensaje_error'] = "Registro no encontrado.";
        header('Location: ../vista/listar_reportes.php');
        exit;
    }
    
    $mantenimiento = $resultado->fetch_assoc();
    
    // Crear nueva instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    
    // Establecer información del documento
    $pdf->SetCreator('Parque Industrial Santiváñez');
    $pdf->SetAuthor('Sistema de Mantenimiento');
    $pdf->SetTitle('Informe de Mantenimiento - ' . $mantenimiento['nombre_planta']);
    $pdf->SetSubject('Mantenimiento');
    
    // Eliminar encabezado y pie de página predeterminados
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Establecer márgenes
    $pdf->SetMargins(15, 15, 15);
    
    // Establecer auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 15);
    
    // Agregar página
    $pdf->AddPage();
    
    // Título
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'INFORME DE MANTENIMIENTO', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'PARQUE INDUSTRIAL SANTIVÁÑEZ', 0, 1, 'C');
    $pdf->Ln(5);
    
    // Tabla de datos
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Detalles del Mantenimiento', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
    
    $pdf->Cell(40, 7, 'Nombre de la planta:', 0, 0, 'L');
    $pdf->Cell(0, 7, $mantenimiento['nombre_planta'], 0, 1, 'L');
    
    $pdf->Cell(40, 7, 'Código:', 0, 0, 'L');
    $pdf->Cell(0, 7, $mantenimiento['codigo'], 0, 1, 'L');
    
    $pdf->Cell(40, 7, 'Ubicación:', 0, 0, 'L');
    $pdf->Cell(0, 7, $mantenimiento['ubicacion'], 0, 1, 'L');
    
    $pdf->Cell(40, 7, 'Fecha:', 0, 0, 'L');
    $pdf->Cell(0, 7, date('d/m/Y', strtotime($mantenimiento['fecha_mantenimiento'])), 0, 1, 'L');
    
    $pdf->Cell(40, 7, 'Tipo de mantenimiento:', 0, 0, 'L');
    $pdf->Cell(0, 7, $mantenimiento['tipo_mantenimiento'], 0, 1, 'L');
    
    $pdf->Cell(40, 7, 'Responsable:', 0, 0, 'L');
    $pdf->Cell(0, 7, $mantenimiento['responsable'], 0, 1, 'L');
    
    $pdf->Cell(40, 7, 'Descripción:', 0, 0, 'L');
    $pdf->MultiCell(0, 7, $mantenimiento['descripcion_actividad'], 0, 'L');
    
    $pdf->Cell(40, 7, 'Materiales:', 0, 0, 'L');
    $pdf->MultiCell(0, 7, $mantenimiento['materiales'] ?: 'No especificados', 0, 'L');
    
    $pdf->Cell(40, 7, 'Observaciones:', 0, 0, 'L');
    $pdf->MultiCell(0, 7, $mantenimiento['observaciones'] ?: 'No hay observaciones', 0, 'L');
    
    $pdf->Cell(40, 7, 'Próximo mantenimiento:', 0, 0, 'L');
    $pdf->Cell(0, 7, $mantenimiento['fecha_proximo'] ? date('d/m/Y', strtotime($mantenimiento['fecha_proximo'])) : 'No programado', 0, 1, 'L');
    
    // Generar PDF
    $pdf->Output('Mantenimiento_' . $mantenimiento['codigo'] . '_' . date('Ymd') . '.pdf', 'D');
    exit;
}

// Función para exportar todos los mantenimientos a Excel
function exportarTodosExcel($conexion) {
    require_once '../vendor/autoload.php';
    
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    
    // Consultar datos
    $sql = "SELECT * FROM mantenimientos ORDER BY fecha_mantenimiento DESC";
    $resultado = $conexion->query($sql);
    
    // Crear instancia de Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Mantenimientos');
    
    // Establecer encabezado
    $sheet->setCellValue('A1', 'PARQUE INDUSTRIAL SANTIVÁÑEZ');
    $sheet->mergeCells('A1:G1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    
    $sheet->setCellValue('A2', 'LISTADO DE MANTENIMIENTOS');
    $sheet->mergeCells('A2:G2');
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
    
    $sheet->setCellValue('A3', 'Fecha de generación: ' . date('d/m/Y H:i:s'));
    $sheet->mergeCells('A3:G3');
   
   // Establecer encabezados de columnas
   $sheet->setCellValue('A5', 'ID');
   $sheet->setCellValue('B5', 'Planta');
   $sheet->setCellValue('C5', 'Ubicación');
   $sheet->setCellValue('D5', 'Código');
   $sheet->setCellValue('E5', 'Tipo');
   $sheet->setCellValue('F5', 'Fecha');
   $sheet->setCellValue('G5', 'Estado');
   
   // Aplicar estilo a encabezados
   $headerStyle = [
       'font' => [
           'bold' => true,
       ],
       'fill' => [
           'fillType' => Fill::FILL_SOLID,
           'startColor' => [
               'rgb' => '4e73df',
           ],
       ],
       'font' => [
           'color' => [
               'rgb' => 'FFFFFF',
           ],
       ],
       'borders' => [
           'allBorders' => [
               'borderStyle' => Border::BORDER_THIN,
           ],
       ],
   ];
   $sheet->getStyle('A5:G5')->applyFromArray($headerStyle);
   
   // Llenar datos
   $row = 6;
   if ($resultado->num_rows > 0) {
       while ($fila = $resultado->fetch_assoc()) {
           $sheet->setCellValue('A' . $row, $fila['id']);
           $sheet->setCellValue('B' . $row, $fila['nombre_planta']);
           $sheet->setCellValue('C' . $row, $fila['ubicacion']);
           $sheet->setCellValue('D' . $row, $fila['codigo']);
           $sheet->setCellValue('E' . $row, $fila['tipo_mantenimiento']);
           $sheet->setCellValue('F' . $row, date('d/m/Y', strtotime($fila['fecha_mantenimiento'])));
           $sheet->setCellValue('G' . $row, $fila['estado_salud']);
           $row++;
       }
   }
   
   // Aplicar estilo a los datos
   $dataStyle = [
       'borders' => [
           'allBorders' => [
               'borderStyle' => Border::BORDER_THIN,
           ],
       ],
   ];
   $sheet->getStyle('A6:G' . ($row - 1))->applyFromArray($dataStyle);
   
   // Aplicar auto-filtro
   $sheet->setAutoFilter('A5:G' . ($row - 1));
   
   // Ajustar ancho de columnas
   foreach (range('A', 'G') as $col) {
       $sheet->getColumnDimension($col)->setAutoSize(true);
   }
   
   // Crear escritor de Excel
   $writer = new Xlsx($spreadsheet);
   
   // Establecer cabeceras para descarga
   header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
   header('Content-Disposition: attachment;filename="Mantenimientos_' . date('Ymd_His') . '.xlsx"');
   header('Cache-Control: max-age=0');
   
   // Guardar archivo y enviarlo al navegador
   $writer->save('php://output');
   exit;
}

// Función para exportar un mantenimiento individual a Excel
function exportarExcelIndividual($conexion, $id) {
   require_once '../vendor/autoload.php';
   
   use PhpOffice\PhpSpreadsheet\Spreadsheet;
   use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
   use PhpOffice\PhpSpreadsheet\Style\Fill;
   use PhpOffice\PhpSpreadsheet\Style\Border;
   
   $id = intval($id);
   
   // Consultar datos
   $sql = "SELECT * FROM mantenimientos WHERE id = ?";
   $stmt = $conexion->prepare($sql);
   $stmt->bind_param("i", $id);
   $stmt->execute();
   $resultado = $stmt->get_result();
   
   if ($resultado->num_rows === 0) {
       $_SESSION['mensaje_error'] = "Registro no encontrado.";
       header('Location: ../vista/listar_reportes.php');
       exit;
   }
   
   $mantenimiento = $resultado->fetch_assoc();
   
   // Crear instancia de Spreadsheet
   $spreadsheet = new Spreadsheet();
   $sheet = $spreadsheet->getActiveSheet();
   $sheet->setTitle('Mantenimiento');
   
   // Título
   $sheet->setCellValue('A1', 'PARQUE INDUSTRIAL SANTIVÁÑEZ');
   $sheet->mergeCells('A1:E1');
   $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
   
   $sheet->setCellValue('A2', 'INFORME DE MANTENIMIENTO');
   $sheet->mergeCells('A2:E2');
   $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
   
   $sheet->setCellValue('A3', 'Fecha de generación: ' . date('d/m/Y H:i:s'));
   $sheet->mergeCells('A3:E3');
   
   // Datos de la planta
   $sheet->setCellValue('A5', 'Nombre de la planta:');
   $sheet->setCellValue('B5', $mantenimiento['nombre_planta']);
   $sheet->mergeCells('B5:E5');
   $sheet->getStyle('A5')->getFont()->setBold(true);
   
   $sheet->setCellValue('A6', 'Código:');
   $sheet->setCellValue('B6', $mantenimiento['codigo']);
   $sheet->getStyle('A6')->getFont()->setBold(true);
   
   $sheet->setCellValue('C6', 'Ubicación:');
   $sheet->setCellValue('D6', $mantenimiento['ubicacion']);
   $sheet->mergeCells('D6:E6');
   $sheet->getStyle('C6')->getFont()->setBold(true);
   
   $sheet->setCellValue('A7', 'Fecha de mantenimiento:');
   $sheet->setCellValue('B7', date('d/m/Y', strtotime($mantenimiento['fecha_mantenimiento'])));
   $sheet->getStyle('A7')->getFont()->setBold(true);
   
   $sheet->setCellValue('C7', 'Estado de salud:');
   $sheet->setCellValue('D7', $mantenimiento['estado_salud']);
   $sheet->getStyle('C7')->getFont()->setBold(true);
   
   $sheet->setCellValue('A8', 'Tipo de mantenimiento:');
   $sheet->setCellValue('B8', $mantenimiento['tipo_mantenimiento']);
   $sheet->getStyle('A8')->getFont()->setBold(true);
   
   $sheet->setCellValue('C8', 'Responsable:');
   $sheet->setCellValue('D8', $mantenimiento['responsable']);
   $sheet->getStyle('C8')->getFont()->setBold(true);
   
   // Descripción de actividad
   $sheet->setCellValue('A10', 'DESCRIPCIÓN DE LA ACTIVIDAD:');
   $sheet->mergeCells('A10:E10');
   $sheet->getStyle('A10')->getFont()->setBold(true);
   
   $sheet->setCellValue('A11', $mantenimiento['descripcion_actividad']);
   $sheet->mergeCells('A11:E13');
   $sheet->getStyle('A11')->getAlignment()->setWrapText(true);
   
   // Materiales utilizados
   $sheet->setCellValue('A15', 'MATERIALES UTILIZADOS:');
   $sheet->mergeCells('A15:E15');
   $sheet->getStyle('A15')->getFont()->setBold(true);
   
   $materiales = !empty($mantenimiento['materiales']) ? $mantenimiento['materiales'] : 'No especificados';
   $sheet->setCellValue('A16', $materiales);
   $sheet->mergeCells('A16:E17');
   $sheet->getStyle('A16')->getAlignment()->setWrapText(true);
   
   // Observaciones
   $sheet->setCellValue('A19', 'OBSERVACIONES:');
   $sheet->mergeCells('A19:E19');
   $sheet->getStyle('A19')->getFont()->setBold(true);
   
   $observaciones = !empty($mantenimiento['observaciones']) ? $mantenimiento['observaciones'] : 'No hay observaciones';
   $sheet->setCellValue('A20', $observaciones);
   $sheet->mergeCells('A20:E21');
   $sheet->getStyle('A20')->getAlignment()->setWrapText(true);
   
   // Próximo mantenimiento
   $sheet->setCellValue('A23', 'Próximo mantenimiento programado:');
   $fecha_proximo = !empty($mantenimiento['fecha_proximo']) ? date('d/m/Y', strtotime($mantenimiento['fecha_proximo'])) : 'No programado';
   $sheet->setCellValue('C23', $fecha_proximo);
   $sheet->mergeCells('C23:E23');
   $sheet->getStyle('A23')->getFont()->setBold(true);
   
   // Firma
   $sheet->setCellValue('A25', 'Firma del responsable:');
   $sheet->setCellValue('B25', $mantenimiento['firma'] ?: $mantenimiento['responsable']);
   $sheet->mergeCells('B25:E25');
   $sheet->getStyle('A25')->getFont()->setBold(true);
   
   // Aplicar estilos adicionales
   $styleArray = [
       'borders' => [
           'allBorders' => [
               'borderStyle' => Border::BORDER_THIN,
           ],
       ],
   ];
   $sheet->getStyle('A5:E8')->applyFromArray($styleArray);
   $sheet->getStyle('A10:E13')->applyFromArray($styleArray);
   $sheet->getStyle('A15:E17')->applyFromArray($styleArray);
   $sheet->getStyle('A19:E21')->applyFromArray($styleArray);
   $sheet->getStyle('A23:E23')->applyFromArray($styleArray);
   $sheet->getStyle('A25:E25')->applyFromArray($styleArray);
   
   // Ajustar ancho de columnas
   foreach (range('A', 'E') as $col) {
       $sheet->getColumnDimension($col)->setAutoSize(true);
   }
   
   // Crear escritor de Excel
   $writer = new Xlsx($spreadsheet);
   
   // Establecer cabeceras para descarga
   header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
   header('Content-Disposition: attachment;filename="Mantenimiento_' . $mantenimiento['codigo'] . '_' . date('Ymd') . '.xlsx"');
   header('Cache-Control: max-age=0');
   
   // Guardar archivo y enviarlo al navegador
   $writer->save('php://output');
   exit;
}

// Función para enviar por email
function enviarPorEmail($conexion, $id, $tipo) {
   $id = intval($id);
   
   // Verificar el tipo de documento
   if ($tipo === 'mantenimiento') {
       $sql = "SELECT * FROM mantenimientos WHERE id = ?";
   } elseif ($tipo === 'reporte') {
       $sql = "SELECT * FROM reportes WHERE id_reporte = ?";
   } else {
       $_SESSION['mensaje_error'] = "Tipo de documento no válido.";
       header('Location: ../vista/listar_reportes.php');
       exit;
   }
   
   // Obtener datos
   $stmt = $conexion->prepare($sql);
   $stmt->bind_param("i", $id);
   $stmt->execute();
   $resultado = $stmt->get_result();
   
   if ($resultado->num_rows === 0) {
       $_SESSION['mensaje_error'] = "Registro no encontrado.";
       header('Location: ../vista/listar_reportes.php');
       exit;
   }
   
   $documento = $resultado->fetch_assoc();
   
   // Preparar la información para el correo
   $titulo = ($tipo === 'mantenimiento') ? 'Informe de Mantenimiento - ' . $documento['nombre_planta'] : 'Reporte - ' . $documento['id_reporte'];
   $mensaje = ($tipo === 'mantenimiento') ? 
       "Informe de mantenimiento para " . $documento['nombre_planta'] . 
       "\nFecha: " . date('d/m/Y', strtotime($documento['fecha_mantenimiento'])) . 
       "\nTipo: " . $documento['tipo_mantenimiento'] . 
       "\nResponsable: " . $documento['responsable'] : 
       "Reporte #" . $documento['id_reporte'] . 
       "\nFecha: " . date('d/m/Y', strtotime($documento['fecha_reporte'])) . 
       "\nDescripción: " . $documento['descripcion'];
   
   // Enviar correo (implementación de envío de correo aquí)
   // ...

   $_SESSION['mensaje_exito'] = "Correo enviado correctamente.";
   header('Location: ../vista/listar_reportes.php');
   exit;
}