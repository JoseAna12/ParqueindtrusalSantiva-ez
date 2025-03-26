<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Verificar si se recibieron los datos necesarios
if (!isset($_POST['id_asistencia']) || !isset($_POST['hora_salida'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

include "../modelo/conexion.php";

$id_asistencia = intval($_POST['id_asistencia']);
$hora_salida = $_POST['hora_salida'];

// Registrar la salida en la base de datos
$sql = "UPDATE asistencia SET salida = ? WHERE id_asistencia = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('si', $hora_salida, $id_asistencia);

if ($stmt->execute()) {
    // Registrar en historial de acciones
    $usuario = $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];
    $accion = "Registró salida para asistencia ID: $id_asistencia";
    $fecha = date('Y-m-d H:i:s');
    
    $sql_log = "INSERT INTO log_actividad (usuario, accion, fecha) VALUES (?, ?, ?)";
    $stmt_log = $conexion->prepare($sql_log);
    
    if ($stmt_log) {
        $stmt_log->bind_param('sss', $usuario, $accion, $fecha);
        $stmt_log->execute();
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Salida registrada correctamente']);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al registrar salida: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>