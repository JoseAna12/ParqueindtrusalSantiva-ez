<?php
// Verificar sesión pero no incluir layouts completos
session_start();
if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    echo '<div class="error-auth">Acceso no autorizado</div>';
    exit;
}

// Incluir la conexión a la base de datos
include "../modelo/conexion.php";
?>

<div class="container mt-4">
    <h4 class="text-center text-primary">📌 REGISTRO DE ALTA PRODUCCIÓN</h4>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card p-4">
                <div class="card-body">
                    <form id="form-alta-produccion">
                        <div class="mb-3">
                            <label for="id_empleado" class="form-label">👨‍💼 Empleado</label>
                            <select class="form-select" name="id_empleado" required>
                                <?php
                                $query = "SELECT id_empleado, nombre, apellido FROM empleado";
                                $result = $conexion->query($query);
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id_empleado'] . "'>" . 
                                             $row['nombre'] . " " . $row['apellido'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No hay empleados disponibles</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">📦 Cantidad Producida</label>
                            <input type="number" class="form-control" name="cantidad" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">📅 Fecha</label>
                            <input type="date" class="form-control" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">📝 Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="3"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-100">✅ Registrar Alta Producción</button>
                        </div>
                    </form>
                    <div id="mensaje-resultado" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script para el envío del formulario usando AJAX
document.getElementById('form-alta-produccion').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Mostrar indicador de carga
    document.getElementById('mensaje-resultado').innerHTML = 
        '<div class="alert alert-info">Procesando solicitud...</div>';
    
    fetch('../controlador/controlador_alta_produccion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('mensaje-resultado').innerHTML = 
                `<div class="alert alert-success">${data.message}</div>`;
            document.getElementById('form-alta-produccion').reset();
        } else {
            document.getElementById('mensaje-resultado').innerHTML = 
                `<div class="alert alert-danger">${data.message}</div>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('mensaje-resultado').innerHTML = 
            '<div class="alert alert-danger">Error al procesar la solicitud. Intente nuevamente.</div>';
    });
});
</script>