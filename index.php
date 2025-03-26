<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asistencia - Parque Industrial Santivañez</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="public/estilos/estilos.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="vista/login/css/all.min.css">
    <link rel="stylesheet" href="vista/login/css/fontawesome.min.css">
    <!-- Fuentes de Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <!-- Estilos personalizados embebidos -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a472a, #85a603);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            color: #fff;
        }

        .main-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
        }

        .logo {
            width: 100px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2.2rem;
        }

        .datetime {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .btn-login {
            background: linear-gradient(45deg, #1a472a, #85a603);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
            display: inline-block;
            cursor: pointer;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #85a603, #ffd700);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="login-container">
            <img src="vista/login/img/ari.png" alt="Logo" class="logo">
            <h1 class="mb-4 fw-bold text-success">BIENVENIDOS</h1>
            <div class="datetime mb-4" id="datetime"></div>
            <a href="vista/login/login.php" class="btn-login">Ingresar al sistema</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para mostrar fecha y hora en tiempo real -->
    <script>
    function updateDateTime() {
        const now = new Date();
        const options = {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        // Combina fecha y hora en una sola cadena
        document.getElementById('datetime').textContent = now.toLocaleDateString('es-ES', options);
    }

    // Actualizar fecha y hora cada segundo
    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>

</body>

</html>
