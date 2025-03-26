<?php
session_start();
require_once '../modelo/conexion.php';

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar que todos los campos requeridos estén presentes
    if (!empty($_POST['id_empleado']) && !empty($_POST['cantidad']) && !empty($_POST['motivo']) && 
        !empty($_POST['fecha']) && !empty($_POST['detalles'])) {
        
        // Sanitizar y validar entradas
        $id_empleado = htmlspecialchars(trim($_POST['id_empleado']));
        $cantidad = htmlspecialchars(trim($_POST['cantidad']));
        $motivo = htmlspecialchars(trim($_POST['motivo']));
        $fecha = htmlspecialchars(trim($_POST['fecha']));
        $detalles = htmlspecialchars(trim($_POST['detalles']));

        try {
            // Crear conexión
            $conn = new Conexion();
            $conexion = $conn->Conectar();

            // Preparar la consulta
            $stmt = $conexion->prepare("INSERT INTO baja_produccion (id_empleado, cantidad, motivo, fecha, detalles) 
                                      VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("idsss", $id_empleado, $cantidad, $motivo, $fecha, $detalles);
            $stmt->execute();

            // Verificar si se insertó correctamente
            if ($stmt->affected_rows > 0) {
                $_SESSION['mensaje'] = "Registro guardado correctamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al guardar el registro";
                $_SESSION['tipo_mensaje'] = "error";
            }
            $stmt->close();
            $conexion->close();
            header("Location: ../vista/baja_produccion.php");
            exit;
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            $_SESSION['mensaje'] = "Error interno del servidor";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: ../vista/baja_produccion.php");
            exit;
        }
    } else {
        $_SESSION['mensaje'] = "Complete todos los campos";
        $_SESSION['tipo_mensaje'] = "warning";
        header("Location: ../vista/baja_produccion.php");
        exit;
    }
} else {
    $_SESSION['mensaje'] = "Acceso no permitido";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: ../vista/baja_produccion.php");
    exit;
}
?>