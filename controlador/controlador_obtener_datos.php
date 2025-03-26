<?php
/**
 * Controlador para exportación de datos a PDF y Excel
 * Sistema de mantenimiento de plantas - Parque Industrial Santiváñez
 */

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    header('Location: ../login/login.php');
    exit;
}

// Incluir archivo de conexión a la base de datos
require_once '../modelo/conexion.php';

// Si estamos generando un PDF, necesitamos la biblioteca TCPDF
require_once '../vendor/tecnickcom/tcpdf/tcpdf.php';

// Verificar el tipo de solicitud
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Crear instancia de conexión
$conexion = new Conexion();

// Procesar según la acción solicitada
switch ($accion) {
    case 'exportar_pdf':
        exportarPDF($conexion, $tipo, $id);
        break;
    case 'exportar_excel':
        exportarExcel($conexion, $tipo, $id);
        break;
    default:
        header('Location: ../index.php');
        exit;
}

/**
 * Exportar datos a PDF
 */
function exportarPDF($conexion, $tipo, $id) {
    // Crear nueva instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    
    // Configuración del documento
    $pdf->SetCreator('Sistema de Mantenimiento - Parque Industrial Santiváñez');
    $pdf->SetAuthor('Parque Industrial Santiváñez');
    $pdf->SetTitle('Reporte de Mantenimiento');
    $pdf->SetSubject('Reporte de Mantenimiento');
    $pdf->SetKeywords('Mantenimiento, Planta, Reporte');
    
    // Establecer información del encabezado
    $pdf->setHeaderData('', 0, 'PARQUE INDUSTRIAL SANTIVÁÑEZ', 'SISTEMA DE MANTENIMIENTO DE PLANTAS', array(0,64,255), array(0,64,128));
    $pdf->setHeaderFont(Array('helvetica', '', 10));
    
    // Establecer información del pie de página
    $pdf->setFooterData(array(0,64,0), array(0,64,128));
    $pdf->setFooterFont(Array('helvetica', '', 8));
    
    // Configurar márgenes
    $pdf->SetMargins(15, 27, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    
    // Configurar saltos de página automáticos
    $pdf->SetAutoPageBreak(TRUE, 25);
    
    // Configurar fuente predeterminada
    $pdf->SetFont('helvetica', '', 10);
    
    // Agregar una página
    $pdf->AddPage();
    
    // Contenido del PDF según el tipo
    switch ($tipo) {
        case 'mantenimiento':
            generarPDFMantenimiento($pdf, $conexion, $id);
            break;
        case 'ruta':
            generarPDFRuta($pdf, $conexion, $id);
            break;
        case 'historial':
            generarPDFHistorial($pdf, $conexion);
            break;
        default:
            $pdf->Write(0, 'Tipo de reporte no válido', '', 0, 'L', true, 0, false, false, 0);
            break;
    }
    
    // Cerrar y generar el PDF
    $nombre_archivo = 'Reporte_' . ucfirst($tipo) . '_' . date('Y-m-d') . '.pdf';
    $pdf->Output($nombre_archivo, 'D');
    exit;
}

/**
 * Generar contenido de PDF para mantenimiento
 */
function generarPDFMantenimiento($pdf, $conexion, $id) {
    // Consultar datos del mantenimiento
    $sql = "SELECT * FROM mantenimiento_plantas WHERE id_mantenimiento = ?";
    $params = [$id];
    
    try {
        $result = $conexion->consulta($sql, $params);
        
        if ($result && $result->num_rows > 0) {
            $datos = $result->fetch_assoc();
            
            // Título del documento
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'REGISTRO DE MANTENIMIENTO DE PLANTAS', 0, 1, 'C');
            $pdf->Ln(5);
            
            // Subtítulo con información básica
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'INFORMACIÓN DEL MANTENIMIENTO', 0, 1, 'L');
            
            // Detalles del mantenimiento
            $pdf->SetFont('helvetica', '', 10);
            
            // Crear tabla para la información básica
            $info = [
                ['Nombre de planta:', $datos['nombre_planta'] ?? 'N/A'],
                ['Código:', $datos['codigo'] ?? 'N/A'],
                ['Ubicación:', $datos['ubicacion'] ?? 'N/A'],
                ['Fecha:', $datos['fecha_mantenimiento'] ?? 'N/A'],
                ['Tipo:', $datos['tipo_mantenimiento'] ?? 'N/A'],
                ['Responsable:', $datos['responsable'] ?? 'N/A'],
                ['Estado de salud:', $datos['estado_salud'] ?? 'N/A'],
                ['Próximo mantenimiento:', $datos['fecha_proximo'] ?? 'N/A']
            ];
            
            foreach ($info as $row) {
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Cell(50, 7, $row[0], 0, 0);
                $pdf->SetFont('helvetica', '', 10);
                $pdf->Cell(0, 7, $row[1], 0, 1);
            }
            
            $pdf->Ln(5);
            
            // Descripción de la actividad
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'DESCRIPCIÓN DE LA ACTIVIDAD', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->MultiCell(0, 7, $datos['descripcion_actividad'] ?? 'Sin descripción', 0, 'L');
            
            $pdf->Ln(5);
            
            // Materiales utilizados
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'MATERIALES UTILIZADOS', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->MultiCell(0, 7, $datos['materiales'] ?? 'No se especificaron materiales', 0, 'L');
            
            $pdf->Ln(5);
            
            // Observaciones
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'OBSERVACIONES', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->MultiCell(0, 7, $datos['observaciones'] ?? 'Sin observaciones', 0, 'L');
            
            $pdf->Ln(15);
            
            // Espacio para firma
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 10, '____________________________', 0, 1, 'C');
            $pdf->Cell(0, 7, 'Firma del responsable', 0, 1, 'C');
            
        } else {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'No se encontraron datos para el ID de mantenimiento proporcionado.', 0, 1, 'C');
        }
    } catch (Exception $e) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Error al consultar la base de datos: ' . $e->getMessage(), 0, 1, 'C');
    }
}

