<?php
session_start();
require_once '../modelo/conexion.php';

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar que todos los campos requeridos estén presentes
    if (!empty($_POST['zona']) && !empty($_POST['nivel_riesgo']) && !empty($_POST['descripcion']) && 
        !empty($_POST['medidas_prevencion']) && !empty($_POST['fecha_identificacion'])) {
        
        // Sanitizar y validar entradas
        $zona = htmlspecialchars(trim($_POST['zona']));
        $nivel_riesgo = htmlspecialchars(trim($_POST['nivel_riesgo']));
        $descripcion = htmlspecialchars(trim($_POST['descripcion']));
        $medidas_prevencion = htmlspecialchars(trim($_POST['medidas_prevencion']));
        $fecha_identificacion = htmlspecialchars(trim($_POST['fecha_identificacion']));

        try {
            // Crear conexión
            $conn = new Conexion();
            $conexion = $conn->Conectar();

            // Preparar la consulta
            $stmt = $conexion->prepare("INSERT INTO zonas_riesgo (zona, nivel_riesgo, descripcion, 
                                      medidas_prevencion, fecha_identificacion) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $zona, $nivel_riesgo, $descripcion, $medidas_prevencion, $fecha_identificacion);
            $stmt->execute();

            // Verificar si se insertó correctamente
            if ($stmt->affected_rows > 0) {
                $_SESSION['mensaje'] = "Zona de riesgo registrada correctamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al registrar la zona de riesgo";
                $_SESSION['tipo_mensaje'] = "error";
            }
            $stmt->close();
            $conexion->close();
            header("Location: ../vista/zonas_de_riesgo.php");
            exit;
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            $_SESSION['mensaje'] = "Error interno del servidor";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: ../vista/zonas_de_riesgo.php");
            exit;
        }
    } else {
        $_SESSION['mensaje'] = "Complete todos los campos";
        $_SESSION['tipo_mensaje'] = "warning";
        header("Location: ../vista/zonas_de_riesgo.php");
        exit;
    }
} else {
    $_SESSION['mensaje'] = "Acceso no permitido";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: ../vista/zonas_de_riesgo.php");
    exit;
}
?>