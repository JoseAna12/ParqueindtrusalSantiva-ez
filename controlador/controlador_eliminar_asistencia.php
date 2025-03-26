<?php
// Incluir archivo de conexión a la base de datos
include("../modelo/conexion.php");

// Verificar si existe el parámetro "id" en la URL
if (!empty($_GET["id"])) {
    // Obtener el ID de la URL y sanitizarlo
    $id = (int) $_GET["id"];
    
    // Usar una consulta preparada para evitar inyecciones SQL
    $sql = $conexion->prepare("DELETE FROM asistencia WHERE id_asistencia = ?");
    $sql->bind_param("i", $id);
    
    // Ejecutar la consulta
    if ($sql->execute()) {
        ?>
        <script>
            function notificacion() {
                new PNotify({
                    title: "CORRECTO",
                    type: "success",
                    text: "Asistencia eliminada correctamente",
                    styling: "bootstrap3"
                });
            }
            notificacion(); // Llamar la notificación
        </script>
        <script>
            setTimeout(() => {
                window.location.href = "ruta_a_la_pagina_de_asistencias.php"; // Redirigir a la página de asistencias
            }, 2000); // Redirige después de 2 segundos
        </script>
        <?php
    } else {
        ?>
        <script>
            function notificacion() {
                new PNotify({
                    title: "ERROR",
                    type: "error", 
                    text: "Error al eliminar la asistencia",
                    styling: "bootstrap3"
                });
            }
            notificacion(); // Llamar la notificación
        </script>
        <script>
            setTimeout(() => {
                window.history.replaceState(null, null, window.location.pathname); // Refresca la página sin cambiar la URL
            }, 0);
        </script>
        <?php
    }

    // Cerrar la conexión a la base de datos
    $sql->close();
} else {
    // Si no se recibe un ID, mostrar un mensaje de error
    ?>
    <script>
        function notificacion() {
            new PNotify({
                title: "ERROR",
                type: "error",
                text: "ID de asistencia no válido",
                styling: "bootstrap3"
            });
        }
        notificacion(); // Llamar la notificación
    </script>
    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname); // Refresca la página sin cambiar la URL
        }, 0);
    </script>
    <?php
}
?>
