<!doctype html> 
<html lang="es"> 
<head>     
    <meta charset="utf-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">     
    <meta http-equiv="x-ua-compatible" content="ie=edge">     
    <title>REPORTE DE MANTENIMIENTO PARQUE SANTIVAÑEZ</title>      
    <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">     
    <link href="../public/bootstrap5/css/bootstrap.min.css" rel="stylesheet">     
    <script src="https://kit.fontawesome.com/646ac4fad6.js" crossorigin="anonymous"></script>          
    <style>         
        body {             
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f6f7;
        }         
        
        .site-header {             
            background: #4a89dc;
            padding: 15px 20px;             
            color: white;             
            display: flex;             
            align-items: center;             
            justify-content: center;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }         
        
        .logo {
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-align: center;
            flex-grow: 1;
        }
        
        .user-menu {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .user-menu button {             
            background: none;             
            border: none;
            padding: 3px;
            transition: transform 0.2s;
        }
        
        .user-menu button:hover {
            transform: scale(1.05);
        }
        
        .user-menu img {             
            width: 40px;             
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
        }
    </style> 
</head> 
<body>     
    <header class="site-header">         
        <div class="logo">Parque Santivañez</div>
        <div class="user-menu dropdown">             
            <button id="dd-user-menu" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">                 
                <img src="../public/app/publico/img/user.svg" alt="Usuario">             
            </button>             
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dd-user-menu">                 
                <li class="dropdown-header text-center bg-primary text-white">Usuario</li>                 
                <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Perfil</a></li>                 
                <li><a class="dropdown-item" href="#"><i class="fas fa-lock"></i> Cambiar contraseña</a></li>                 
                <li><div class="dropdown-divider"></div></li>                 
                <li><a class="dropdown-item text-danger" id="logout-btn" href="#"><i class="fas fa-sign-out-alt"></i> Salir</a></li>             
            </ul>         
        </div>     
    </header>

    <div class="container mt-4">
        <h1 class="mb-4">Reporte de Mantenimiento Parque Santivañez</h1>
        <div class="row">
            <div class="col-12">
                    </div>
                </div>
            </div>
        </div>
    </div>
      
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>     
    <script>         
        // Funcionalidad para el botón de logout
        document.getElementById("logout-btn").addEventListener("click", function (event) {             
            event.preventDefault();             
            window.location.href = "http://localhost/sis_asistencia/vista/login/login.php";         
        });
    </script> 
</body> 
</html>