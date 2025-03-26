<?php
session_start();

if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    header('Location: login/login.php');
    exit;
}

require_once '../libs/fpdf.php';
require_once '../modelo/conexion.php';

class PDF extends FPDF {
    private $titulo = 'DIRECTORIO MIXTO PARQUE INDUSTRIAL SANTIVAÑEZ';
    private $subtitulo = 'Lista de Reportes';
    
    // Método para cambiar el subtítulo
    function setSubtitulo($subtitulo) {
        $this->subtitulo = $subtitulo;
    }
    
    function Header() {
        // Logo
        $this->Image('login/img/ari.jpg', 10, 8, 30);
        
        // Título principal
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(30); // Mover a la derecha después del logo
        $this->MultiCell(0, 10, utf8_decode($this->titulo), 0, 'C');
        
        // Subtítulo
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(30); // Mover a la derecha después del logo
        $this->Cell(0, 10, utf8_decode($this->subtitulo), 0, 1, 'C');
        
        // Línea separadora
        $this->Line(10, 45, 200, 45);
        
        // Salto de línea
        $this->Ln(20);
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        
        // Fecha actual
        $this->Cell(0, 10, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 0, 'L');
        
        // Número de página
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }
}

// Determinar qué tipo de reporte generar
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'reportes';

// Crear instancia de PDF
$pdf = new PDF();

switch ($tipo) {
    case 'mantenimiento':
        generarPDFMantenimiento($pdf, $conexion);
        break;
    
    case 'historial':
        generarPDFHistorialMantenimiento($pdf, $conexion);
        break;
    
    case 'ruta':
        generarPDFHojaRuta($pdf, $conexion);
        break;
    
    case 'reportes':
    default:
        generarPDFReportes($pdf, $conexion);
        break;
}

// Función para generar PDF de un registro de mantenimiento
function generarPDFMantenimiento($pdf, $conexion) {
    $pdf->setSubtitulo('Registro de Mantenimiento de Planta');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    
    // Obtener datos específicos si se proporcionan
    $planta = isset($_GET['planta']) ? $_GET['planta'] : '';
    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';
    
    // Si tenemos un código específico, intentamos obtener los datos de la BD
    if (!empty($codigo)) {
        $sql = "SELECT * FROM mantenimiento_plantas WHERE codigo = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $datos = $result->fetch_assoc();
        } else {
            // Si no hay datos, mostrar formulario vacío o con datos proporcionados
            $datos = [
                'nombre_planta' => $planta,
                'codigo' => $codigo,
                'ubicacion' => '',
                'fecha_mantenimiento' => date('Y-m-d'),
                'tipo_mantenimiento' => '',
                'responsable' => '',
                'descripcion_actividad' => '',
                'materiales' => '',
                'observaciones' => '',
                'estado_salud' => '',
                'fecha_proximo' => '',
                'firma' => ''
            ];
        }
    } else {
        // Formulario en blanco o con datos básicos
        $datos = [
            'nombre_planta' => $planta,
            'codigo' => '',
            'ubicacion' => '',
            'fecha_mantenimiento' => date('Y-m-d'),
            'tipo_mantenimiento' => '',
            'responsable' => '',
            'descripcion_actividad' => '',
            'materiales' => '',
            'observaciones' => '',
            'estado_salud' => '',
            'fecha_proximo' => '',
            'firma' => ''
        ];
    }
    
    // Crear tabla con los datos
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Detalles del Mantenimiento', 0, 1, 'C');
    $pdf->Ln(5);
    
    // Encabezados de la tabla
    $pdf->SetFillColor(64, 97, 238); // Color azul para encabezados
    $pdf->SetTextColor(255, 255, 255); // Texto en blanco
    $pdf->Cell(60, 10, 'Campo', 1, 0, 'C', true);
    $pdf->Cell(130, 10, 'Valor', 1, 1, 'C', true);
    
    // Restaurar color de texto para el contenido
    $pdf->SetTextColor(0, 0, 0);
    
    // Datos de la tabla
    $pdf->Cell(60, 10, 'Nombre de la planta', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['nombre_planta']), 1, 1);
    
    $pdf->Cell(60, 10, 'Código', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['codigo']), 1, 1);
    
    $pdf->Cell(60, 10, 'Ubicación', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['ubicacion']), 1, 1);
    
    $pdf->Cell(60, 10, 'Fecha de Mantenimiento', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['fecha_mantenimiento']), 1, 1);
    
    $pdf->Cell(60, 10, 'Tipo de Mantenimiento', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['tipo_mantenimiento']), 1, 1);
    
    $pdf->Cell(60, 10, 'Responsable', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['responsable']), 1, 1);
    
    $pdf->Cell(60, 10, 'Descripción de la Actividad', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['descripcion_actividad']), 1, 1);
    
    $pdf->Cell(60, 10, 'Materiales', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['materiales']), 1, 1);
    
    $pdf->Cell(60, 10, 'Observaciones', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['observaciones']), 1, 1);
    
    $pdf->Cell(60, 10, 'Estado de Salud', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['estado_salud']), 1, 1);
    
    $pdf->Cell(60, 10, 'Próximo Mantenimiento', 1);
    $pdf->Cell(130, 10, utf8_decode($datos['fecha_proximo']), 1, 1);
    
    // Espacio para firma
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Firma del Responsable:', 0, 1);
    $pdf->Cell(0, 10, '__________________________', 0, 1);
    
    $pdf->Output('D', 'Mantenimiento_' . date('Y-m-d') . '.pdf');
}

// Función para generar PDF del historial de mantenimiento
function generarPDFHistorialMantenimiento($pdf, $conexion) {
    // ... (código existente)
}

// Función para generar PDF de hoja de ruta
function generarPDFHojaRuta($pdf, $conexion) {
    // ... (código existente)
}
?>