<?php
session_start();

// Verificar si el usuario está autenticado
if (empty($_SESSION['nombre']) && empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit();
}

// Incluir el archivo de conexión
include "../modelo/conexion.php";

// Inicializar la variable $sql
$sql = null;

// Verificar que la conexión existe y es válida
if (isset($conexion) && $conexion instanceof mysqli) {
    try {
        // Realizar la consulta
        $sql = $conexion->query("SELECT * FROM usuario");
        
        // Verificar si hay error en la consulta
        if (!$sql) {
            throw new Exception("Error en la consulta: " . $conexion->error);
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        $sql = null;
    }
} else {
    echo "<div class='alert alert-danger'>Error: No se pudo establecer la conexión con la base de datos</div>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --accent-color: #dbeafe;
            --danger-color: #dc2626;
            --warning-color: #f59e0b;
        }

        /* Estilos para el sidebar y menú */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #333;
            z-index: 1060 !important;
            transition: all 0.3s ease;
        }

        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .page-content {
            padding: 2rem;
            background-color: #f8fafc;
            min-height: calc(100vh - 60px);
            position: relative;
            z-index: 1;
        }

        /* Estilos para la tabla y cards */
        .card {
            position: relative;
            z-index: 1;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            background-color: white;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .page-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
        }

        /* Estilos para la tabla */
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }

        .table thead {
            background-color: var(--accent-color);
        }

        .table thead th {
            border: none;
            color: var(--primary-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            padding: 1rem;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Estilos para botones */
        .btn-register {
            background-color: var(--primary-color);
            color: white;
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            color: white;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background-color: var(--warning-color);
            color: white;
        }

        .btn-edit:hover {
            background-color: #d97706;
            color: white;
        }

        .btn-delete {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-delete:hover {
            background-color: #b91c1c;
            color: white;
        }

        /* Estilos adicionales */
        .user-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            background-color: var(--accent-color);
            color: var(--primary-color);
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        .actions-column {
            width: 200px;
        }

        /* Aseguramos que DataTables no interfiera */
        .dataTables_wrapper {
            position: relative;
            z-index: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .page-content {
                padding: 1rem;
            }
        }
        </style>
</head>
<body>
    <?php require('./layout/topbar.php'); ?>
    <?php require('./layout/sidebar.php'); ?>

    <div class="main-content">
        <div class="page-content">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="page-title m-0">Gestión de Usuarios</h4>
                    <a href="registro_usuario.php" class="btn btn-register">
                        <i class="fa-solid fa-plus me-2"></i>Nuevo Usuario
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Usuario</th>
                                <th class="actions-column">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($sql && $sql->num_rows > 0) {
                                while ($datos = $sql->fetch_assoc()) { ?>
                                    <tr id="fila-<?= htmlspecialchars($datos['id_usuario']) ?>">
                                        <td>
                                            <span class="user-badge">#<?= htmlspecialchars($datos['id_usuario']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($datos['nombre']) ?></td>
                                        <td><?= htmlspecialchars($datos['apellido']) ?></td>
                                        <td><?= htmlspecialchars($datos['usuario']) ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="http://localhost/sis_asistencia/vista/registro_usuario.php?id=<?= urlencode($datos['id_usuario']) ?>" 
                                                   class="btn btn-action btn-edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                    Editar
                                                </a>
                                                <button class="btn btn-action btn-delete"
                                                        onclick="confirmarEliminacion(<?= htmlspecialchars(json_encode($datos['id_usuario'])) ?>)">
                                                    <i class="fa-solid fa-trash"></i>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <i class="fa-solid fa-users-slash fa-2x mb-3 d-block"></i>
                                        <p class="m-0">No hay usuarios registrados</p>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php require('./layout/footer.php'); ?>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->

    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            let table = $('#usersTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 10,
                responsive: true,
                order: [[0, 'desc']], // Ordenar por ID de forma descendente
                columnDefs: [
                    { orderable: false, targets: 4 } // Desactivar ordenamiento en columna de acciones
                ]
            });

            // Toggle sidebar en móvil
            $('.sidebar-toggle').on('click', function() {
                $('.sidebar').toggleClass('active');
            });
        });

        // Función para confirmar eliminación con SweetAlert2 y AJAX
        function confirmarEliminacion(userId) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "No podrás revertir esta acción",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "No, cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'eliminar_usuario.php',
                        type: 'POST',
                        data: { id: userId },
                        success: function(response) {
                            let res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire("Eliminado", "El usuario ha sido eliminado", "success");
                                $("#fila-" + userId).fadeOut(500, function () {
                                    $(this).remove();
                                });
                            } else {
                                Swal.fire("Error", "No se pudo eliminar el usuario", "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error", "No se pudo conectar con el servidor", "error");
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>