/**
 * Generar contenido de PDF para hoja de ruta
 */
function generarPDFRuta($pdf, $conexion, $id) {
    // Consultar datos de la hoja de ruta
    $sql = "SELECT * FROM hojas_ruta WHERE id_hoja_ruta = ?";
    $params = [$id];
    
    try {
        $result = $conexion->consulta($sql, $params);
        
        if ($result && $result->num_rows > 0) {
            $datos = $result->fetch_assoc();
            
            // Título del documento
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'DIRECTORIO MIXTO PARQUE INDUSTRIAL SANTIVÁÑEZ', 0, 1, 'C');
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 10, 'HOJA DE RUTA', 0, 1, 'C');
            $pdf->Ln(5);
            
            // Información general
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'INFORMACIÓN GENERAL', 0, 1, 'L');
            
            // Crear tabla para la información básica
            $pdf->SetFont('helvetica', '', 10);
            
            // Primera fila de información
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(50, 7, 'REFERENCIA:', 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 7, $datos['referencia'] ?? 'N/A', 0, 1);
            
            // Segunda fila (procedencia y interno)
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(50, 7, 'PROCEDENCIA:', 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(100, 7, $datos['procedencia'] ?? 'N/A', 0, 0);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(15, 7, 'Int.:', 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 7, $datos['interno'] ?? 'N/A', 0, 1);
            
            // Tercera fila (número de registro, fecha y externo)
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(50, 7, 'N° DE REGISTRO:', 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(40, 7, $datos['num_registro'] ?? 'N/A', 0, 0);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(15, 7, 'FECHA:', 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            
            // Formatear fecha
            $fecha = '';
            if (!empty($datos['fecha_dia']) && !empty($datos['fecha_mes']) && !empty($datos['fecha_anio'])) {
                $fecha = $datos['fecha_dia'] . '/' . $datos['fecha_mes'] . '/' . $datos['fecha_anio'];
            } else if (!empty($datos['fecha'])) {
                $fecha = $datos['fecha'];
            } else {
                $fecha = 'N/A';
            }
            
            $pdf->Cell(45, 7, $fecha, 0, 0);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(15, 7, 'Ext.:', 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 7, $datos['externo'] ?? 'N/A', 0, 1);
            
            $pdf->Ln(5);
            
            // Información del destinatario
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'DESTINATARIO', 0, 1, 'L');
            
            // Recuadro para destinatario
            $pdf->SetFillColor(240, 240, 240);
            $pdf->Rect(15, $pdf->GetY(), 180, 70, 'DF');
            
            // Información del destinatario
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(50, 7, 'DESTINATARIO:', 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 7, $datos['destinatario1'] ?? 'N/A', 0, 1);
            
            // Fecha de recepción
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(50, 7, 'FECHA DE RECEPCIÓN:', 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            
            // Formatear fecha de recepción
            $fecha_recepcion = '';
            if (!empty($datos['recepcion1_dia']) && !empty($datos['recepcion1_mes']) && !empty($datos['recepcion1_anio'])) {
                $fecha_recepcion = $datos['recepcion1_dia'] . '/' . $datos['recepcion1_mes'] . '/' . $datos['recepcion1_anio'];
                if (!empty($datos['recepcion1_hora'])) {
                    $fecha_recepcion .= ' - ' . $datos['recepcion1_hora'];
                }
            } else {
                $fecha_recepcion = 'N/A';
            }
            
            $pdf->Cell(0, 7, $fecha_recepcion, 0, 1);
            
            // Asunto
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(50, 7, 'ASUNTO:', 0, 1);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->MultiCell(170, 7, $datos['asunto1'] ?? 'N/A', 0, 'L');
            
            $pdf->Ln(5);
            
            // Espacio para firmas - dividido en 3 columnas
            $pdf->SetY($pdf->GetY() + 5);
            
            // Primera columna - Derivado de
            $pdf->SetFont('helvetica', 'B', 10);
            $x_start = $pdf->GetX();
            $y_start = $pdf->GetY();
            $pdf->Cell(60, 7, 'DERIVADO DE:', 0, 1, 'C');
            $pdf->Rect($x_start, $y_start + 7, 60, 30);
            $pdf->SetXY($x_start, $y_start + 38);
            $pdf->Cell(60, 7, 'FIRMA Y SELLO', 0, 1, 'C');
            
            // Segunda columna - Fecha y hora
            $pdf->SetXY($x_start + 60, $y_start);
            $pdf->Cell(60, 7, 'FECHA Y HORA:', 0, 1, 'C');
            $pdf->SetXY($x_start + 60, $y_start + 15);
            
            // Formatear fecha de derivación
            $fecha_derivacion = '';
            if (!empty($datos['derivacion1_dia']) && !empty($datos['derivacion1_mes']) && !empty($datos['derivacion1_anio'])) {
                $fecha_derivacion = $datos['derivacion1_dia'] . '/' . $datos['derivacion1_mes'] . '/' . $datos['derivacion1_anio'];
            } else {
                $fecha_derivacion = 'N/A';
            }
            
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(60, 7, $fecha_derivacion, 0, 1, 'C');
            $pdf->SetXY($x_start + 60, $y_start + 25);
            $pdf->Cell(60, 7, $datos['derivacion1_hora'] ?? 'N/A', 0, 1, 'C');
            
            // Tercera columna - Firma de recepción
            $pdf->SetXY($x_start + 120, $y_start);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(60, 7, 'FIRMA DE RECEPCIÓN:', 0, 1, 'C');
            $pdf->Rect($x_start + 120, $y_start + 7, 60, 30);
            
        } else {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'No se encontraron datos para el ID de hoja de ruta proporcionado.', 0, 1, 'C');
        }
    } catch (Exception $e) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Error al consultar la base de datos: ' . $e->getMessage(), 0, 1, 'C');
    }
}

