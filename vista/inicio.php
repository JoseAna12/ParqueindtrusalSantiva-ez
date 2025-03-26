<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    header('Location: login/login.php');
    exit;
}

require_once 'layout/topbar.php';
require_once 'layout/sidebar.php';

// Obtener la fecha y hora actual en formato adecuado
$fecha_hora_actual = date('H:i d/m/Y');
$fecha_actual = date('d/m/Y');
$hora_actual = date('H:i');
$nombre_usuario = $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];
?>

<!-- Inicio del contenido principal -->
<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="text-primary fw-bold"><i class="fas fa-clipboard-check me-2"></i>ASISTENCIA DE EMPLEADOS</h4>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex flex-column align-items-end">
                        <span class="text-muted small">Usuario actual: <span class="fw-bold text-success"><?php echo $nombre_usuario; ?></span></span>
                        <span class="badge bg-info text-white rounded-pill px-3 py-2 mt-1">
                            <i class="far fa-calendar-alt me-1"></i> <?php echo $fecha_actual; ?> 
                            <i class="far fa-clock ms-2 me-1"></i> <?php echo $hora_actual; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controles y filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="filtroFecha">
                        <label for="filtroFecha">Filtrar por fecha</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select" id="filtroCargo">
                            <option value="">Todos</option>
                            <!-- Se llenarán dinámicamente -->
                        </select>
                        <label for="filtroCargo">Filtrar por cargo</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button id="btnFiltrar" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Aplicar filtros
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button id="btnExportar" class="btn btn-success w-100">
                        <i class="fas fa-file-excel me-2"></i>Exportar
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button id="btnImprimir" class="btn btn-secondary w-100">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de asistencias -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="tablaAsistencias">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col"><i class="fas fa-user me-2"></i>EMPLEADO</th>
                            <th scope="col"><i class="fas fa-id-card me-2"></i>DNI</th>
                            <th scope="col"><i class="fas fa-briefcase me-2"></i>CARGO</th>
                            <th scope="col"><i class="fas fa-sign-in-alt me-2"></i>ENTRADA</th>
                            <th scope="col"><i class="fas fa-sign-out-alt me-2"></i>SALIDA</th>
                            <th scope="col"><i class="fas fa-stopwatch me-2"></i>DURACIÓN</th>
                            <th scope="col"><i class="fas fa-cog me-2"></i>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../modelo/conexion.php";
                        
                        // Consulta SQL para obtener las asistencias
                        $sql = "SELECT
                                    asistencia.id_asistencia,
                                    asistencia.id_empleado,
                                    asistencia.entrada,
                                    asistencia.salida,
                                    empleado.nombre,
                                    empleado.apellido,
                                    empleado.dni,
                                    cargo.nombre AS cargo_nombre
                                FROM asistencia
                                INNER JOIN empleado ON asistencia.id_empleado = empleado.id_empleado
                                INNER JOIN cargo ON empleado.cargo_id = cargo.id_cargo
                                ORDER BY asistencia.entrada DESC";
                        
                        $result = $conexion->query($sql);

                        // Obtener todos los cargos para el filtro
                        $sqlCargos = "SELECT DISTINCT id_cargo, nombre FROM cargo ORDER BY nombre";
                        $resultCargos = $conexion->query($sqlCargos);
                        $cargos = [];
                        if ($resultCargos && $resultCargos->num_rows > 0) {
                            while ($rowCargo = $resultCargos->fetch_assoc()) {
                                $cargos[] = $rowCargo;
                            }
                        }

                        // Mostrar los resultados en la tabla
                        if ($result && $result->num_rows > 0) {
                            while ($fila = $result->fetch_assoc()) {
                                // Calcular duración si hay entrada y salida
                                $duracion = "";
                                if (!empty($fila['entrada']) && !empty($fila['salida'])) {
                                    $entrada = strtotime($fila['entrada']);
                                    $salida = strtotime($fila['salida']);
                                    $diferencia = $salida - $entrada;
                                    $horas = floor($diferencia / 3600);
                                    $minutos = floor(($diferencia % 3600) / 60);
                                    $duracion = sprintf("%02d:%02d", $horas, $minutos);
                                }

                                // Formatear fechas si existen
                                $entrada_formateada = !empty($fila['entrada']) ? $fila['entrada'] : $fecha_hora_actual;
                                $salida_formateada = !empty($fila['salida']) ? $fila['salida'] : '';

                                echo "<tr>";
                                echo "<td class='text-center fw-bold'>" . $fila['id_asistencia'] . "</td>";
                                echo "<td><div class='fw-bold'>" . $fila['nombre'] . " " . $fila['apellido'] . "</div></td>";
                                echo "<td>" . $fila['dni'] . "</td>";
                                echo "<td><span class='badge bg-secondary rounded-pill'>" . $fila['cargo_nombre'] . "</span></td>";
                                
                                // Mostrar entrada con estilo
                                echo "<td><span class='text-success fw-bold'>" . $entrada_formateada . "</span></td>";
                                
                                // Mostrar salida con estilo o botón si está vacía
                                if (empty($fila['salida'])) {
                                    echo "<td><button class='btn btn-sm btn-outline-danger registrar-salida' data-id='" . $fila['id_asistencia'] . "'>Registrar salida</button></td>";
                                } else {
                                    echo "<td><span class='text-danger fw-bold'>" . $salida_formateada . "</span></td>";
                                }
                                
                                // Mostrar duración
                                echo "<td>" . ($duracion ? "<span class='badge bg-info text-white'>" . $duracion . "</span>" : "<span class='badge bg-warning text-dark'>En progreso</span>") . "</td>";
                                
                                // Botones de acción
                                echo "<td>
                                        <div class='btn-group btn-group-sm'>
                                            <button class='btn btn-outline-primary btn-sm ver-detalles' data-id='" . $fila['id_asistencia'] . "'><i class='fas fa-eye'></i></button>
                                            <button class='btn btn-outline-warning btn-sm editar' data-id='" . $fila['id_asistencia'] . "'><i class='fas fa-edit'></i></button>
                                        </div>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center py-4'><i class='fas fa-info-circle me-2 text-info'></i>No se encontraron registros de asistencia</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles de asistencia -->
<div class="modal fade" id="detallesModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detalles de Asistencia</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="detallesContenido">
          <!-- Se llena dinámicamente -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php require('./layout/footer.php'); ?>

<!-- Incluye Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Incluye FontAwesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- Incluye jQuery y DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Llenar selector de cargos
        <?php foreach($cargos as $cargo): ?>
            $('#filtroCargo').append(new Option('<?php echo $cargo['nombre']; ?>', '<?php echo $cargo['id_cargo']; ?>'));
        <?php endforeach; ?>

        // Configurar DataTable
        var tabla = $('#tablaAsistencias').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }
            ],
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "<i class='fas fa-search'></i> Buscar:",
                "paginate": {
                    "next": "<i class='fas fa-chevron-right'></i>",
                    "previous": "<i class='fas fa-chevron-left'></i>"
                }
            },
            "order": [[0, "desc"]]
        });

        // Conectar botones de exportación
        $('#btnExportar').on('click', function() {
            $('.buttons-excel').click();
        });

        $('#btnImprimir').on('click', function() {
            $('.buttons-print').click();
        });

        // Implementar filtros personalizados
        $('#btnFiltrar').on('click', function() {
            var fechaFiltro = $('#filtroFecha').val();
            var cargoFiltro = $('#filtroCargo').val();
            
            tabla.columns(4).search(fechaFiltro).draw();
            tabla.columns(3).search(cargoFiltro).draw();
        });

        // Manejar registro de salida
        $(document).on('click', '.registrar-salida', function() {
            var idAsistencia = $(this).data('id');
            if (confirm('¿Confirmar registro de salida?')) {
                // Aquí irá el código AJAX para registrar la salida
                $.ajax({
                    url: 'procesar_salida.php', // Crea este archivo para procesar la salida
                    type: 'POST',
                    data: {
                        id_asistencia: idAsistencia,
                        hora_salida: '<?php echo $fecha_hora_actual; ?>'
                    },
                    success: function(response) {
                        alert('Salida registrada correctamente');
                        location.reload();
                    },
                    error: function() {
                        alert('Error al registrar la salida');
                    }
                });
            }
        });

        // Manejar ver detalles
        $(document).on('click', '.ver-detalles', function() {
            var idAsistencia = $(this).data('id');
            
            // Aquí simularemos cargar los detalles
            // En producción, deberías hacer una llamada AJAX para obtener los datos
            $('#detallesContenido').html(`
                <div class="d-flex justify-content-center mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            `);
            
            $('#detallesModal').modal('show');
            
            // Simulamos una carga asíncrona
            setTimeout(function() {
                // Aquí deberías tener datos reales de una llamada AJAX
                $('#detallesContenido').html(`
                    <div class="card border-0 mb-3">
                        <div class="card-body p-0">
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">ID de Asistencia</h6>
                                <p class="fw-bold">${idAsistencia}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Empleado</h6>
                                <p class="fw-bold">Nombre del Empleado</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted mb-1">Entrada</h6>
                                    <p class="text-success fw-bold">12:30 19/02/2025</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted mb-1">Salida</h6>
                                    <p class="text-danger fw-bold">18:45 19/02/2025</p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Tiempo Total</h6>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div>
                                </div>
                                <p>6 horas 15 minutos</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Estado</h6>
                                <span class="badge bg-success">Completo</span>
                            </div>
                        </div>
                    </div>
                `);
            }, 1000);
        });
    });
</script>