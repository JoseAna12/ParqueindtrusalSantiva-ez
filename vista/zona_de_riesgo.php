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
    <h4 class="text-center text-secondary">REGISTRO DE ZONAS DE RIESGO</h4>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="../controlador/controlador_zonas_de_riesgo.php" method="POST">
                        <div class="mb-3">
                            <label for="zona" class="form-label">Nombre de la Zona</label>
                            <input type="text" class="form-control" name="zona" required>
                        </div>
                        <div class="mb-3">
                            <label for="nivel_riesgo" class="form-label">Nivel de Riesgo</label>
                            <select class="form-select" name="nivel_riesgo" required>
                                <option value="bajo">Bajo</option>
                                <option value="medio">Medio</option>
                                <option value="alto">Alto</option>
                                <option value="critico">Crítico</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción del Riesgo</label>
                            <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="medidas_prevencion" class="form-label">Medidas de Prevención</label>
                            <textarea class="form-control" name="medidas_prevencion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_identificacion" class="form-label">Fecha de Identificación</label>
                            <input type="date" class="form-control" name="fecha_identificacion" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Registrar Zona de Riesgo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('./layout/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>