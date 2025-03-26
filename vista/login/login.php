<?php session_start(); // Iniciar sesión para recibir mensajes de error ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">
    <!-- Añadir FontAwesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Inicio de sesión</title>
</head>
<body>
    <img class="wave" src="img/wave.png">
    <div class="container">
        <div class="img">
            <img src="img/ari.png" alt="Logo">
        </div>
        <div class="login-content">
            <form method="POST" action="../../controlador/login.php">
                <img src="img/avatar.svg" alt="Avatar">
                <h2 class="title">BIENVENIDO</h2>
                
                <?php
                // Mostrar mensajes de error si existen
                if (isset($_SESSION['error_message'])) {
                    echo "<div class='alert alert-danger text-center'>" . 
                          htmlspecialchars($_SESSION['error_message']) . 
                          "</div>";
                    unset($_SESSION['error_message']); // Limpiar el mensaje después de mostrarlo
                }
                ?>
                
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input type="text" class="input" name="usuario" required autocomplete="off">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" class="input" name="password" id="password" required>
                    </div>
                    <div class="view">
                        <i class="fas fa-eye" id="verPassword"></i>
                    </div>
                </div>
                
                <div class="text-center">
                    <a class="font-italic isai5" href="recuperar_contraseña.php">Olvidé mi contraseña</a>
                </div>
                
                <input name="btningresar" class="btn" type="submit" value="INICIAR SESIÓN">
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Referencia al elemento del ojo y al input de contraseña
        const verPassword = document.getElementById('verPassword');
        const password = document.getElementById('password');
        
        // Agregar evento de clic al icono del ojo
        verPassword.addEventListener('click', function() {
            if (password.type === 'password') {
                password.type = 'text';
                verPassword.classList.remove('fa-eye');
                verPassword.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                verPassword.classList.remove('fa-eye-slash');
                verPassword.classList.add('fa-eye');
            }
        });
        
        // Efecto para los inputs
        const inputs = document.querySelectorAll('.input');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.parentNode.classList.add('focus');
                this.parentNode.querySelector('h5').classList.add('active');
            });
            
            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentNode.parentNode.classList.remove('focus');
                    this.parentNode.querySelector('h5').classList.remove('active');
                }
            });
            
            // Activar label si ya hay contenido
            if (input.value !== '') {
                input.parentNode.parentNode.classList.add('focus');
                input.parentNode.querySelector('h5').classList.add('active');
            }
        });
    });
    </script>
</body>
</html>