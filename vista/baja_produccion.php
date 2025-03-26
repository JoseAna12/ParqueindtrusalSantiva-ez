<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    header('Location: login/login.php');
    exit;
}

// Incluir archivos de diseño
require_once 'layout/topbar.php';
require_once 'layout/sidebar.php';
?>

<div class="container mt-4">
    <h4 class="text-center text-secondary">REGISTRO DE BAJA PRODUCCIÓN</h4>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="../controlador/controlador_baja_produccion.php" method="POST">
                        <div class="mb-3">
                            <label for="id_empleado" class="form-label">Empleado</label>
                            <select class="form-select" name="id_empleado" required>
                                <?php
                                include "../modelo/conexion.php";
                                $query = "SELECT id_empleado, nombre, apellido FROM empleado";
                                $result = $conexion->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id_empleado'] . "'>" . 
                                         $row['nombre'] . " " . $row['apellido'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad Producida</label>
                            <input type="number" class="form-control" name="cantidad" required>
                        </div>
                        <div class="mb-3">
                            <label for="motivo" class="form-label">Motivo de Baja Producción</label>
                            <select class="form-select" name="motivo" required>
                                <option value="falla_equipo">Falla de Equipo</option>
                                <option value="falta_material">Falta de Material</option>
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="detalles" class="form-label">Detalles</label>
                            <textarea class="form-control" name="detalles" rows="3" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Registrar Baja Producción</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('./layout/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>