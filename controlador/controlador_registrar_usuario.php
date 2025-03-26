<?php
// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST['btnregistrar'])) {
    header('Location: ../vista/registro_usuario.php');
    exit();
}

// Include database connection
require_once "../modelo/conexion.php";

// Sanitize and validate inputs
$nombre = filter_input(INPUT_POST, 'txtnombre', FILTER_SANITIZE_STRING);
$apellido = filter_input(INPUT_POST, 'txtapellido', FILTER_SANITIZE_STRING);
$usuario = filter_input(INPUT_POST, 'txtusuario', FILTER_SANITIZE_STRING);
$password = $_POST['txtpassword'];

// Validation array to collect errors
$errores = [];

// Validate nombre
if (empty($nombre) || strlen($nombre) < 2 || strlen($nombre) > 50) {
    $errores[] = "Nombre inválido. Debe tener entre 2 y 50 caracteres.";
}

// Validate apellido
if (empty($apellido) || strlen($apellido) < 2 || strlen($apellido) > 50) {
    $errores[] = "Apellido inválido. Debe tener entre 2 y 50 caracteres.";
}

// Validate usuario
if (empty($usuario) || strlen($usuario) < 4 || strlen($usuario) > 20) {
    $errores[] = "Usuario inválido. Debe tener entre 4 y 20 caracteres.";
}

// Validate password
if (empty($password) || strlen($password) < 8) {
    $errores[] = "Contraseña inválida. Debe tener al menos 8 caracteres.";
}

// Check for existing user
$stmt_check = $conexion->prepare("SELECT COUNT(*) AS total FROM usuario WHERE usuario = ?");
$stmt_check->bind_param("s", $usuario);
$stmt_check->execute();
$resultado_check = $stmt_check->get_result();
$datos_check = $resultado_check->fetch_object();

if ($datos_check->total > 0) {
    $errores[] = "El usuario ya existe. Por favor, elija otro nombre de usuario.";
}

// If there are no errors, proceed with registration
if (empty($errores)) {
    // Hash password securely
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Prepare insert statement
    $stmt_insert = $conexion->prepare("INSERT INTO usuario (nombre, apellido, usuario, password) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param("ssss", $nombre, $apellido, $usuario, $password_hash);

    // Execute registration
    try {
        $registro = $stmt_insert->execute();
        
        // Start session for success message
        session_start();
        $_SESSION['registro_mensaje'] = [
            'tipo' => 'success',
            'texto' => 'Usuario registrado correctamente.'
        ];
        
        // Redirect to user list or registration page
        header("Location: ../vista/lista_usuarios.php");
        exit();
    } catch (Exception $e) {
        $errores[] = "Error al registrar usuario: " . $e->getMessage();
    }
}

// If errors exist, store in session and redirect back
session_start();
$_SESSION['registro_errores'] = $errores;
header("Location: ../vista/registro_usuario.php");
exit();
?>