/**
 * Generar contenido de PDF para historial de mantenimiento
 */
function generarPDFHistorial($pdf, $conexion) {
    // Consultar datos del historial de mantenimiento
    $sql = "SELECT m.*, p.nombre as nombre_planta FROM mantenimiento_plantas m 
           LEFT JOIN plantas p ON m.id_planta = p.id_planta 
           ORDER BY m.fecha_mantenimiento DESC";
    
    try {
        $result = $conexion->consulta($sql);
        
        // Título del documento
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'HISTORIAL DE MANTENIMIENTO DE PLANTAS', 0, 1, 'C');
        $pdf->Ln(5);
        
        if ($result && $result->num_rows > 0) {
            // Crear tabla para el historial
            $header = array('ID', 'Planta', 'Ubicación', 'Tipo', 'Fecha', 'Responsable', 'Estado');
            
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array(
                    $row['id_mantenimiento'],
                    $row['nombre_planta'] ?? $row['nombre_planta_manual'],
                    $row['ubicacion'],
                    $row['tipo_mantenimiento'],
                    date('d/m/Y', strtotime($row['fecha_mantenimiento'])),
                    $row['responsable'],
                    $row['estado_salud']
                );
            }
            
            // Generar la tabla
            $pdf->SetFont('helvetica', '', 9);
            
            // Colores de fondo para encabezado
            $pdf->SetFillColor(66, 135, 245);
            $pdf->SetTextColor(255);
            $pdf->SetDrawColor(128, 128, 128);
            $pdf->SetLineWidth(0.3);
            $pdf->SetFont('', 'B');
            
            // Ancho de cada columna
            $w = array(15, 45, 30, 30, 25, 30, 25);
            
            // Encabezado
            for($i = 0; $i < count($header); $i++)
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
            $pdf->Ln();
            
            // Restaurar colores y fuentes
            $pdf->SetFillColor(224, 235, 255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');
            
            // Datos
            $fill = 0;
            foreach($data as $row)
            {
                $pdf->Cell($w[0], 6, $row[0], 'LR', 0, 'C', $fill);
                $pdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
                $pdf->Cell($w[2], 6, $row[2], 'LR', 0, 'L', $fill);
                $pdf->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill);
                $pdf->Cell($w[4], 6, $row[4], 'LR', 0, 'C', $fill);
                $pdf->Cell($w[5], 6, $row[5], 'LR', 0, 'L', $fill);
                $pdf->Cell($w[6], 6, $row[6], 'LR', 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }
            
            // Línea de cierre
            $pdf->Cell(array_sum($w), 0, '', 'T');
            
        } else {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 10, 'No hay registros de mantenimiento disponibles.', 0, 1, 'C');
        }
    } catch (Exception $e) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Error al consultar la base de datos: ' . $e->getMessage(), 0, 1, 'C');
    }
}

