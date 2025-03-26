<?php
session_start();
require_once "../modelo/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['accion'])) {
    $accion = $_GET['accion'];

    if (isset($_POST['id_empleado']) && isset($_POST['cantidad']) && isset($_POST['fecha'])) {
        try {
            $conn = new Conexion();
            $conexion = $conn->Conectar();

            // Sanitizar entradas
            $id_empleado = filter_var($_POST['id_empleado'], FILTER_SANITIZE_NUMBER_INT);
            $cantidad = filter_var($_POST['cantidad'], FILTER_SANITIZE_NUMBER_INT);
            $fecha = filter_var($_POST['fecha'], FILTER_SANITIZE_STRING);
            $observaciones = isset($_POST['observaciones']) ? 
                            filter_var($_POST['observaciones'], FILTER_SANITIZE_STRING) : '';

            // Determinar la tabla según la acción
            switch ($accion) {
                case 'alta':
                    $tabla = 'alta_produccion';
                    $redirect = '../vista/alta_produccion.php';
                    break;
                case 'baja':
                    $tabla = 'baja_produccion';
                    $redirect = '../vista/baja_produccion.php';
                    break;
                case 'riesgo':
                    $tabla = 'zonas_riesgo';
                    $redirect = '../vista/zonas_riesgo.php';
                    break;
                default:
                    throw new Exception("Acción no válida");
            }

            // Preparar consulta
            $stmt = $conexion->prepare("INSERT INTO $tabla (id_empleado, cantidad, fecha, observaciones) 
                                        VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $id_empleado, $cantidad, $fecha, $observaciones);

            // Ejecutar consulta
            if ($stmt->execute()) {
                $_SESSION['mensaje'] = "Registro guardado exitosamente en $tabla";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                throw new Exception("Error al guardar el registro en $tabla");
            }

            $stmt->close();
            $conexion->close();

        } catch (Exception $e) {
            error_log("Error en $accion: " . $e->getMessage());
            $_SESSION['mensaje'] = "Error al procesar el registro";
            $_SESSION['tipo_mensaje'] = "error";
        }

    } else {
        $_SESSION['mensaje'] = "Por favor complete todos los campos requeridos";
        $_SESSION['tipo_mensaje'] = "warning";
    }

    // Redirigir a la vista correspondiente
    header("Location: $redirect");
    exit();

} else {
    $_SESSION['mensaje'] = "Método de acceso no permitido";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: ../vista/plantas.php");
    exit();
}
?>
