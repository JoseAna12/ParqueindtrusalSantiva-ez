<?php
$conexion = new mysqli("localhost", "root", "1234", "sis_asistencia");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>