/**
 * Exportar datos a Excel
 */
function exportarExcel($conexion, $tipo, $id) {
    // Incluir librería PHPExcel
    require_once '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
    
    // Crear nueva instancia de PHPExcel
    $excel = new PHPExcel();
    
    // Configurar propiedades del documento
    $excel->getProperties()->setCreator("Sistema de Mantenimiento - Parque Industrial Santiváñez")
                           ->setLastModifiedBy("Sistema de Mantenimiento")
                           ->setTitle("Reporte de Mantenimiento")
                           ->setSubject("Reporte de Mantenimiento")
                           ->setDescription("Documento generado por el Sistema de Mantenimiento")
                           ->setKeywords("mantenimiento planta reporte")
                           ->setCategory("Reportes");
    
    // Contenido del Excel según el tipo
    switch ($tipo) {
        case 'mantenimiento':
            generarExcelMantenimiento($excel, $conexion, $id);
            break;
        case 'ruta':
            generarExcelRuta($excel, $conexion, $id);
            break;
        case 'historial':
            generarExcelHistorial($excel, $conexion);
            break;
        default:
            $excel->setActiveSheetIndex(0);
            $excel->getActiveSheet()->setCellValue('A1', 'Tipo de reporte no válido');
            break;
    }
    
    // Establecer índice de hoja activa a la primera hoja
    $excel->setActiveSheetIndex(0);
    
    // Configurar encabezados para la descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte_' . ucfirst($tipo) . '_' . date('Y-m-d') . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    // Generar el Excel y enviarlo al navegador
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $writer->save('php://output');
    exit;
}

/**
 * Generar contenido de Excel para mantenimiento
 */
