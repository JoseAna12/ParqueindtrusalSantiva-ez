<?php
include "../modelo/conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_empleado = $_POST['id_empleado'];
    $descripcion = $_POST['descripcion'];
    $fecha_reporte = date('Y-m-d H:i:s');

    $sql = "INSERT INTO reportes (id_empleado, descripcion, fecha_reporte) 
            VALUES ('$id_empleado', '$descripcion', '$fecha_reporte')";

    if ($conexion->query($sql) === TRUE) {
        header('Location: index.php');  // Redirigir a la lista de reportes después de registrar
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}
?>
