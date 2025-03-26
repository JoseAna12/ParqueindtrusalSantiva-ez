<?php
session_start(); // Asegúrate de iniciar la sesión

// Incluir el archivo de conexión
include '../modelo/conexion.php';

if (!empty($_POST["btningresar"])) {
    if (!empty($_POST["usuario"]) && !empty($_POST["password"])) {
        $usuario = $_POST["usuario"];
        $password = md5($_POST["password"]); // Encriptación de contraseña
        
        // Realiza la consulta
        $sql = $conexion->query("SELECT * FROM usuario WHERE usuario='$usuario' AND password='$password'");
        if ($datos = $sql->fetch_object()) {
            $_SESSION["nombre"] = $datos->nombre;
            $_SESSION["apellido"] = $datos->apellido;
            header("Location: ../vista/inicio.php"); // Redirige a inicio.php
        } else {
            echo "<div class='alert alert-danger'>El usuario no existe</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Los campos están vacíos</div>";
    }
}
?>