function generarExcelMantenimiento($excel, $conexion, $id) {
    // Consultar datos del mantenimiento
    $sql = "SELECT * FROM mantenimiento_plantas WHERE id_mantenimiento = ?";
    $params = [$id];
    
    try {
        $result = $conexion->consulta($sql, $params);
        
        if ($result && $result->num_rows > 0) {
            $datos = $result->fetch_assoc();
            
            // Establecer hoja activa
            $sheet = $excel->getActiveSheet();
            $sheet->setTitle('Mantenimiento');
            
            // Formatear celdas para títulos
            $titleStyle = array(
                'font' => array(
                    'bold' => true,
                    'size' => 14
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                )
            );
            
            $subTitleStyle = array(
                'font' => array(
                    'bold' => true,
                    'size' => 12
                )
            );
            
            $headerStyle = array(
                'font' => array(
                    'bold' => true
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '4361EE')
                ),
                'font' => array(
                    'color' => array('rgb' => 'FFFFFF')
                )
            );
            
            // Título principal
            $sheet->setCellValue('A1', 'PARQUE INDUSTRIAL SANTIVÁÑEZ');
            $sheet->mergeCells('A1:F1');
            $sheet->getStyle('A1')->applyFromArray($titleStyle);
            
            $sheet->setCellValue('A2', 'REGISTRO DE MANTENIMIENTO DE PLANTAS');
            $sheet->mergeCells('A2:F2');
            $sheet->getStyle('A2')->applyFromArray($titleStyle);
            
            // Información del mantenimiento
            $sheet->setCellValue('A4', 'INFORMACIÓN DEL MANTENIMIENTO');
            $sheet->mergeCells('A4:F4');
            $sheet->getStyle('A4')->applyFromArray($subTitleStyle);
            
            // Datos básicos
            $sheet->setCellValue('A5', 'Nombre de planta:');
            $sheet->setCellValue('B5', $datos['nombre_planta'] ?? 'N/A');
            
            $sheet->setCellValue('A6', 'Código:');
            $sheet->setCellValue('B6', $datos['codigo'] ?? 'N/A');
            
            $sheet->setCellValue('A7', 'Ubicación:');
            $sheet->setCellValue('B7', $datos['ubicacion'] ?? 'N/A');
            
            $sheet->setCellValue('A8', 'Fecha:');
            $sheet->setCellValue('B8', $datos['fecha_mantenimiento'] ?? 'N/A');
            
            $sheet->setCellValue('A9', 'Tipo:');
            $sheet->setCellValue('B9', $datos['tipo_mantenimiento'] ?? 'N/A');
            
            $sheet->setCellValue('A10', 'Responsable:');
            $sheet->setCellValue('B10', $datos['responsable'] ?? 'N/A');
            
            $sheet->setCellValue('A11', 'Estado de salud:');
            $sheet->setCellValue('B11', $datos['estado_salud'] ?? 'N/A');
            
            $sheet->setCellValue('A12', 'Próximo mantenimiento:');
            $sheet->setCellValue('B12', $datos['fecha_proximo'] ?? 'N/A');
            
            // Formatear celda para mejorar presentación
            $sheet->getStyle('A5:A12')->getFont()->setBold(true);
            
            // Descripción de la actividad
            $sheet->setCellValue('A14', 'DESCRIPCIÓN DE LA ACTIVIDAD');
            $sheet->getStyle('A14')->applyFromArray($subTitleStyle);
            
            $sheet->setCellValue('A15', $datos['descripcion_actividad'] ?? 'Sin descripción');
            $sheet->mergeCells('A15:F15');
            
            // Materiales utilizados
            $sheet->setCellValue('A17', 'MATERIALES UTILIZADOS');
            $sheet->getStyle('A17')->applyFromArray($subTitleStyle);
            
            $sheet->setCellValue('A18', $datos['materiales'] ?? 'No se especificaron materiales');
            $sheet->mergeCells('A18:F18');
            
            // Observaciones
            $sheet->setCellValue('A20', 'OBSERVACIONES');
            $sheet->getStyle('A20')->applyFromArray($subTitleStyle);
            
            $sheet->setCellValue('A21', $datos['observaciones'] ?? 'Sin observaciones');
            $sheet->mergeCells('A21:F21');
            
            // Firma
            $sheet->setCellValue('C23', 'Firma del responsable:');
            $sheet->setCellValue('D23', $datos['firma'] ?? '');
            $sheet->getStyle('C23')->getFont()->setBold(true);
            
            // Ajustar ancho de columnas automáticamente
            foreach(range('A','F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        } else {
            $sheet = $excel->getActiveSheet();
            $sheet->setCellValue('A1', 'No se encontraron datos para el ID de mantenimiento proporcionado.');
        }
    } catch (Exception $e) {
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue('A1', 'Error al consultar la base de datos: ' . $e->getMessage());
    }
}

/**
 * Generar contenido de Excel para hoja de ruta
 */
function generarExcelRuta($excel, $conexion, $id) {
    // Consultar datos de la hoja de ruta
    $sql = "SELECT * FROM hojas_ruta WHERE id_hoja_ruta = ?";
    $params = [$id];
    
    try {
        $result = $conexion->consulta($sql, $params);
        
        if ($result && $result->num_rows > 0) {
            $datos = $result->fetch_assoc();
            
            // Establecer hoja activa
            $sheet = $excel->getActiveSheet();
            $sheet->setTitle('Hoja de Ruta');
            
            // Formatear celdas para títulos
            $titleStyle = array(
                'font' => array(
                    'bold' => true,
                    'size' => 14
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                )
            );
            
            $subTitleStyle = array(
                'font' => array(
                    'bold' => true,
                    'size' => 12
                )
            );
            
            // Título principal
            $sheet->setCellValue('A1', 'DIRECTORIO MIXTO PARQUE INDUSTRIAL SANTIVÁÑEZ');
            $sheet->mergeCells('A1:F1');
            $sheet->getStyle('A1')->applyFromArray($titleStyle);
            
            $sheet->setCellValue('A2', 'HOJA DE RUTA');
            $sheet->mergeCells('A2:F2');
            $sheet->getStyle('A2')->applyFromArray($titleStyle);
            
            // Información general
            $sheet->setCellValue('A4', 'INFORMACIÓN GENERAL');
            $sheet->mergeCells('A4:F4');
            $sheet->getStyle('A4')->applyFromArray($subTitleStyle);
            
            // Datos básicos
            $sheet->setCellValue('A5', 'Referencia:');
            $sheet->setCellValue('B5', $datos['referencia'] ?? 'N/A');
            
            $sheet->setCellValue('A6', 'Procedencia:');
            $sheet->setCellValue('B6', $datos['procedencia'] ?? 'N/A');
            $sheet->setCellValue('D6', 'Int.:');
            $sheet->setCellValue('E6', $datos['int'] ?? 'N/A');
            
            $sheet->setCellValue('A7', 'N° de Registro:');
            $sheet->setCellValue('B7', $datos['num_registro'] ?? 'N/A');
            $sheet->setCellValue('D7', 'Fecha:');
            
            // Formatear fecha
            $fecha = '';
            if (!empty($datos['fecha_dia']) && !empty($datos['fecha_mes']) && !empty($datos['fecha_anio'])) {
                $fecha = $datos['fecha_dia'] . '/' . $datos['fecha_mes'] . '/' . $datos['fecha_anio'];
            } else if (!empty($datos['fecha'])) {
                $fecha = $datos['fecha'];
            } else {
                $fecha = 'N/A';
            }
            
            $sheet->setCellValue('E7', $fecha);
            $sheet->setCellValue('F7', 'Ext.:');
            $sheet->setCellValue('G7', $datos['ext'] ?? 'N/A');
            
            // Destinatario
            $sheet->setCellValue('A9', 'DESTINATARIO');
            $sheet->mergeCells('A9:F9');
            $sheet->getStyle('A9')->applyFromArray($subTitleStyle);
            
            $sheet->setCellValue('A10', 'Destinatario:');
            $sheet->setCellValue('B10', $datos['destinatario1'] ?? 'N/A');
            
            $sheet->setCellValue('A11', 'Fecha recepción:');
            
            // Formatear fecha de recepción
            $fecha_recepcion = '';
            if (!empty($datos['recepcion1_dia']) && !empty($datos['recepcion1_mes']) && !empty($datos['recepcion1_anio'])) {
                $fecha_recepcion = $datos['recepcion1_dia'] . '/' . $datos['recepcion1_mes'] . '/' . $datos['recepcion1_anio'];
                if (!empty($datos['recepcion1_hora'])) {
                    $fecha_recepcion .= ' - ' . $datos['recepcion1_hora'];
                }
            } else {
                $fecha_recepcion = 'N/A';
            }
            
            $sheet->setCellValue('B11', $fecha_recepcion);
            
            $sheet->setCellValue('A12', 'Asunto:');
            $sheet->setCellValue('B12', $datos['asunto1'] ?? 'N/A');
            $sheet->mergeCells('B12:F12');
            
            // Información de derivación
            $sheet->setCellValue('A14', 'INFORMACIÓN DE DERIVACIÓN');
            $sheet->mergeCells('A14:F14');
            $sheet->getStyle('A14')->applyFromArray($subTitleStyle);
            
            $sheet->setCellValue('A15', 'Fecha:');
            
            // Formatear fecha de derivación
            $fecha_derivacion = '';
            if (!empty($datos['derivacion1_dia']) && !empty($datos['derivacion1_mes']) && !empty($datos['derivacion1_anio'])) {
                $fecha_derivacion = $datos['derivacion1_dia'] . '/' . $datos['derivacion1_mes'] . '/' . $datos['derivacion1_anio'];
            } else {
                $fecha_derivacion = 'N/A';
            }
            
            $sheet->setCellValue('B15', $fecha_derivacion);
            
            $sheet->setCellValue('A16', 'Hora:');
            $sheet->setCellValue('B16', $datos['derivacion1_hora'] ?? 'N/A');
            
            // Formatear celda para mejorar presentación
            foreach (array('A5', 'A6', 'D6', 'A7', 'D7', 'F7', 'A10', 'A11', 'A12', 'A15', 'A16') as $cell) {
                $sheet->getStyle($cell)->getFont()->setBold(true);
            }
            
            // Ajustar ancho de columnas automáticamente
            foreach(range('A','G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        } else {
            $sheet = $excel->getActiveSheet();
            $sheet->setCellValue('A1', 'No se encontraron datos para el ID de hoja de ruta proporcionado.');
        }
    } catch (Exception $e) {
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue('A1', 'Error al consultar la base de datos: ' . $e->getMessage());
    }
}

/**
 * Generar contenido de Excel para historial de mantenimiento
 */
function generarExcelHistorial($excel, $conexion) {
    // Consultar datos del historial de mantenimiento
    $sql = "SELECT m.*, p.nombre as nombre_planta FROM mantenimiento_plantas m 
           LEFT JOIN plantas p ON m.id_planta = p.id_planta 
           ORDER BY m.fecha_mantenimiento DESC";
    
    try {
        $result = $conexion->consulta($sql);
        
        // Establecer hoja activa
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Historial');
        
        // Formatear celdas para títulos
        $titleStyle = array(
            'font' => array(
                'bold' => true,
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );
        
        $headerStyle = array(
            'font' => array(
                'bold' => true
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '4361EE')
            ),
            'font' => array(
                'color' => array('rgb' => 'FFFFFF')
            )
        );
        
        // Título principal
        $sheet->setCellValue('A1', 'HISTORIAL DE MANTENIMIENTO DE PLANTAS');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->applyFromArray($titleStyle);
        
        if ($result && $result->num_rows > 0) {
            // Encabezados
            $sheet->setCellValue('A3', 'ID');
            $sheet->setCellValue('B3', 'Planta');
            $sheet->setCellValue('C3', 'Ubicación');
            $sheet->setCellValue('D3', 'Tipo');
            $sheet->setCellValue('E3', 'Fecha');
            $sheet->setCellValue('F3', 'Responsable');
            $sheet->setCellValue('G3', 'Estado');
            
            // Aplicar estilo a encabezados
            $sheet->getStyle('A3:G3')->applyFromArray($headerStyle);
            
            // Llenar datos
            $row = 4;
            while ($data = $result->fetch_assoc()) {
                $sheet->setCellValue('A' . $row, $data['id_mantenimiento']);
                $sheet->setCellValue('B' . $row, $data['nombre_planta'] ?? $data['nombre_planta_manual']);
                $sheet->setCellValue('C' . $row, $data['ubicacion']);
                $sheet->setCellValue('D' . $row, $data['tipo_mantenimiento']);
                $sheet->setCellValue('E' . $row, date('d/m/Y', strtotime($data['fecha_mantenimiento'])));
                $sheet->setCellValue('F' . $row, $data['responsable']);
                $sheet->setCellValue('G' . $row, $data['estado_salud']);
                
                $row++;
            }
            
            // Ajustar ancho de columnas automáticamente
            foreach(range('A','G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        } else {
            $sheet->setCellValue('A3', 'No hay registros de mantenimiento disponibles.');
            $sheet->mergeCells('A3:G3');
        }
    } catch (Exception $e) {
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue('A1', 'Error al consultar la base de datos: ' . $e->getMessage());
    }
}