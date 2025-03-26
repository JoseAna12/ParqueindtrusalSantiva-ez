<?php 
session_start();

// Verificar autenticación de sesión
if (empty($_SESSION['nombre']) && empty($_SESSION['apellido'])) {
    header('Location: login.php');
    exit();
}

require_once '../modelo/conexion.php';

// Manejo del registro de usuario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btnregistrar'])) {
    // Limpiar y validar entradas manualmente
    $nombre = trim(htmlspecialchars(strip_tags($_POST['nombre'])));
    $apellido = trim(htmlspecialchars(strip_tags($_POST['apellido'])));
    $usuario = trim(htmlspecialchars(strip_tags($_POST['usuario'])));
    $contraseña = $_POST['contraseña'];

    $errors = [];

    // Validar longitud de los campos
    if (strlen($nombre) < 2 || strlen($nombre) > 50) {
        $errors[] = "El nombre debe tener entre 2 y 50 caracteres.";
    }
    if (strlen($apellido) < 2 || strlen($apellido) > 50) {
        $errors[] = "El apellido debe tener entre 2 y 50 caracteres.";
    }
    if (strlen($usuario) < 4 || strlen($usuario) > 20) {
        $errors[] = "El usuario debe tener entre 4 y 20 caracteres.";
    }
    if (strlen($contraseña) < 8) {
        $errors[] = "La contraseña debe tener al menos 8 caracteres.";
    }

    // Verificar si el usuario ya existe
    $stmt_check = $conexion->prepare("SELECT usuario FROM usuario WHERE usuario = ?");
    $stmt_check->bind_param("s", $usuario);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $errors[] = "El nombre de usuario ya está en uso.";
    }

    // Si no hay errores, proceder con el registro
    if (empty($errors)) {
        $hashed_password = password_hash($contraseña, PASSWORD_BCRYPT);

        $stmt = $conexion->prepare("INSERT INTO usuario (nombre, apellido, usuario, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $apellido, $usuario, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['registro_exitoso'] = "Usuario registrado correctamente.";
            header("Location: usuario.php"); // Redirige al inicio de usuario
            exit();
        } else {
            $errors[] = "Error al registrar el usuario: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?php require('./layout/topbar.php'); ?>
    <?php require('./layout/sidebar.php'); ?>

    <div class="page-content">
        <div class="container">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registro de Usuarios</h4>
                </div>
                <div class="card-body">
                    <?php 
                    if (!empty($errors)) {
                        echo "<div class='alert alert-danger'>";
                        foreach ($errors as $error) {
                            echo "<p class='mb-1'>• $error</p>";
                        }
                        echo "</div>";
                    }
                    ?>
                    <form action="" method="POST" id="registroForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" id="nombre" name="nombre" 
                                       class="form-control" 
                                       placeholder="Ingrese el nombre" 
                                       required 
                                       pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+" 
                                       minlength="2" 
                                       maxlength="50">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" id="apellido" name="apellido" 
                                       class="form-control" 
                                       placeholder="Ingrese el apellido" 
                                       required 
                                       pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+" 
                                       minlength="2" 
                                       maxlength="50">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" id="usuario" name="usuario" 
                                       class="form-control" 
                                       placeholder="Nombre de usuario" 
                                       required 
                                       minlength="4" 
                                       maxlength="20">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contraseña" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" id="contraseña" name="contraseña" 
                                           class="form-control" 
                                           placeholder="Contraseña" 
                                           required 
                                           minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Mínimo 8 caracteres</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" name="btnregistrar" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar
                            </button>
                            <a href="usuario.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require('./layout/footer.php'); ?>

    <script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('contraseña');
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    }
    </script>
</body>
</html>
