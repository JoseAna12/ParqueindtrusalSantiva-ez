<?php
session_start();
require_once "../modelo/conexion.php";
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que los campos requeridos estén presentes
    if (isset($_POST['id_empleado']) && isset($_POST['cantidad']) && isset($_POST['fecha'])) {
        
        try {
            $conn = new Conexion();
            $conexion = $conn->Conectar();

            // Sanitizar y validar entradas
            $id_empleado = filter_var($_POST['id_empleado'], FILTER_SANITIZE_NUMBER_INT);
            $cantidad = filter_var($_POST['cantidad'], FILTER_SANITIZE_NUMBER_INT);
            $fecha = filter_var($_POST['fecha'], FILTER_SANITIZE_STRING);
            $observaciones = isset($_POST['observaciones']) ? 
                            filter_var($_POST['observaciones'], FILTER_SANITIZE_STRING) : '';

            // Preparar la consulta
            $stmt = $conexion->prepare("INSERT INTO alta_produccion (id_empleado, cantidad, fecha, observaciones) 
                                      VALUES (?, ?, ?, ?)");
            
            // Vincular parámetros
            $stmt->bind_param("iiss", $id_empleado, $cantidad, $fecha, $observaciones);
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Registro de alta producción guardado exitosamente'
                ]);
            } else {
                throw new Exception("Error al guardar el registro");
            }

            $stmt->close();
            $conexion->close();

        } catch (Exception $e) {
            error_log("Error en alta producción: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al procesar el registro de alta producción: ' . $e->getMessage()
            ]);
        }

    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor complete todos los campos requeridos'
        ]);
    }

} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método de acceso no permitido'
    ]);
}

exit();
?>