<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantas</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Hide content sections by default */
        #alta-contenido, 
        #baja-contenido, 
        #riesgo-contenido {
            display: none;
        }
    </style>
    <script>
        function mostrarSeccion(seccion) {
            // Ocultar todos los contenidos
            document.getElementById('alta-contenido').style.display = 'none';
            document.getElementById('baja-contenido').style.display = 'none';
            document.getElementById('riesgo-contenido').style.display = 'none';
            
            // Mostrar el contenido de la sección seleccionada
            document.getElementById(seccion + '-contenido').style.display = 'block';
        }

        function volverInicio() {
            // Ocultar todos los contenidos de secciones
            document.getElementById('alta-contenido').style.display = 'none';
            document.getElementById('baja-contenido').style.display = 'none';
            document.getElementById('riesgo-contenido').style.display = 'none';
        }
    </script>
</head>
<body>
    <?php include("sidebar.php"); ?>
    <div class="contenedor">
        <h1>Gestión de Plantas</h1>
        <div class="botones">
            <button onclick="mostrarSeccion('alta')">🟢 Alta Producción</button>
            <button onclick="mostrarSeccion('baja')">🟡 Baja Producción</button>
            <button onclick="mostrarSeccion('riesgo')">🔴 Zonas de Riesgo</button>
        </div>

        <!-- Contenidos de cada sección -->
        <div id="alta-contenido">
            <h2>Plantas en Alta Producción</h2>
            <!-- Contenido de plantas en alta producción -->
            <div class="plantas-grid">
                <div class="planta">
                    <h3>Planta Industrial A12</h3>
                    <p>Sector: Norte</p>
                    <p>Rendimiento: 96%</p>
                </div>
                <!-- Más plantas de alta producción -->
            </div>
            <button onclick="volverInicio()">Volver al Inicio</button>
        </div>

        <div id="baja-contenido">
            <h2>Plantas en Baja Producción</h2>
            <!-- Contenido de plantas en baja producción -->
            <div class="plantas-grid">
                <!-- Detalles de plantas en baja producción -->
            </div>
            <button onclick="volverInicio()">Volver al Inicio</button>
        </div>

        <div id="riesgo-contenido">
            <h2>Zonas de Riesgo</h2>
            <!-- Contenido de zonas de riesgo -->
            <div class="plantas-grid">
                <!-- Detalles de plantas en zonas de riesgo -->
            </div>
            <button onclick="volverInicio()">Volver al Inicio</button>
        </div>
    </div>
</body>
</html>