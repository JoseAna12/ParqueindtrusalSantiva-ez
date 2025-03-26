<?php 
session_start(); 

if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) { 
    header('Location: login/login.php'); 
    exit; 
} 

require_once 'layout/topbar.php'; 
require_once 'layout/sidebar.php'; 
require_once '../modelo/conexion.php'; 
?>

<!-- Contenido principal -->
<div class="main-container">
    <div class="page-content">
        <div class="header-section">
            <h2 class="main-title">Sistema de Mantenimiento de Plantas</h2>
            <p class="subtitle">Gestión y seguimiento de mantenimiento de plantas</p>
        </div>

        <!-- Formulario de mantenimiento - Botones -->
        <div class="card-header">
            <h4>Registro de Mantenimiento - Parque Industrial Santiváñez</h4>
            <div class="header-actions">
                <button class="btn btn-success btn-sm" onclick="generateMaintenancePDF()">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </button>
                <button class="btn btn-primary btn-sm" onclick="exportMaintenanceToExcel()">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </button>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="../controlador/controlador_registrar_reporte.php" class="maintenance-form" id="maintenanceForm">
                <div class="form-row">
                    <div class="form-group half-width">
                        <label for="nombre_planta">Nombre de la Planta:<span class="required">*</span></label>
                        <input type="text" name="nombre_planta" id="nombre_planta" class="form-control" required aria-required="true">
                    </div>
                    <div class="form-group half-width">
                        <label for="ubicacion">Ubicación:<span class="required">*</span></label>
                        <input type="text" name="ubicacion" id="ubicacion" class="form-control" required aria-required="true">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group quarter-width">
                        <label for="codigo">Código de Planta:<span class="required">*</span></label>
                        <input type="text" name="codigo" id="codigo" class="form-control" required aria-required="true">
                    </div>
                    <div class="form-group quarter-width">
                        <label for="fecha_mantenimiento">Fecha:<span class="required">*</span></label>
                        <input type="date" name="fecha_mantenimiento" id="fecha_mantenimiento" class="form-control" required aria-required="true" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group quarter-width">
                        <label for="tipo_mantenimiento">Tipo de Mantenimiento:</label>
                        <select name="tipo_mantenimiento" id="tipo_mantenimiento" class="form-control">
                            <option value="Lubricación">Lubricación</option>
                            <option value="Calibración">Calibración</option>
                            <option value="Reemplazo">Reemplazo de Piezas</option>
                            <option value="Limpieza">Limpieza Técnica</option>
                            <option value="Inspección">Inspección</option>
                            <option value="Reparación">Reparación</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group quarter-width">
                        <label for="responsable">Responsable:<span class="required">*</span></label>
                        <input type="text" name="responsable" id="responsable" class="form-control" required aria-required="true">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="descripcion_actividad">Descripción de la Actividad:<span class="required">*</span></label>
                        <textarea name="descripcion_actividad" id="descripcion_actividad" class="form-control Maintenance-textarea" rows="3" required aria-required="true"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half-width">
                        <label for="materiales">Materiales Utilizados:</label>
                        <textarea name="materiales" id="materiales" class="form-control Maintenance-textarea" rows="2"></textarea>
                    </div>
                    <div class="form-group half-width">
                        <label for="observaciones">Observaciones:</label>
                        <textarea name="observaciones" id="observaciones" class="form-control Maintenance-textarea" rows="2"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group quarter-width">
                        <label for="estado_salud">Estado de Salud:</label>
                        <select name="estado_salud" id="estado_salud" class="form-control">
                            <option value="Excelente">Excelente</option>
                            <option value="Bueno">Bueno</option>
                            <option value="Regular">Regular</option>
                            <option value="Malo">Malo</option>
                            <option value="Crítico">Crítico</option>
                        </select>
                    </div>
                    <div class="form-group quarter-width">
                        <label for="fecha_proximo">Próximo Mantenimiento:</label>
                        <input type="date" name="fecha_proximo" id="fecha_proximo" class="form-control">
                    </div>
                    <div class="form-group half-width">
                        <label for="firma">Firma del Responsable:</label>
                        <input type="text" name="firma" id="firma" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-gradient mt-4">
                    <i class="fas fa-save"></i> Registrar Mantenimiento
                </button>
            </form>
        </div>
    </div>
</div>
<!-- Historial de Mantenimiento -->
<div class="card">
    <div class="card-header">
        <h4>Historial de Mantenimientos</h4>
        <div class="header-actions">
            <button class="btn btn-success btn-sm" onclick="generateMaintenanceHistoryPDF()">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
            <button class="btn btn-primary btn-sm" onclick="exportMaintenanceHistoryToExcel()">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="maintenanceTable">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Código</th>
                        <th>Nombre de Planta</th>
                        <th>Tipo</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta a la base de datos
                    $sql = "SELECT * FROM mantenimientos ORDER BY fecha_mantenimiento DESC";
                    $result = $conexion->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["fecha_mantenimiento"] . "</td>";
                            echo "<td>" . $row["codigo"] . "</td>";
                            echo "<td>" . $row["nombre_planta"] . "</td>";
                            echo "<td>" . $row["tipo_mantenimiento"] . "</td>";
                            echo "<td>" . $row["responsable"] . "</td>";
                            echo "<td><span class='badge badge-" . 
                                 ($row["estado_salud"] == 'Excelente' ? 'success' : 
                                 ($row["estado_salud"] == 'Bueno' ? 'info' : 
                                 ($row["estado_salud"] == 'Regular' ? 'warning' : 
                                 ($row["estado_salud"] == 'Malo' ? 'danger' : 'secondary')))) . 
                                 "'>" . $row["estado_salud"] . "</span></td>";
                            echo "<td class='table-actions'>
                                    <a href='ver_mantenimiento.php?id=" . $row["id"] . "' class='action-icon view'><i class='fas fa-eye'></i></a>
                                    <a href='editar_mantenimiento.php?id=" . $row["id"] . "' class='action-icon edit'><i class='fas fa-edit'></i></a>
                                    <a href='#' onclick='confirmarEliminar(" . $row["id"] . ")' class='action-icon delete'><i class='fas fa-trash'></i></a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No hay registros de mantenimiento</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


        <!-- RÉPLICA EXACTA DE LA HOJA DE RUTA -->
<div class="container">
    <div class="header-section">
        <h2 class="main-title">Directorio Mixto Parque Industrial Santiváñez</h2>
        <p class="subtitle">Hoja de Ruta</p>
    </div>

    <!-- Hoja de Ruta - Botones -->
    <div class="card-header">
        <h4>Hoja de Ruta - Parque Industrial Santiváñez</h4>
        <div class="header-actions">
            <button class="btn btn-success btn-sm" onclick="generateRoutingPDF()">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
            <button class="btn btn-primary btn-sm" onclick="exportRoutingToExcel()">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </button>
            <button class="btn btn-secondary btn-sm" onclick="printRoutingForm()">
                <i class="fas fa-print"></i> Imprimir
            </button>
            <button class="btn btn-info btn-sm" onclick="sendEmail()">
                <i class="fas fa-envelope"></i> Enviar por Outlook
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- Formulario de Hoja de Ruta según las imágenes proporcionadas -->
        <form method="POST" action="../controlador/controlador_registrar_hoja_ruta.php" class="routing-form" id="routingForm">
            <div class="document-header">
                <div class="document-title">
                    <h3>DIRECTORIO MIXTO PARQUE INDUSTRIAL SANTIVÁÑEZ</h3>
                    <div class="routing-title">HOJA DE RUTA</div>
                </div>
                <div class="company-logo">
                    <img src="../public/img-inicio/ari.png" alt="Logo" style="max-width: 60px; max-height: 60px;">
                </div>
            </div>

            <!-- Campo REFERENCIA -->
            <div class="field-row">
                <div class="field-label">REFERENCIA:</div>
                <div class="field-value">
                    <input type="text" name="referencia" id="referencia" class="form-control" style="width:100%;">
                </div>
            </div>

            <!-- Campo PROCEDENCIA -->
            <div class="field-row">
                <div class="field-label">PROCEDENCIA:</div>
                <div class="field-value" style="display:flex; justify-content:space-between;">
                    <input type="text" name="procedencia" id="procedencia" class="form-control" style="width:80%;">
                    <div style="display:flex; align-items:center;">
                        <span style="margin-right:5px;">Int.:</span>
                        <input type="text" name="int" id="int" class="form-control" style="width:80px;">
                    </div>
                </div>
            </div>

            <!-- Fila N° DE REGISTRO y FECHA -->
            <div class="field-row">
                <div style="display:flex; width:100%;">
                    <div style="display:flex; align-items:center; margin-right:20px;">
                        <div class="field-label" style="width:auto; margin-right:10px;">N° DE REGISTRO:</div>
                        <input type="text" name="num_registro" id="num_registro" class="form-control" style="width:100px;">
                    </div>
                    
                    <div style="display:flex; align-items:center; margin-right:20px;">
                        <div class="field-label" style="width:auto; margin-right:10px;">FECHA:</div>
                        <div style="display:flex;">
                            <input type="text" name="fecha_dia" id="fecha_dia" placeholder="DÍA" class="form-control" style="width:60px; margin-right:5px;">
                            <input type="text" name="fecha_mes" id="fecha_mes" placeholder="MES" class="form-control" style="width:60px; margin-right:5px;">
                            <input type="text" name="fecha_anio" id="fecha_anio" placeholder="AÑO" class="form-control" style="width:80px;">
                        </div>
                    </div>
                    
                    <div style="display:flex; align-items:center; margin-left:auto;">
                        <span style="margin-right:5px;">Ext.:</span>
                        <input type="text" name="ext" id="ext" class="form-control" style="width:80px;">
                    </div>
                </div>
            </div>

            <!-- Bloques de destinatarios para el formulario normal -->
<div id="recipientBlocks" style="border: 1px solid #000;">
    <!-- Primer destinatario -->
    <div style="border-bottom: 1px solid #000;">
        <div style="display: flex;">
            <div style="width: 65%; padding: 5px;">
                <div style="display: flex; margin-bottom: 5px;">
                    <div style="font-weight: bold; width: 120px;">DESTINATARIO:</div>
                    <input type="text" name="destinatario1" id="destinatario1" class="form-control" style="flex: 1;">
                </div>
                <div style="margin-bottom: 10px;">
                    <div style="font-weight: bold;">ASUNTO:</div>
                    <textarea name="asunto1" id="asunto1" class="form-control" rows="1" style="width:100%;"></textarea>
                </div>
                <div style="display: flex;">
                    <div style="width: 50%; border: 1px solid #000; border-radius: 15px; padding: 5px; margin-right: 10px;">
                        <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">DERIVADO POR:</div>
                        <div style="height: 70px;"></div>
                        <div style="text-align: center; font-weight: bold;">FIRMA Y SELLO</div>
                    </div>
                    <div style="width: 45%;">
                        <div style="margin-bottom: 5px;">
                            <span style="font-weight: bold; margin-right: 5px;">FECHA:</span>
                            <span style="font-weight: bold; margin-left: 20px;">HORA</span>
                            <input type="text" name="derivacion1_hora" class="form-control" style="width: 60px; margin-left: 5px;">
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                            <div style="text-align: center; width: 30%;">
                                <input type="text" name="derivacion1_dia" class="form-control" style="width:100%;">
                                <div style="font-weight: bold; font-size: 12px;">DIA</div>
                            </div>
                            <div style="text-align: center; width: 30%;">
                                <input type="text" name="derivacion1_mes" class="form-control" style="width:100%;">
                                <div style="font-weight: bold; font-size: 12px;">MES</div>
                            </div>
                            <div style="text-align: center; width: 30%;">
                                <input type="text" name="derivacion1_anio" class="form-control" style="width:100%;">
                                <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width: 35%; border-left: 1px solid #000; padding: 5px;">
                <div style="text-align: center; font-weight: bold;">FECHA DE RECEPCION:</div>
                <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion1_hora" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">HORA</div>
                    </div>
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion1_dia" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">DIA</div>
                    </div>
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion1_mes" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">MES</div>
                    </div>
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion1_anio" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">FIRMAR Y SELLO:</div>
                    <div style="border: 1px solid #000; border-radius: 15px; height: 90px;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Segundo destinatario -->
    <div style="border-bottom: 1px solid #000;">
        <div style="display: flex;">
            <div style="width: 65%; padding: 5px;">
                <div style="display: flex; margin-bottom: 5px;">
                    <div style="font-weight: bold; width: 120px;">DESTINATARIO:</div>
                    <input type="text" name="destinatario2" id="destinatario2" class="form-control" style="flex: 1;">
                </div>
                <div style="margin-bottom: 10px;">
                    <div style="font-weight: bold;">ASUNTO:</div>
                    <textarea name="asunto2" id="asunto2" class="form-control" rows="1" style="width:100%;"></textarea>
                </div>
                <div style="display: flex;">
                    <div style="width: 50%; border: 1px solid #000; border-radius: 15px; padding: 5px; margin-right: 10px;">
                        <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">DERIVADO POR:</div>
                        <div style="height: 70px;"></div>
                        <div style="text-align: center; font-weight: bold;">FIRMA Y SELLO</div>
                    </div>
                    <div style="width: 45%;">
                        <div style="margin-bottom: 5px;">
                            <span style="font-weight: bold; margin-right: 5px;">FECHA:</span>
                            <span style="font-weight: bold; margin-left: 20px;">HORA</span>
                            <input type="text" name="derivacion2_hora" class="form-control" style="width: 60px; margin-left: 5px;">
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                            <div style="text-align: center; width: 30%;">
                                <input type="text" name="derivacion2_dia" class="form-control" style="width:100%;">
                                <div style="font-weight: bold; font-size: 12px;">DIA</div>
                            </div>
                            <div style="text-align: center; width: 30%;">
                                <input type="text" name="derivacion2_mes" class="form-control" style="width:100%;">
                                <div style="font-weight: bold; font-size: 12px;">MES</div>
                            </div>
                            <div style="text-align: center; width: 30%;">
                                <input type="text" name="derivacion2_anio" class="form-control" style="width:100%;">
                                <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width: 35%; border-left: 1px solid #000; padding: 5px;">
                <div style="text-align: center; font-weight: bold;">FECHA DE RECEPCION:</div>
                <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion2_hora" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">HORA</div>
                    </div>
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion2_dia" class="form-control" style="width:100%;">
                        <div style="font-weight: bold ; font-size: 12px;">DIA</div>
                    </div>
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion2_mes" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">MES</div>
                    </div>
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion2_anio" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">FIRMAR Y SELLO:</div>
                    <div style="border: 1px solid #000; border-radius: 15px; height: 90px;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tercer destinatario -->
    <div style="border-bottom: 1px solid #000;">
        <div style="display: flex;">
            <div style="width: 65%; padding: 5px;">
                <div style="display: flex; margin-bottom: 5px;">
                    <div style="font-weight: bold; width: 120px;">DESTINATARIO:</div>
                    <input type="text" name="destinatario3" id="destinatario3" class="form-control" style="flex: 1;">
                </div>
                <div style="margin-bottom: 10px;">
                    <div style="font-weight: bold;">ASUNTO:</div>
                    <textarea name="asunto3" id="asunto3" class="form-control" rows="1" style="width:100%;"></textarea>
                </div>
                <div style="display: flex;">
                    <div style="width: 50%; border: 1px solid #000; border-radius: 15px; padding: 5px; margin-right: 10px;">
                        <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">DERIVADO POR:</div>
                        <div style="height: 70px;"></div>
                        <div style="text-align: center; font-weight: bold;">FIRMA Y SELLO</div>
                    </div>
                    <div style="width: 45%;">
                        <div style="margin-bottom: 5px;">
                            <span style="font-weight: bold; margin-right: 5px;">FECHA:</span>
                            <span style="font-weight: bold; margin-left: 20px;">HORA</span>
                            <input type="text" name="derivacion3_hora" class="form-control" style="width: 60px; margin-left: 5px;">
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                            <div style="text-align: center; width: 30%;">
                                <input type="text" name="derivacion3_dia" class="form-control" style="width:100%;">
                                <div style="font-weight: bold; font-size: 12px;">DIA</div>
                            </div>
                            <div style="text-align: center; width: 30%;">
                                <input type="text" name="derivacion3_mes" class="form-control" style="width:100%;">
                                <div style="font-weight: bold; font-size: 12px;">MES</div>
                            </div>
                            <div style="text-align: center; width: 30%;">
                                <input type="text" name="derivacion3_anio" class="form-control" style="width:100%;">
                                <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width: 35%; border-left: 1px solid #000; padding: 5px;">
                <div style="text-align: center; font-weight: bold;">FECHA DE RECEPCION:</div>
                <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion3_hora" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px ;">HORA</div>
                    </div>
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion3_dia" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">DIA</div>
                    </div>
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion3_mes" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">MES</div>
                    </div>
                    <div style="text-align: center; width: 22%;">
                        <input type="text" name="recepcion3_anio" class="form-control" style="width:100%;">
                        <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">FIRMAR Y SELLO:</div>
                    <div style="border: 1px solid #000; border-radius: 15px; height: 90px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
                
                <!-- Cuarto destinatario -->
<div style="border-bottom: 1px solid #000;">
    <div style="display: flex;">
        <div style="width: 65%; padding: 5px;">
            <div style="display: flex; margin-bottom: 5px;">
                <div style="font-weight: bold; width: 120px;">DESTINATARIO:</div>
                <input type="text" name="destinatario4" id="destinatario4" class="form-control" style="flex: 1;">
            </div>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">ASUNTO:</div>
                <textarea name="asunto4" id="asunto4" class="form-control" rows="1" style="width:100%;"></textarea>
            </div>
            <div style="display: flex;">
                <div style="width: 50%; border: 1px solid #000; border-radius: 15px; padding: 5px; margin-right: 10px;">
                    <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">DERIVADO POR:</div>
                    <div style="height: 70px;"></div>
                    <div style="text-align: center; font-weight: bold;">FIRMA Y SELLO</div>
                </div>
                <div style="width: 45%;">
                    <div style="margin-bottom: 5px;">
                        <span style="font-weight: bold; margin-right: 5px;">FECHA:</span>
                        <span style="font-weight: bold; margin-left: 20px;">HORA</span>
                        <input type="text" name="derivacion4_hora" class="form-control" style="width: 60px; margin-left: 5px;">
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_dia" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">DIA</div>
                        </div>
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_mes" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">MES</div>
                        </div>
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_anio" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 35%; border-left: 1px solid #000; padding: 5px;">
            <div style="text-align: center; font-weight: bold;">FECHA DE RECEPCION:</div>
            <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_hora" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">HORA</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_dia" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">DIA</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_mes" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">MES</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_anio" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">FIRMAR Y SELLO:</div>
                <div style="border: 1px solid #000; border-radius: 15px; height: 90px;"></div>
            </div>
        </div>
    </div>
</div>


<!-- quinto destinatario -->
<div style="border-bottom: 1px solid #000;">
    <div style="display: flex;">
        <div style="width: 65%; padding: 5px;">
            <div style="display: flex; margin-bottom: 5px;">
                <div style="font-weight: bold; width: 120px;">DESTINATARIO:</div>
                <input type="text" name="destinatario4" id="destinatario4" class="form-control" style="flex: 1;">
            </div>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">ASUNTO:</div>
                <textarea name="asunto4" id="asunto4" class="form-control" rows="1" style="width:100%;"></textarea>
            </div>
            <div style="display: flex;">
                <div style="width: 50%; border: 1px solid #000; border-radius: 15px; padding: 5px; margin-right: 10px;">
                    <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">DERIVADO POR:</div>
                    <div style="height: 70px;"></div>
                    <div style="text-align: center; font-weight: bold;">FIRMA Y SELLO</div>
                </div>
                <div style="width: 45%;">
                    <div style="margin-bottom: 5px;">
                        <span style="font-weight: bold; margin-right: 5px;">FECHA:</span>
                        <span style="font-weight: bold; margin-left: 20px;">HORA</span>
                        <input type="text" name="derivacion4_hora" class="form-control" style="width: 60px; margin-left: 5px;">
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_dia" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">DIA</div>
                        </div>
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_mes" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">MES</div>
                        </div>
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_anio" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 35%; border-left: 1px solid #000; padding: 5px;">
            <div style="text-align: center; font-weight: bold;">FECHA DE RECEPCION:</div>
            <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_hora" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">HORA</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_dia" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">DIA</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_mes" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">MES</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_anio" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">FIRMAR Y SELLO:</div>
                <div style="border: 1px solid #000; border-radius: 15px; height: 90px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- sexto destinatario -->
<div style="border-bottom: 1px solid #000;">
    <div style="display: flex;">
        <div style="width: 65%; padding: 5px;">
            <div style="display: flex; margin-bottom: 5px;">
                <div style="font-weight: bold; width: 120px;">DESTINATARIO:</div>
                <input type="text" name="destinatario4" id="destinatario4" class="form-control" style="flex: 1;">
            </div>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">ASUNTO:</div>
                <textarea name="asunto4" id="asunto4" class="form-control" rows="1" style="width:100%;"></textarea>
            </div>
            <div style="display: flex;">
                <div style="width: 50%; border: 1px solid #000; border-radius: 15px; padding: 5px; margin-right: 10px;">
                    <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">DERIVADO POR:</div>
                    <div style="height: 70px;"></div>
                    <div style="text-align: center; font-weight: bold;">FIRMA Y SELLO</div>
                </div>
                <div style="width: 45%;">
                    <div style="margin-bottom: 5px;">
                        <span style="font-weight: bold; margin-right: 5px;">FECHA:</span>
                        <span style="font-weight: bold; margin-left: 20px;">HORA</span>
                        <input type="text" name="derivacion4_hora" class="form-control" style="width: 60px; margin-left: 5px;">
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_dia" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">DIA</div>
                        </div>
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_mes" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">MES</div>
                        </div>
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_anio" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 35%; border-left: 1px solid #000; padding: 5px;">
            <div style="text-align: center; font-weight: bold;">FECHA DE RECEPCION:</div>
            <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_hora" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">HORA</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_dia" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">DIA</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_mes" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">MES</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_anio" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">FIRMAR Y SELLO:</div>
                <div style="border: 1px solid #000; border-radius: 15px; height: 90px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- septimo destinatario -->
<div style="border-bottom: 1px solid #000;">
    <div style="display: flex;">
        <div style="width: 65%; padding: 5px;">
            <div style="display: flex; margin-bottom: 5px;">
                <div style="font-weight: bold; width: 120px;">DESTINATARIO:</div>
                <input type="text" name="destinatario4" id="destinatario4" class="form-control" style="flex: 1;">
            </div>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">ASUNTO:</div>
                <textarea name="asunto4" id="asunto4" class="form-control" rows="1" style="width:100%;"></textarea>
            </div>
            <div style="display: flex;">
                <div style="width: 50%; border: 1px solid #000; border-radius: 15px; padding: 5px; margin-right: 10px;">
                    <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">DERIVADO POR:</div>
                    <div style="height: 70px;"></div>
                    <div style="text-align: center; font-weight: bold;">FIRMA Y SELLO</div>
                </div>
                <div style="width: 45%;">
                    <div style="margin-bottom: 5px;">
                        <span style="font-weight: bold; margin-right: 5px;">FECHA:</span>
                        <span style="font-weight: bold; margin-left: 20px;">HORA</span>
                        <input type="text" name="derivacion4_hora" class="form-control" style="width: 60px; margin-left: 5px;">
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_dia" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">DIA</div>
                        </div>
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_mes" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">MES</div>
                        </div>
                        <div style="text-align: center; width: 30%;">
                            <input type="text" name="derivacion4_anio" class="form-control" style="width:100%;">
                            <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 35%; border-left: 1px solid #000; padding: 5px;">
            <div style="text-align: center; font-weight: bold;">FECHA DE RECEPCION:</div>
            <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_hora" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">HORA</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_dia" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">DIA</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_mes" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">MES</div>
                </div>
                <div style="text-align: center; width: 22%;">
                    <input type="text" name="recepcion4_anio" class="form-control" style="width:100%;">
                    <div style="font-weight: bold; font-size: 12px;">AÑO</div>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">FIRMAR Y SELLO:</div>
                <div style="border: 1px solid #000; border-radius: 15px; height: 90px;"></div>
            </div>
        </div>
    </div>
</div>

            <!-- Botones del formulario -->
            <div class="form-actions">
                <button type="submit" class="btn btn-gradient mt-4">
                    <i class="fas fa-save"></i> Guardar hoja de ruta
                </button>
                <button type="reset" class="btn btn-secondary mt-4">
                    <i class="fas fa-undo"></i> Limpiar formulario
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para manejar la impresión -->
<script>
function printRoutingForm() {
    // Crear o acceder al iframe existente
    var iframe = document.getElementById('print-iframe');
    if (!iframe) {
        iframe = document.createElement('iframe');
        iframe.id = 'print-iframe';
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
    }
    
    // Obtener la fecha actual en formato DD/MM/YY, HH:MM
    var now = new Date();
    var day = String(now.getDate()).padStart(2, '0');
    var month = String(now.getMonth() + 1).padStart(2, '0');
    var year = String(now.getFullYear()).slice(2);
    var hours = String(now.getHours()).padStart(2, '0');
    var minutes = String(now.getMinutes()).padStart(2, '0');
    var formattedDate = day + '/' + month + '/' + year + ', ' + hours + ':' + minutes;
    
    // Establecer fechas en los elementos
    document.getElementById('fechaActual').textContent = formattedDate;
    document.getElementById('fechaActual2').textContent = formattedDate;
    
    // Mostrar la sección de impresión
    document.getElementById('printSection').style.display = 'block';
    
    // Ocultar temporalmente el resto del contenido
    const formOriginal = document.getElementById('routingForm').style.display;
    document.getElementById('routingForm').style.display = 'none';
    
    // Imprimir
    window.print();
    
    // Restaurar la visualización normal
    document.getElementById('printSection').style.display = 'none';
    document.getElementById('routingForm').style.display = formOriginal;
}
</script>

<!-- Script para el archivo JavaScript -->
<script src="../public/js/exportaciones.js"></script>

<?php require('./layout/footer.php'); ?>

<!-- Importar librerías necesarias -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
// Función para exportar a PDF el formulario de mantenimiento
function generateMaintenancePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Configuraciones de estilo avanzadas
    const pageWidth = doc.internal.pageSize.width;
    const pageHeight = doc.internal.pageSize.height;
    const margin = 15;
    const primaryColor = [41, 128, 185]; // Azul elegante
    const secondaryColor = [52, 152, 219]; // Azul más claro
    
    // Fondo decorativo
    doc.setFillColor(240, 248, 255); // Azul muy claro
    doc.rect(0, 0, pageWidth, pageHeight, 'F');
    
    // Encabezado con diseño profesional
    doc.setFillColor(...primaryColor);
    doc.rect(0, 0, pageWidth, 35, 'F');
    
    // Texto del encabezado en blanco
    doc.setTextColor(255, 255, 255);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(18);
    doc.text('Registro de Mantenimiento', pageWidth / 2, 22, { align: 'center' });
    doc.setFontSize(12);
    doc.text('Parque Industrial Santiváñez', pageWidth / 2, 30, { align: 'center' });
    
    // Restaurar color de texto
    doc.setTextColor(0, 0, 0);
    doc.setFont('helvetica', 'normal');
    
    // Recoger datos del formulario
    const formData = {
        'Nombre de la Planta': document.getElementById('nombre_planta').value || "[No especificado]",
        'Ubicación': document.getElementById('ubicacion').value || "[No especificado]",
        'Código': document.getElementById('codigo').value || "[No especificado]",
        'Fecha': document.getElementById('fecha_mantenimiento').value || "[No especificado]",
        'Tipo de Mantenimiento': document.getElementById('tipo_mantenimiento').value || "[No especificado]",
        'Responsable': document.getElementById('responsable').value || "[No especificado]",
        'Descripción de la Actividad': document.getElementById('descripcion_actividad').value || "[No especificado]",
        'Materiales Utilizados': document.getElementById('materiales').value || "[No especificado]",
        'Observaciones': document.getElementById('observaciones').value || "[No especificado]",
        'Estado de Salud': document.getElementById('estado_salud').value || "[No especificado]",
        'Próximo Mantenimiento': document.getElementById('fecha_proximo').value || "[No especificado]"
    };
    
    // Dibujar tabla de datos con diseño más elegante
    const startY = 50;
    const lineHeight = 10;
    let currentY = startY;
    
    // Función para agregar fila a la tabla
    function addTableRow(label, value, isAlternate = false) {
        // Fondo alternado
        doc.setFillColor(isAlternate ? 240 : 250, 240, 255);
        doc.rect(margin, currentY, pageWidth - 2*margin, lineHeight, 'F');
        
        // Línea de contorno suave
        doc.setDrawColor(200, 215, 235);
        doc.rect(margin, currentY, pageWidth - 2*margin, lineHeight);
        
        // Etiqueta en negrita con color de acento
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(...primaryColor);
        doc.text(label + ':', margin + 2, currentY + lineHeight - 2);
        
        // Valor en texto normal
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(0, 0, 0);
        doc.text(value, margin + 80, currentY + lineHeight - 2);
        
        currentY += lineHeight;
    }
    
    // Agregar filas de datos con fondo alternado
    Object.entries(formData).forEach(([label, value], index) => {
        addTableRow(label, value, index % 2 === 1);
    });
    
    // Líneas de firma con diseño profesional
    currentY += 20;
    doc.setLineWidth(0.5);
    doc.setDrawColor(...primaryColor);
    
    // Línea de firma 1
    doc.line(margin, currentY + 20, pageWidth / 2 - margin, currentY + 20);
    doc.setTextColor(...secondaryColor);
    doc.text('Firma del Responsable', margin, currentY + 30);
    
    // Línea de firma 2
    doc.line(pageWidth / 2 + margin, currentY + 20, pageWidth - margin, currentY + 20);
    doc.text('Firma de Supervisión', pageWidth / 2 + margin, currentY + 30);
    
    // Pie de página con estilo
    doc.setFontSize(10);
    doc.setTextColor(100, 100, 100);
    doc.text(`Fecha de emisión: ${new Date().toLocaleDateString()}`, 
        margin, pageHeight - margin);
    
    // Logotipo o marca de agua (opcional, comentado)
    // doc.setFontSize(50);
    // doc.setTextColor(200, 200, 200);
    // doc.text('SANTIVÁÑEZ', pageWidth/2, pageHeight/2, { align: 'center', angle: 45 });
    
    // Guardar el PDF
    doc.save('Registro_Mantenimiento.pdf');
}

// Opcional: Añadir un event listener para un botón de exportación
document.getElementById('export-pdf-btn')?.addEventListener('click', generateMaintenancePDF);
function exportMaintenanceToExcel() {
    // Crear un objeto de datos para Excel
    const data = [
        ['Registro de Mantenimiento - Parque Industrial Santiváñez'],
        [''],
        ['Información de Mantenimiento'],
        ['Nombre de la planta', document.getElementById('nombre_planta').value || '[No especificado]'],
        ['Ubicación', document.getElementById('ubicacion').value || '[No especificado]'],
        ['Código', document.getElementById('codigo').value || '[No especificado]'],
        ['Fecha', document.getElementById('fecha_mantenimiento').value || '[No especificado]'],
        ['Tipo de Mantenimiento', document.getElementById('tipo_mantenimiento').value || '[No especificado]'],
        ['Responsable', document.getElementById('responsable').value || '[No especificado]'],
        [''],
        ['Detalles de la Actividad'],
        ['Descripción de la Actividad', document.getElementById('descripcion_actividad').value || '[No especificado]'],
        ['Materiales utilizados', document.getElementById('materiales').value || '[No especificado]'],
        ['Observaciones', document.getElementById('observaciones').value || '[No especificado]'],
        [''],
        ['Información Adicional'],
        ['Estado de Salud', document.getElementById('estado_salud').value || '[No especificado]'],
        ['Próximo Mantenimiento', document.getElementById('fecha_proximo').value || '[No especificado]'],
        [''],
        ['Fecha de Generación del Informe', new Date().toLocaleString()]
    ];
    
    // Crear workbook y añadir worksheet
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(data);
    
    // Configurar anchos de columna
    ws['!cols'] = [
        { wch: 25 }, // Columna de etiquetas
        { wch: 40 }  // Columna de valores
    ];
    
    // Establecer estilos para las celdas
    const range = XLSX.utils.decode_range(ws['!ref']); // Obtener el rango de la hoja
    for (let row = range.s.r; row <= range.e.r; row++) {
        for (let col = range.s.c; col <= range.e.c; col++) {
            const cell = ws[XLSX.utils.encode_cell({ r: row, c: col })];
            if (cell) {
                // Definir estilos basados en el tipo de fila
                let cellStyle = {
                    fill: { fgColor: { rgb: "FFFFFF" } }, // Blanco por defecto
                    font: {
                        name: "Calibri",
                        sz: 11,
                        color: { rgb: "000000" }
                    },
                    alignment: {
                        horizontal: "left",
                        vertical: "center",
                        wrapText: true
                    },
                    border: {
                        bottom: { style: "thin", color: { rgb: "D3D3D3" } }
                    }
                };
                
                // Estilos especiales para filas específicas
                if (row === 0) { // Título principal
                    cellStyle.fill.fgColor.rgb = "4472C4"; // Azul oscuro
                    cellStyle.font.bold = true;
                    cellStyle.font.color.rgb = "FFFFFF"; // Texto blanco
                    cellStyle.font.sz = 14;
                } else if (row === 2 || row === 9 || row === 14) { // Encabezados de sección
                    cellStyle.fill.fgColor.rgb = "D9EAD3"; // Verde claro
                    cellStyle.font.bold = true;
                    cellStyle.font.sz = 12;
                } else if (row === range.e.r) { // Última fila (fecha de generación)
                    cellStyle.font.italic = true;
                    cellStyle.font.color.rgb = "808080"; // Gris
                }
                
                // Aplicar estilos a la celda
                cell.s = cellStyle;
            }
        }
    }

    // Añadir worksheet al workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Registro de Mantenimiento');
    
    // Escribir archivo y descargarlo
    XLSX.writeFile(wb, 'Registro_Mantenimiento.xlsx', { 
        bookType: 'xlsx', 
        bookSST: false, 
        type: 'binary' 
    });
}

// Opcional: Añadir un event listener para un botón de exportación
document.getElementById('export-excel-btn')?.addEventListener('click', exportMaintenanceToExcel);

function generateRoutingPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Configuraciones de estilo
    const pageWidth = doc.internal.pageSize.width;
    const primaryColor = [41, 128, 185]; // Azul elegante
    const secondaryColor = [52, 152, 219]; // Azul más claro
    
    // Fondo decorativo
    doc.setFillColor(240, 248, 255); // Azul muy claro
    doc.rect(0, 0, pageWidth, doc.internal.pageSize.height, 'F');
    
    // Encabezado con diseño profesional
    doc.setFillColor(...primaryColor);
    doc.rect(0, 0, pageWidth, 35, 'F');
    
    // Texto del encabezado en blanco
    doc.setTextColor(255, 255, 255);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Hoja de Ruta', pageWidth / 2, 22, { align: 'center' });
    doc.setFontSize(12);
    doc.text('Directorio Mixto Parque Industrial Santiváñez', pageWidth / 2, 30, { align: 'center' });
    
    // Restaurar color de texto
    doc.setTextColor(0, 0, 0);
    doc.setFont('helvetica', 'normal');
    
    // Recoger datos del formulario
    const formData = [
        { label: 'Referencia', value: document.getElementById('referencia').value || '[No especificado]' },
        { label: 'Procedencia', value: document.getElementById('procedencia').value || '[No especificado]' },
        { label: 'N° de Registro', value: document.getElementById('num_registro').value || '[No especificado]' },
        { 
            label: 'Fecha', 
            value: (() => {
                const dia = document.getElementById('fecha_dia').value || '';
                const mes = document.getElementById('fecha_mes').value || '';
                const anio = document.getElementById('fecha_anio').value || '';
                return (dia || mes || anio) ? `${dia}/${mes}/${anio}` : '[No especificado]';
            })()
        },
        { label: 'Destinatario', value: document.getElementById('destinatario1').value || '[No especificado]' }
    ];
    
    // Dibujar tabla de datos con diseño elegante
    const startY = 50;
    const lineHeight = 10;
    let currentY = startY;
    
    // Función para agregar fila a la tabla
    function addTableRow(label, value, isAlternate = false) {
        // Fondo alternado
        doc.setFillColor(isAlternate ? 240 : 250, 240, 255);
        doc.rect(15, currentY, pageWidth - 30, lineHeight, 'F');
        
        // Línea de contorno suave
        doc.setDrawColor(200, 215, 235);
        doc.rect(15, currentY, pageWidth - 30, lineHeight);
        
        // Etiqueta en negrita con color de acento
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(...primaryColor);
        doc.text(label + ':', 20, currentY + lineHeight - 2);
        
        // Valor en texto normal
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(0, 0, 0);
        doc.text(value, 100, currentY + lineHeight - 2);
        
        currentY += lineHeight;
    }
    
    // Agregar filas de datos con fondo alternado
    formData.forEach((item, index) => {
        addTableRow(item.label, item.value, index % 2 === 1);
    });
    
    // Sección de Asunto
    currentY += 10;
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(...primaryColor);
    doc.text('Asunto:', 15, currentY);
    
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(0, 0, 0);
    currentY += 10;
    
    // Obtener asunto y partir si es muy largo
    const asunto = document.getElementById('asunto1').value || '[No especificado]';
    const splitAsunto = doc.splitTextToSize(asunto, pageWidth - 30);
    doc.text(splitAsunto, 15, currentY);
    
    // Líneas de firma con diseño profesional
    currentY += splitAsunto.length * 7 + 20;
    doc.setLineWidth(0.5);
    doc.setDrawColor(...primaryColor);
    
    // Línea de firma 1
    doc.line(15, currentY + 20, pageWidth / 2 - 15, currentY + 20);
    doc.setTextColor(...secondaryColor);
    doc.text('Firma de Remitente', 15, currentY + 30);
    
    // Línea de firma 2
    doc.line(pageWidth / 2 + 15, currentY + 20, pageWidth - 15, currentY + 20);
    doc.text('Firma de Receptor', pageWidth / 2 + 15, currentY + 30);
    
    // Pie de página con estilo
    doc.setFontSize(10);
    doc.setTextColor(100, 100, 100);
    doc.text(`Fecha de emisión: ${new Date().toLocaleString()}`, 
        15, doc.internal.pageSize.height - 15);
    
    // Guardar el PDF
    doc.save('Hoja_de_Ruta.pdf');
}

// Opcional: Añadir un event listener para un botón de exportación
document.getElementById('export-routing-pdf-btn')?.addEventListener('click', generateRoutingPDF);

// Función para exportar a Excel la hoja de ruta
function exportRoutingToExcel() {
    // Crear un objeto de datos para Excel
    const data = [
        ['Hoja de Ruta - Directorio Mixto Parque Industrial Santiváñez'],
        [''],
        ['Referencia', document.getElementById('referencia').value || ''],
        ['Procedencia', document.getElementById('procedencia').value || ''],
        ['N° de Registro', document.getElementById('num_registro').value || ''],
        ['Fecha', `${document.getElementById('fecha_dia').value || ''} / ${document.getElementById('fecha_mes').value || ''} / ${document.getElementById('fecha_anio').value || ''}`],
        ['Destinatario', document.getElementById('destinatario1').value || ''],
        ['Asunto', document.getElementById('asunto1').value || '']
    ];
    
    // Crear workbook y añadir worksheet
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(data);
    
    // Establecer estilos para las celdas
    const range = XLSX.utils.decode_range(ws['!ref']); // Obtener el rango de la hoja
    for (let row = range.s.r; row <= range.e.r; row++) {
        for (let col = range.s.c; col <= range.e.c; col++) {
            const cell = ws[XLSX.utils.encode_cell({ r: row, c: col })];
            if (cell) {
                // Aplicar estilos
                cell.s = {
                    fill: {
                        fgColor: { rgb: "D9EAD3" } // Color de fondo verde claro
                    },
                    font: {
                        name: "Arial",
                        sz: 12,
                        bold: row === 0, // Negrita solo para la primera fila
                        color: { rgb: row === 0 ? "000000" : "000000" } // Color de texto negro
                    },
                    alignment: {
                        horizontal: "left" // Alinear texto a la izquierda
                    }
                };
            }
        }
    }

    // Añadir worksheet al workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Hoja_de_Ruta');
    
    // Escribir archivo y descargarlo
    XLSX.writeFile(wb, 'Hoja_de_Ruta.xlsx');
}

// Función para imprimir el formulario de hoja de ruta
function printRoutingForm() {
    const formContent = document.getElementById('routingForm');
    const originalContents = document.body.innerHTML;
    
    // Crear una copia del formulario para imprimir
    const printContent = formContent.cloneNode(true);
    document.body.innerHTML = '';
    document.body.appendChild(printContent);
    
    // Eliminar los botones de acción para la impresión
    const actionButtons = document.querySelectorAll('.form-actions button');
    actionButtons.forEach(button => {
        button.style.display = 'none';
    });
    
    // Imprimir
    window.print();
    
    // Restaurar el contenido original
    document.body.innerHTML = originalContents;
}

// Función para enviar por email (simulado para Outlook)
function sendEmail() {
    // Recoger datos básicos del formulario
    const referencia = document.getElementById('referencia').value || 'Hoja de Ruta';
    const asunto = document.getElementById('asunto1').value || 'Hoja de Ruta - Parque Industrial Santiváñez';
    
    // Crear una URL mailto para abrir el cliente de correo predeterminado
    const mailtoURL = `mailto:?subject=${encodeURIComponent(referencia)}&body=${encodeURIComponent('Adjunto hoja de ruta: ' + asunto)}`;
    
    // Abrir el cliente de correo
    window.location.href = mailtoURL;
    
    // Nota: Esta es una solución básica. Para realmente adjuntar la hoja de ruta,
    // se necesitaría una implementación de servidor o utilizar una API más avanzada.
    alert('Para adjuntar automáticamente la hoja de ruta, primero exporte el PDF y luego adjúntelo manualmente.');
}


// Función para exportar a PDF el historial de mantenimiento
function generateMaintenanceHistoryPDF() {
    // Obtener la tabla de mantenimiento
    const table = document.getElementById('maintenanceTable');
    if (!table) {
        alert('No se encontró la tabla de historial');
        return;
    }
    
    // Usar html2canvas para convertir la tabla a una imagen
    html2canvas(table).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); // landscape
        
        // Título del documento
        doc.setFontSize(16);
        doc.text('Historial de Mantenimiento - Parque Industrial Santiváñez', 15, 15);
        
        // Añadir la imagen de la tabla
        const imgWidth = 280;
        const imgHeight = canvas.height * imgWidth / canvas.width;
        doc.addImage(imgData, 'PNG', 10, 25, imgWidth, imgHeight);
        
        // Guardar el PDF
        doc.save('Historial_Mantenimiento.pdf');
    });
}

// Función para exportar a Excel el historial de mantenimiento
function exportMaintenanceHistoryToExcel() {
    // Obtener la tabla de mantenimiento
    const table = document.getElementById('maintenanceTable');
    if (!table) {
        alert('No se encontró la tabla de historial');
        return;
    }
    
    // Convertir tabla HTML a hoja de Excel
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(table);
    
    // Añadir worksheet al workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Historial');
    
    // Escribir archivo y descargarlo
    XLSX.writeFile(wb, 'Historial_Mantenimiento.xlsx');
}

</script>

<style>

/**
 * Estilos mejorados para el Sistema de Gestión de Mantenimiento
 * Parque Industrial Santiváñez
 */

 :root {
    /* Paleta de colores moderna */
    --primary-color: #0c4a6e;        /* Azul oscuro */
    --secondary-color: #0ea5e9;      /* Azul claro */
    --success-color: #16a34a;        /* Verde */
    --warning-color: #f59e0b;        /* Naranja */
    --danger-color: #dc2626;         /* Rojo */
    --info-color: #3b82f6;           /* Azul */
    --dark-color: #1e293b;           /* Gris oscuro */
    --light-color: #f8fafc;          /* Gris claro */
    --text-color: #334155;           /* Color texto principal */
    --text-muted: #64748b;           /* Color texto secundario */
    --background-color: #f1f5f9;     /* Fondo principal */
    
    /* Elementos de UI */
    --border-color: #e2e8f0;
    --border-radius: 0.5rem;
    --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    --transition: all 0.3s ease;
    --font-family: 'Arial', sans-serif;
}

/* Estilos generales */
body {
    font-family: var(--font-family);
    background-color: var(--background-color);
    color: var(--text-color);
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

.content-wrapper {
    padding: 1.5rem;
    margin: 0 auto;
    max-width: 1400px;
}

.card {
    background-color: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: none;
}

.card-header {
    padding: 1rem 1.5rem;
    background-color: rgba(12, 74, 110, 0.03);
    border-bottom: 1px solid var(--border-color);
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    margin: 0;
    color: var(--primary-color);
    font-weight: 600;
    font-size: 1.25rem;
}

.card-body {
    padding: 1.5rem;
}

/* Tablas de reportes */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 1rem;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: middle;
    border-top: 1px solid var(--border-color);
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid var(--border-color);
    background-color: var(--primary-color);
    color: white;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: background-color 0.2s;
}

.table tbody tr:hover {
    background-color: rgba(12, 74, 110, 0.04);
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(12, 74, 110, 0.02);
}

/* Barra de búsqueda y filtros */
.filters-section {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
    align-items: center;
}

.search-bar {
    display: flex;
    flex: 1;
    min-width: 250px;
}

.search-input {
    flex: 1;
    padding: 0.6rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius) 0 0 var(--border-radius);
    font-size: 0.95rem;
    transition: var(--transition);
}

.search-input:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
}

.search-button {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 0 1.25rem;
    border-radius: 0 var(--border-radius) var(--border-radius) 0;
    cursor: pointer;
    transition: var(--transition);
}

.search-button:hover {
    background-color: #0c3d5c;
}

.filter-dropdown {
    min-width: 200px;
    padding: 0.6rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    font-size: 0.95rem;
    background-color: white;
    transition: var(--transition);
}

.filter-dropdown:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
}

/* Botones de acción */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    border: none;
    font-size: 0.9rem;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background-color: #0c3d5c;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(12, 74, 110, 0.15);
}

.btn-secondary {
    background-color: var(--secondary-color);
    color: white;
}

.btn-secondary:hover {
    background-color: #0b8bc2;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(14, 165, 233, 0.15);
}

.btn-success {
    background-color: var(--success-color);
    color: white;
}

.btn-success:hover {
    background-color: #138a3f;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(22, 163, 74, 0.15);
}

.btn-danger {
    background-color: var(--danger-color);
    color: white;
}

.btn-danger:hover {
    background-color: #b91c1c;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(220, 38, 38, 0.15);
}

.btn-sm {
    padding: 0.35rem 0.7rem;
    font-size: 0.85rem;
}

.btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Paginación */
.pagination {
    display: flex;
    justify-content: center;
    padding: 1rem 0;
    list-style: none;
    gap: 0.25rem;
}

.pagination li a {
    display: flex;
    justify-content: center;
    align-items: center;
    min-width: 40px;
    height: 40px;
    border-radius: var(--border-radius);
    padding: 0 0.75rem;
    color: var(--primary-color);
    text-decoration: none;
    background-color: white;
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.pagination li a:hover {
    background-color: rgba(12, 74, 110, 0.05);
    border-color: var(--primary-color);
}

.pagination li.active a {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.pagination li.disabled a {
    color: var(--text-muted);
    pointer-events: none;
    background-color: #f8f9fa;
    border-color: var(--border-color);
}

/* Alertas y notificaciones */
.alert {
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 1.5rem;
    border-left: 4px solid transparent;
}

.alert-success {
    background-color: rgba(22, 163, 74, 0.1);
    border-left-color: var(--success-color);
    color: #166534;
}

.alert-danger {
    background-color: rgba(220, 38, 38, 0.1);
    border-left-color: var(--danger-color);
    color: #991b1b;
}

.alert-warning {
    background-color: rgba(245, 158, 11, 0.1);
    border-left-color: var(--warning-color);
    color: #b45309;
}

.alert-info {
    background-color: rgba(59, 130, 246, 0.1);
    border-left-color: var(--info-color);
    color: #1e40af;
}

/* Acciones de tabla */
.table-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: center;
}

.action-icon {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: rgba(14, 165, 233, 0.1);
    color: var(--secondary-color);
    transition: var(--transition);
}

.action-icon:hover {
    background-color: var(--secondary-color);
    color: white;
    transform: translateY(-2px);
}

.action-icon.edit {
    background-color: rgba(22, 163, 74, 0.1);
    color: var(--success-color);
}

.action-icon.edit:hover {
    background-color: var(--success-color);
    color: white;
}

.action-icon.delete {
    background-color: rgba(220, 38, 38, 0.1);
    color: var(--danger-color);
}

.action-icon.delete:hover {
    background-color: var(--danger-color);
    color: white;
}

/* Badges o etiquetas de estado */
.badge {
    display: inline-block;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 50rem;
}

.badge-success {
    background-color: var(--success-color);
    color: white;
}

.badge-warning {
    background-color: var(--warning-color);
    color: white;
}

.badge-danger {
    background-color: var(--danger-color);
    color: white;
}

.badge-info {
    background-color: var(--info-color);
    color: white;
}

.badge-secondary {
    background-color: var(--text-muted);
    color: white;
}

/* Estilos para la Hoja de Ruta */
.routing-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    transition: var(--transition);
    border: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
    padding: 1.25rem;
    page-break-inside: avoid;
}

.routing-form {
    font-family: var(--font-family);
    color: var(--text-color);
    background-color: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}

/* Encabezado del documento */
.document-header {
    text-align: center;
    margin-bottom: 1rem;
    position: relative;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.5rem;
}

.document-title {
    font-size: 1.25rem;
    font-weight: bold;
    margin: 0.5rem 0;
    text-transform: uppercase;
}

.routing-title {
    font-size: 1.1rem;
    font-weight: bold;
    margin: 0.5rem 0;
    text-transform: uppercase;
    border: 1px solid var(--border-color);
    padding: 0.5rem;
    border-radius: var(--border-radius);
    display: inline-block;
    min-width: 200px;
}

.company-logo {
    position: absolute;
    right: 1rem;
    top: 0;
    max-width: 100px;
    max-height: 100px;
}

/* Campos principales */
.main-fields {
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 1rem;
}

.field-row {
    display: flex;
    margin-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
}

.field-row:last-child {
    border-bottom: none;
}

.field-label {
    font-weight: bold;
    padding: 0.5rem;
    min-width: 150px;
    border-right: 1px solid var(--border-color);
}

.field-value {
    flex: 1;
    padding: 0.5rem;
}

/* Número de registro y fecha */
.registry-row {
    display: flex;
    align-items: stretch;
    margin-bottom: 0.5rem;
}

.registry-number {
    display: flex;
    align-items: center;
    margin-right: 1rem;
}

.registry-label {
    font-weight: bold;
    padding: 0.5rem;
    margin-right: 0.5rem;
}

.registry-input {
    border: 1px solid var(--border-color);
    padding: 0.3rem;
    width: 80px;
    text-align: center;
}

.date-field {
    display: flex;
    align-items: center;
}

.date-label {
    font-weight: bold;
    padding: 0.5rem;
    margin-right: 0.5rem;
}

.date-inputs {
    display: flex;
}

.date-input {
    border: 1px solid var(--border-color);
    padding: 0.3rem;
    width: 60px;
    text-align: center;
    margin-right: 2px;
}

/* Campos internos/externos */
.int-ext-container {
    display: flex;
    flex-direction: column;
    margin-left: auto;
}

.int-ext-row {
    display: flex;
    align-items: center;
    margin-bottom: 0.3rem;
}

.int-ext-label {
    font-weight: bold;
    padding: 0.3rem;
    margin-right: 0.5rem;
    min-width: 40px;
    text-align: right;
}

.int-ext-input {
    border: 1px solid var(--border-color);
    padding: 0.3rem;
    width: 80px;
}

/* Destinatarios */
.recipient-section {
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 1rem;
}

.recipient-row {
    display: flex;
    margin-bottom: 0.5rem;
}

.recipient-label {
    font-weight: bold;
    padding: 0.5rem;
    min-width: 150px;
    border-right: 1px solid var(--border-color);
}

.recipient-value {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    min-height: 30px;
}

/* Asunto */
.subject-section {
    margin-bottom: 1rem;
}

.subject-row {
    display: flex;
    margin-bottom: 0.5rem;
}

.subject-label {
    font-weight: bold;
    padding: 0.5rem;
    min-width: 150px;
    align-self: flex-start;
    border-right: 1px solid var(--border-color);
}

.subject-value {
    flex: 1;
    padding: 0.5rem;
    min-height: 80px;
    position: relative;
}

.subject-line {
    position: absolute;
    border-top: 1px dotted var(--border-color);
    left: 0;
    right: 0;
}

.line1 { top: 25px; }
.line2 { top: 45px; }
.line3 { top: 65px; }

/* Sección de derivación */
.derivation-section {
    display: flex;
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.derivation-left {
    width: 60%;
    border-right: 1px solid var(--border-color);
    padding: 0.5rem;
}

.derivation-title {
    font-weight: bold;
    text-align: center;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
}

.signature-area {
    height: 100px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    margin-bottom: 0.5rem;
}

.signature-title {
    text-align: center;
    font-weight: bold;
    padding: 0.5rem;
}

.derivation-right {
    width: 40%;
    padding: 0.5rem;
}

.date-box {
    margin-bottom: 0.5rem;
}

.date-row {
    display: flex;
    align-items: center;
    margin-bottom: 0.3rem;
}

.date-box-label {
    font-weight: bold;
    padding: 0.3rem;
    min-width: 60px;
    text-align: right;
}

.date-box-value {
    flex: 1;
    display: flex;
}

.small-input {
    border: 1px solid var(--border-color);
    padding: 0.3rem;
    width: 40px;
    text-align: center;
    margin-right: 2px;
}

/* Fecha de recepción */
.reception-section {
    text-align: center;
    width: 40%;
    min-width: 200px;
    margin-left: auto;
}

.reception-title {
    font-weight: bold;
    margin-bottom: 0.5rem;
    padding: 0.3rem;
    border-bottom: 1px solid var(--border-color);
}

.reception-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: auto auto;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.reception-header {
    border-bottom: 1px solid var(--border-color);
    padding: 0.3rem;
    font-size: 0.8rem;
    font-weight: bold;
    text-align: center;
}

.reception-cell {
    border-right: 1px solid var(--border-color);
    padding: 0.5rem;
    text-align: center;
}

.reception-cell:last-child {
    border-right: none;
}

/* Firma y sello */
.sign-seal-section {
    margin-top: 1rem;
}

.sign-seal-title {
    font-weight: bold;
    text-align: center;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
}

.sign-seal-area {
    height: 100px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    margin: 0 auto;
}

/* Estilos para PDF de hoja de ruta */
@page {
    size: A4;
    margin: 10mm;
}

.pdf-page {
    width: 210mm;
    height: 297mm;
    padding: 10mm;
    box-sizing: border-box;
    page-break-after: always;
}

.pdf-form {
    border: 1px solid #000;
    margin-bottom: 20px;
    page-break-inside: avoid;
}

.pdf-form-header {
    text-align: center;
    font-weight: bold;
    margin-bottom: 10px;
}

.pdf-form-row {
    display: flex;
    border-bottom: 1px solid #000;
}

.pdf-form-label {
    font-weight: bold;
    width: 150px;
    padding: 5px;
    border-right: 1px solid #000;
}

.pdf-form-field {
    flex: 1;
    padding: 5px;
}

.pdf-form-field-box {
    border: 1px solid #000;
    height: 30px;
}

.pdf-date-fields {
    display: flex;
    justify-content: space-around;
}

.pdf-date-field {
    display: flex;
    flex-direction: column;
    align-items: center;
    border: 1px solid #000;
    width: 60px;
    text-align: center;
}

.pdf-date-label {
    font-size: 10px;
    border-bottom: 1px solid #000;
    width: 100%;
    padding: 2px 0;
}

.pdf-date-value {
    height: 25px;
    width: 100%;
}

.pdf-signature-section {
    display: flex;
}

.pdf-signature-left {
    width: 60%;
    border-right: 1px solid #000;
    padding: 5px;
}

.pdf-signature-right {
    width: 40%;
    padding: 5px;
}

.pdf-signature-box {
    border: 1px solid #000;
    border-radius: 5px;
    height: 80px;
    margin-top: 5px;
}

.pdf-signature-title {
    text-align: center;
    font-weight: bold;
    margin-bottom: 5px;
}

/* Responsive para diferentes dispositivos */
@media (max-width: 992px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .filters-section {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-bar {
        width: 100%;
    }
    
    .registry-row,
    .field-row,
    .recipient-row,
    .subject-row,
    .derivation-section {
        flex-direction: column;
    }
    
    .field-label,
    .recipient-label,
    .subject-label {
        width: 100%;
        min-width: auto;
        border-right: none;
        border-bottom: 1px solid var(--border-color);
    }
}

@media (max-width: 768px) {
    .table-responsive {
        border: 0;
    }
    
    .table-responsive table {
        width: 100%;
    }
    
    .table-responsive thead {
        display: none;
    }
    
    .table-responsive tr {
        margin-bottom: 1rem;
        display: block;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }
    
    .table-responsive td {
        display: block;
        text-align: right;
        padding: 0.75rem;
        position: relative;
        border-bottom: 1px solid var(--border-color);
    }
    
    .table-responsive td:last-child {
        border-bottom: 0;
    }
    
    .table-responsive td::before {
        content: attr(data-label);
        position: absolute;
        left: 0.75rem;
        width: 45%;
        text-align: left;
        font-weight: bold;
    }
    
    .table-actions {
        justify-content: flex-end;
    }
    
    .derivation-left,
    .derivation-right {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid var(--border-color);
    }
    
    .int-ext-container {
        margin-left: 0;
        margin-top: 1rem;
    }
    
    .reception-section {
        width: 100%;
        margin: 1rem 0;
    }
    
    .company-logo {
        position: static;
        display: block;
        margin: 0 auto 1rem;
    }
}

@media (max-width: 576px) {
    .content-wrapper {
        padding: 1rem;
    }
    
    .card {
        padding: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .btn {
        padding: 0.4rem 0.8rem;
    }
    
    .action-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .btn {
        margin-bottom: 0.5rem;
    }
}

/* Estilos para impresión */
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background-color: white !important;
    }
    
    .content-wrapper,
    .card {
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }
    
    .table {
        border: 1px solid #000 !important;
    }
    
    .table th,
    .table td {
        border: 1px solid #000 !important;
    }
    
    .routing-card,
    .routing-form,
    .pdf-form {
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: 1px solid #000 !important;
    }
    
    .recipient-section,
    .subject-section,
    .derivation-section {
        page-break-inside: avoid !important;
    }
    
    .document-header {
        border-bottom: 2px solid #000 !important;
    }
    
    .signature-area,
    .sign-seal-area,
    .pdf-signature-box {
        border: 1px solid #000 !important;
    }
    
    .reception-grid {
        border: 1px solid #000 !important;
    }
    
    .reception-cell,
    .reception-header,
    .pdf-date-field,
    .pdf-form-row {
        border: 1px solid #000 !important;
    }
    
    input, textarea {
        color: black !important;
        border: none !important;
    }
    
    .action-buttons {
        display: none !important;
    }
    
    .pdf-page {
        page-break-after: always !important;
    }
    
    .pdf-form {
        page-break-inside: avoid !important;
    }
}
</style>

<script>
/**
 * Función para exportar el reporte a PDF
 */
function exportarPDF() {
    // Verificar que las bibliotecas estén cargadas
    if (!checkAndLoadLibraries(function() { exportarPDF(); })) {
        return;
    }
    
    try {
        // Obtener los datos del formulario
        const formData = getFormData();
        
        // Validar datos mínimos necesarios
        if (!validarDatosMinimos(formData)) {
            showNotification('Por favor complete al menos el código, nombre y fecha', 'warning');
            return;
        }
        
        // Crear instancia de jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Añadir encabezado
        addPDFHeader(doc, 'REPORTE DE MANTENIMIENTO');
        
        // Añadir detalles del mantenimiento
        let y = addMaintenanceDetails(doc, formData, 40);
        
        // Añadir sección de firmas
        addSignatureSection(doc, y);
        
        // Guardar el PDF
        doc.save(`Reporte_Mantenimiento_${formData.codigo}_${formData.fecha.replace(/\//g, '-')}.pdf`);
        
        showNotification('Documento PDF generado correctamente', 'success');
    } catch (error) {
        console.error('Error al generar PDF:', error);
        showNotification('Error al generar PDF: ' + error.message, 'error');
    }
    // Exportar formulario de mantenimiento a PDF con tabla verde y logo
    function generateMaintenancePDF() {
    // Verificar bibliotecas
    if (!checkAndLoadLibraries(function() { generateMaintenancePDF(); })) {
        return;
    }
    
    try {
        // Capturar los datos del formulario
        const formData = {
            nombrePlanta: document.getElementById('nombre_planta').value,
            ubicacion: document.getElementById('ubicacion').value,
            codigo: document.getElementById('codigo').value,
            fecha: document.getElementById('fecha_mantenimiento').value,
            tipoMantenimiento: document.getElementById('tipo_mantenimiento').value,
            responsable: document.getElementById('responsable').value,
            descripcion: document.getElementById('descripcion_actividad').value,
            materiales: document.getElementById('materiales').value,
            observaciones: document.getElementById('observaciones').value,
            estadoSalud: document.getElementById('estado_salud').value,
            fechaProximo: document.getElementById('fecha_proximo').value,
            firma: document.getElementById('firma').value
        };

        // Verificar si hay campos obligatorios vacíos
        if (!formData.nombrePlanta || !formData.ubicacion || !formData.codigo || 
            !formData.fecha || !formData.responsable || !formData.descripcion) {
            showNotification('Por favor complete todos los campos obligatorios antes de exportar el PDF.', 'error');
            return;
        }

        // Formatear fechas para mostrarlas en formato más legible
        const fechaFormateada = formatearFecha(formData.fecha);
        const fechaProximoFormateada = formData.fechaProximo ? formatearFecha(formData.fechaProximo) : 'No especificada';

        // Configurar el documento PDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Añadir un fondo sutil
        doc.setFillColor(248, 249, 250);
        doc.rect(0, 0, doc.internal.pageSize.width, doc.internal.pageSize.height, 'F');
        
        // Agregar logo en la esquina superior izquierda
        doc.addImage('assets/img/logo.png', 'PNG', 15, 10, 30, 30);
        
        // Añadir franja de color en la parte superior
        doc.setFillColor(0, 128, 0, 0.7); // Verde con transparencia
        doc.rect(0, 0, doc.internal.pageSize.width, 8, 'F');
        
        // Título del documento con color verde oscuro
        doc.setFontSize(20);
        doc.setTextColor(0, 100, 0); // Verde oscuro para el título
        doc.setFont('helvetica', 'bold');
        doc.text('Sistema de Mantenimiento de Plantas', 105, 25, { align: 'center' });
        
        doc.setFontSize(14);
        doc.setTextColor(60, 60, 60); // Gris oscuro para el subtítulo
        doc.text('Registro de Mantenimiento - Parque Industrial Santiváñez', 105, 35, { align: 'center' });
        
        // Añadir un recuadro decorativo alrededor del título
        doc.setDrawColor(0, 128, 0);
        doc.setLineWidth(0.5);
        doc.roundedRect(20, 15, 170, 25, 3, 3);
        
        // Añadir información de registro
        doc.setFontSize(10);
        doc.setTextColor(100, 100, 100);
        doc.setFont('helvetica', 'italic');
        doc.text(`Código: ${formData.codigo} | Fecha: ${fechaFormateada}`, 105, 45, { align: 'center' });
        
        // Establecer colores y estilos para la tabla principal
        const tableStyles = {
            headStyles: {
                fillColor: [0, 128, 0], // Color de fondo verde para encabezados
                textColor: 255,         // Texto blanco para encabezados
                fontStyle: 'bold',
                halign: 'center'
            },
            bodyStyles: {
                textColor: 40,
                fontSize: 10,
                cellPadding: 4
            },
            alternateRowStyles: {
                fillColor: [240, 255, 240] // Color verde muy claro para filas alternas
            },
            columnStyles: {
                0: { // Columna Campo
                    fontStyle: 'bold',
                    fillColor: [220, 240, 220],
                    textColor: [0, 100, 0],
                    cellWidth: 50
                },
                1: { // Columna Valor
                    cellWidth: 'auto'
                }
            },
            margin: { top: 50 }
        };

        // Crear los datos para la tabla
        const tableData = {
            head: [['Campo', 'Valor']],
            body: [
                ['Nombre de la Planta', formData.nombrePlanta],
                ['Ubicación', formData.ubicacion],
                ['Tipo de Mantenimiento', formData.tipoMantenimiento],
                ['Responsable', formData.responsable]
            ]
        };

        // Agregar la tabla principal al documento
        doc.autoTable({
            head: tableData.head,
            body: tableData.body,
            startY: 50,
            styles: tableStyles.bodyStyles,
            headStyles: tableStyles.headStyles,
            alternateRowStyles: tableStyles.alternateRowStyles,
            columnStyles: tableStyles.columnStyles,
            theme: 'grid'
        });
        
        // Obtener la posición Y después de la primera tabla
        let currentY = doc.lastAutoTable.finalY + 10;
        
        // Sección de descripción con título y recuadro destacado
        doc.setFillColor(0, 128, 0, 0.1);
        doc.setDrawColor(0, 100, 0);
        doc.roundedRect(14, currentY, 182, 35, 3, 3, 'FD');
        
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.setTextColor(0, 100, 0);
        doc.text('Descripción de la Actividad:', 20, currentY + 8);
        
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.setTextColor(50, 50, 50);
        const descripcionSplit = doc.splitTextToSize(formData.descripcion, 170);
        doc.text(descripcionSplit, 20, currentY + 16);
        
        // Actualizar la posición Y para la siguiente sección
        currentY += 40;
        
        // Crear datos para la tabla de detalles adicionales
        const detallesData = {
            head: [['Detalles Adicionales', 'Información']],
            body: [
                ['Materiales Utilizados', formData.materiales || 'No especificados'],
                ['Observaciones', formData.observaciones || 'Sin observaciones'],
                ['Estado de Salud', formData.estadoSalud],
                ['Próximo Mantenimiento', fechaProximoFormateada]
            ]
        };
        
        // Agregar la tabla de detalles
        doc.autoTable({
            head: detallesData.head,
            body: detallesData.body,
            startY: currentY,
            styles: tableStyles.bodyStyles,
            headStyles: tableStyles.headStyles,
            alternateRowStyles: tableStyles.alternateRowStyles,
            columnStyles: tableStyles.columnStyles,
            theme: 'grid'
        });
        
        // Actualizar la posición Y para la sección de firma
        currentY = doc.lastAutoTable.finalY + 20;
        
        // Sección de firma
        doc.setDrawColor(0, 100, 0);
        doc.setLineWidth(0.5);
        doc.line(60, currentY + 10, 150, currentY + 10);
        
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        doc.setTextColor(0, 100, 0);
        doc.text('Firma del Responsable:', 105, currentY, { align: 'center' });
        
        // Si hay texto de firma, mostrarlo
        if (formData.firma) {
            doc.setFont('helvetica', 'italic');
            doc.setFontSize(12);
            doc.text(formData.firma, 105, currentY + 8, { align: 'center' });
        }
        
        // Añadir pie de página con fecha de generación
        const pieY = doc.internal.pageSize.height - 15;
        
        // Franja inferior
        doc.setFillColor(0, 128, 0, 0.7);
        doc.rect(0, pieY - 5, doc.internal.pageSize.width, 20, 'F');
        
        // Texto de generación
        const fechaGeneracion = new Date().toLocaleDateString('es-BO');
        doc.setFontSize(9);
        doc.setTextColor(255, 255, 255);
        doc.setFont('helvetica', 'normal');
        doc.text(`Documento generado el ${fechaGeneracion} | Parque Industrial Santiváñez`, 105, pieY, { align: 'center' });
        
        // Agregar número de página
        doc.text(`Página 1 de 1`, 185, pieY, { align: 'right' });
        
        // Guardar el documento PDF
        doc.save(`Mantenimiento_${formData.codigo}_${formData.fecha}.pdf`);
        
        showNotification('Documento PDF generado correctamente', 'success');
    } catch (error) {
        console.error('Error al generar PDF:', error);
        showNotification('Error al generar PDF: ' + error.message, 'error');
    }
}
        
        // Agregar la tabla de detalles
        doc.autoTable({
            head: detallesData.head,
            body: detallesData.body,
            startY: currentY,
            styles: detallesStyles.bodyStyles,
            headStyles: detallesStyles.headStyles,
            alternateRowStyles: detallesStyles.alternateRowStyles,
            columnStyles: detallesStyles.columnStyles,
            didDrawCell: detallesStyles.didDrawCell,
            theme: 'grid'
        });
        
        // Actualizar la posición Y para la sección de firma
        currentY = doc.lastAutoTable.finalY + 20;
        
        // Sección de firma
        doc.setDrawColor(0, 100, 0);
        doc.setLineWidth(0.5);
        doc.line(60, currentY + 10, 150, currentY + 10);
        
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        doc.setTextColor(0, 100, 0);
        doc.text('Firma del Responsable:', 105, currentY, { align: 'center' });
        
        // Si hay texto de firma, mostrarlo
        if (formData.firma) {
            doc.setFont('helvetica', 'italic');
            doc.setFontSize(12);
            doc.text(formData.firma, 105, currentY + 8, { align: 'center' });
        }
        
        // Añadir sello/validación (círculo decorativo)
        doc.setDrawColor(0, 128, 0);
        doc.setFillColor(0, 128, 0, 0.05);
        doc.circle(160, currentY + 5, 10, 'FD');
        
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(8);
        doc.setTextColor(0, 100, 0);
        doc.text('VÁLIDO', 160, currentY + 5, { align: 'center' });
        
        // Añadir pie de página con fecha de generación
        const pieY = doc.internal.pageSize.height - 15;
        
        // Franja inferior
        doc.setFillColor(0, 128, 0, 0.7);
        doc.rect(0, pieY - 5, doc.internal.pageSize.width, 20, 'F');
        
        // Texto de generación
        const fechaGeneracion = new Date().toLocaleDateString('es-BO');
        doc.setFontSize(9);
        doc.setTextColor(255, 255, 255);
        doc.setFont('helvetica', 'normal');
        doc.text(`Documento generado el ${fechaGeneracion} | Parque Industrial Santiváñez`, 105, pieY, { align: 'center' });
        
        // Agregar número de página
        doc.text(`Página 1 de 1`, 185, pieY, { align: 'right' });
        
        // Guardar el documento PDF
        doc.save(`Mantenimiento_${formData.codigo}_${formData.fecha}.pdf`);
        
        showNotification('Documento PDF generado correctamente', 'success');
    } catch (error) {
        console.error('Error al generar PDF:', error);
        showNotification('Error al generar PDF: ' + error.message, 'error');
    }


// Función auxiliar para formatear fechas si no existe
if (typeof formatearFecha !== 'function') {
    function formatearFecha(fechaISO) {
        if (!fechaISO) return '';
        const fecha = new Date(fechaISO);
        return fecha.toLocaleDateString('es-BO', { 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric' 
        });
    }
}


/**
 * Función para imprimir el reporte
 */
function imprimirReporte() {
    // Verificar que las bibliotecas estén cargadas
    if (!checkAndLoadLibraries(function() { imprimirReporte(); })) {
        return;
    }
    
    try {
        // Obtener los datos del formulario
        const formData = getFormData();
        
        // Validar datos mínimos necesarios
        if (!validarDatosMinimos(formData)) {
            showNotification('Por favor complete al menos el código, nombre y fecha', 'warning');
            return;
        }
        
        // Crear instancia de jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Añadir encabezado
        addPDFHeader(doc, 'REPORTE DE MANTENIMIENTO');
        
        // Añadir detalles del mantenimiento
        let y = addMaintenanceDetails(doc, formData, 40);
        
        // Añadir sección de firmas
        addSignatureSection(doc, y);
        
        // Generar blob para imprimir
        const pdfBlob = doc.output('blob');
        const blobUrl = URL.createObjectURL(pdfBlob);
        
        // Abrir ventana de impresión
        const printWindow = window.open(blobUrl, '_blank');
        if (!printWindow) {
            showNotification('El navegador bloqueó la ventana emergente. Por favor, permita ventanas emergentes para este sitio.', 'warning');
            return;
        }
        
        printWindow.onload = function() {
            printWindow.print();
            // Liberar el objeto URL después de imprimir
            setTimeout(function() {
                URL.revokeObjectURL(blobUrl);
            }, 100);
        };
        
        showNotification('Documento enviado a impresión', 'success');
    } catch (error) {
        console.error('Error al preparar impresión:', error);
        showNotification('Error al preparar impresión: ' + error.message, 'error');
    }
}

/**
 * Función para enviar por Outlook
 */
function enviarPorOutlook() {
    try {
        // Obtener los datos del formulario
        const formData = getFormData();
        
        // Validar datos mínimos necesarios
        if (!validarDatosMinimos(formData)) {
            showNotification('Por favor complete al menos el código, nombre y fecha', 'warning');
            return;
        }
        
        // Verificar que las bibliotecas estén cargadas para generar el PDF
        if (!checkAndLoadLibraries(function() { 
            generarYEnviarPorOutlook(); 
        })) {
            return;
        }
        
        function generarYEnviarPorOutlook() {
            try {
                // Crear instancia de jsPDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                // Añadir encabezado
                addPDFHeader(doc, 'REPORTE DE MANTENIMIENTO');
                
                // Añadir detalles del mantenimiento
                let y = addMaintenanceDetails(doc, formData, 40);
                
                // Añadir sección de firmas
                addSignatureSection(doc, y);
                
                // Crear el cuerpo del correo
                const asunto = `Reporte de Mantenimiento - ${formData.codigo} - ${formData.fecha}`;
                const cuerpo = `
Estimado/a,

Adjunto el reporte de mantenimiento con la siguiente información:

Código: ${formData.codigo}
Nombre: ${formData.nombre}
Fecha: ${formData.fecha}
Tipo: ${formData.tipo}
Técnico: ${formData.tecnico}

Saludos cordiales.
                `;
                
                // Crear enlace para Outlook
                const outlookUrl = `mailto:?subject=${encodeURIComponent(asunto)}&body=${encodeURIComponent(cuerpo)}`;
                
                // Abrir cliente de correo predeterminado
                window.location.href = outlookUrl;
                
                // Mostrar instrucciones al usuario para adjuntar el PDF
                setTimeout(function() {
                    showNotification('Se ha abierto el cliente de correo. Ahora debes adjuntar manualmente el PDF que se descargará a continuación.', 'info', 10000);
                    // Descargar el PDF para que el usuario lo pueda adjuntar
                    doc.save(`Reporte_Mantenimiento_${formData.codigo}_${formData.fecha.replace(/\//g, '-')}.pdf`);
                }, 1000);
            } catch (error) {
                console.error('Error al preparar correo:', error);
                showNotification('Error al preparar correo: ' + error.message, 'error');
            }
        }
    } catch (error) {
        console.error('Error al enviar por Outlook:', error);
        showNotification('Error al enviar por Outlook: ' + error.message, 'error');
    }
}

/**
 * Función para guardar hoja de ruta en PC
 */
function guardarHojaRuta() {
    // Verificar que las bibliotecas estén cargadas
    if (!checkAndLoadLibraries(function() { guardarHojaRuta(); })) {
        return;
    }
    
    try {
        // Obtener el ID de la planta del formulario
        const plantaId = document.getElementById('plantaId')?.value || 0;
        if (!plantaId || plantaId <= 0) {
            showNotification('Por favor seleccione una planta válida', 'warning');
            return;
        }
        
        // Mostrar notificación de carga
        showNotification('Obteniendo datos de la planta...', 'info');
        
        // Obtener los datos del formulario o del servidor según sea necesario
        obtenerDatosReporte(plantaId)
            .then(data => {
                // Crear instancia de jsPDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                // Añadir encabezado
                addPDFHeader(doc, 'HOJA DE RUTA - MANTENIMIENTO');
                
                // Añadir datos específicos de la hoja de ruta
                let y = 40;
                
                // Título de la sección
                doc.setFontSize(14);
                doc.setFont('helvetica', 'bold');
                doc.text('HOJA DE RUTA', doc.internal.pageSize.width / 2, y, { align: 'center' });
                y += 10;
                
                // Información general
                doc.setFontSize(11);
                doc.setFont('helvetica', 'bold');
                doc.text('INFORMACIÓN GENERAL', 20, y);
                y += 10;
                
                // Crear tabla de información
                const tableData = [
                    ['Código', data.codigo || data.planta_id || 'N/A'],
                    ['Nombre', data.nombre || 'N/A'],
                    ['Ubicación', data.ubicacion || 'N/A'],
                    ['Fecha', data.fecha || new Date().toLocaleDateString('es-ES')],
                    ['Tipo', data.tipo || 'N/A'],
                    ['Técnico', data.tecnico || 'N/A'],
                    ['Estado', data.estado || 'N/A'],
                ];
                
                doc.autoTable({
                    startY: y,
                    body: tableData,
                    theme: 'grid',
                    styles: { fontSize: 10 },
                    columnStyles: {
                        0: { fontStyle: 'bold', cellWidth: 40 },
                        1: { cellWidth: 'auto' }
                    },
                    margin: { left: 20, right: 20 }
                });
                
                y = doc.lastAutoTable.finalY + 15;
                
                // Sección de actividades
                doc.setFontSize(11);
                doc.setFont('helvetica', 'bold');
                doc.text('ACTIVIDADES PROGRAMADAS', 20, y);
                y += 10;
                
                // Tabla de actividades (ejemplo o datos reales si están disponibles)
                const actividadesData = data.actividades || [
                    ['1', 'Inspección inicial', '09:00', 'Pendiente'],
                    ['2', 'Mantenimiento preventivo', '10:00', 'Pendiente'],
                    ['3', 'Verificación de funcionamiento', '11:30', 'Pendiente'],
                    ['4', 'Documentación y cierre', '12:30', 'Pendiente']
                ];
                
                const actividadesHeader = [['#', 'Actividad', 'Hora Programada', 'Estado']];
                
                doc.autoTable({
                    startY: y,
                    head: actividadesHeader,
                    body: actividadesData,
                    theme: 'grid',
                    styles: { fontSize: 10 },
                    headStyles: { fillColor: [66, 135, 245], textColor: [255, 255, 255] },
                    margin: { left: 20, right: 20 }
                });
                
                y = doc.lastAutoTable.finalY + 15;
                
                // Sección para firmas
                y = addSignatureSection(doc, y);
                
                // Guardar el documento
                const nombreArchivo = `Hoja_Ruta_${data.codigo || plantaId}_${data.fecha || new Date().toISOString().split('T')[0].replace(/-/g, '')}.pdf`;
                doc.save(nombreArchivo);
                
                showNotification('Hoja de ruta guardada correctamente como: ' + nombreArchivo, 'success');
            })
            .catch(error => {
                console.error('Error al obtener datos:', error);
                showNotification('Error al obtener datos: ' + error.message, 'error');
            });
    } catch (error) {
        console.error('Error al guardar hoja de ruta:', error);
        showNotification('Error al guardar hoja de ruta: ' + error.message, 'error');
    }
}

/**
 * Función para validar datos mínimos necesarios
 */
function validarDatosMinimos(formData) {
    return formData.codigo && formData.nombre && formData.fecha;
}

/**
 * Función para obtener los datos del formulario
 */
function getFormData() {
    return {
        codigo: document.getElementById('codigo')?.value || '',
        nombre: document.getElementById('nombre')?.value || '',
        fecha: document.getElementById('fecha')?.value || new Date().toLocaleDateString('es-ES'),
        tipo: document.getElementById('tipo')?.value || '',
        tecnico: document.getElementById('tecnico')?.value || '',
        estado: document.getElementById('estado')?.value || '',
        ubicacion: document.getElementById('ubicacion')?.value || '',
        observaciones: document.getElementById('observaciones')?.value || '',
        actividades: obtenerActividades() || []
    };
}

/**
 * Función para obtener las actividades del formulario
 */
function obtenerActividades() {
    // Intenta obtener la tabla de actividades si existe
    const tabla = document.getElementById('tablaActividades');
    if (!tabla) return null;
    
    const actividades = [];
    const filas = tabla.querySelectorAll('tbody tr');
    
    filas.forEach((fila, index) => {
        const celdas = fila.querySelectorAll('td');
        if (celdas.length >= 4) {
            actividades.push([
                (index + 1).toString(), // Número de actividad
                celdas[0].textContent.trim() || 'N/A', // Descripción
                celdas[1].textContent.trim() || 'N/A', // Hora
                celdas[2].textContent.trim() || 'Pendiente', // Estado
            ]);
        }
    });
    
    return actividades.length > 0 ? actividades : null;
}

/**
 * Función para añadir el encabezado al PDF
 */
function addPDFHeader(doc, title) {
    // Obtener dimensiones de la página
    const pageWidth = doc.internal.pageSize.width;
    
    // Añadir título
    doc.setFontSize(16);
    doc.setFont('helvetica', 'bold');
    doc.text(title, pageWidth / 2, 20, { align: 'center' });
    
    // Añadir fecha y hora de generación
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    const fechaHora = `Generado: ${new Date().toLocaleString('es-ES')}`;
    doc.text(fechaHora, pageWidth - 20, 10, { align: 'right' });
    
    // Línea separadora
    doc.setLineWidth(0.5);
    doc.line(20, 25, pageWidth - 20, 25);
}

/**
 * Función para añadir detalles del mantenimiento al PDF
 */
function addMaintenanceDetails(doc, formData, startY) {
    let y = startY;
    
    // Información general
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('INFORMACIÓN GENERAL', 20, y);
    y += 10;
    
    // Crear tabla con la información general
    const tableData = [
        ['Código', formData.codigo || 'N/A'],
        ['Nombre', formData.nombre || 'N/A'],
        ['Fecha', formData.fecha || 'N/A'],
        ['Tipo', formData.tipo || 'N/A'],
        ['Técnico', formData.tecnico || 'N/A'],
        ['Estado', formData.estado || 'N/A'],
        ['Ubicación', formData.ubicacion || 'N/A']
    ];
    
    doc.autoTable({
        startY: y,
        body: tableData,
        theme: 'grid',
        styles: { fontSize: 10 },
        columnStyles: {
            0: { fontStyle: 'bold', cellWidth: 40 },
            1: { cellWidth: 'auto' }
        },
        margin: { left: 20, right: 20 }
    });
    
    y = doc.lastAutoTable.finalY + 15;
    
    // Sección de observaciones si existen
    if (formData.observaciones) {
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.text('OBSERVACIONES', 20, y);
        y += 10;
        
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        
        // Agregar texto con saltos de línea automáticos
        const splitObservaciones = doc.splitTextToSize(formData.observaciones, doc.internal.pageSize.width - 40);
        doc.text(splitObservaciones, 20, y);
        
        y += splitObservaciones.length * 6 + 10; // Ajustar según el número de líneas
    }
    
    // Sección de actividades si existen
    if (formData.actividades && formData.actividades.length > 0) {
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.text('ACTIVIDADES REALIZADAS', 20, y);
        y += 10;
        
        const actividadesHeader = [['#', 'Actividad', 'Hora', 'Estado']];
        
        doc.autoTable({
            startY: y,
            head: actividadesHeader,
            body: formData.actividades,
            theme: 'grid',
            styles: { fontSize: 10 },
            headStyles: { fillColor: [66, 135, 245], textColor: [255, 255, 255] },
            margin: { left: 20, right: 20 }
        });
        
        y = doc.lastAutoTable.finalY + 15;
    }
    
    return y;
}

/**
 * Función para añadir sección de firmas al PDF
 */
function addSignatureSection(doc, startY) {
    let y = startY;
    
    // Título de sección
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('FIRMAS', 20, y);
    y += 15;
    
    // Líneas para firmas
    const pageWidth = doc.internal.pageSize.width;
    const firmaWidth = 60;
    const espacio = (pageWidth - 40 - (firmaWidth * 2)) / 3;
    
    // Primera línea (Técnico)
    doc.line(20 + espacio, y + 20, 20 + espacio + firmaWidth, y + 20);
    doc.setFontSize(9);
    doc.text('Firma del Técnico', 20 + espacio + (firmaWidth/2), y + 25, { align: 'center' });
    
    // Segunda línea (Supervisor)
    doc.line(20 + (espacio*2) + firmaWidth, y + 20, 20 + (espacio*2) + (firmaWidth*2), y + 20);
    doc.text('Firma del Supervisor', 20 + (espacio*2) + firmaWidth + (firmaWidth/2), y + 25, { align: 'center' });
    
    return y + 35; // Devolver la nueva posición Y
}

/**
 * Función para obtener datos del reporte desde el servidor
 */
async function obtenerDatosReporte(plantaId) {
    // Mostrar notificación
    showNotification('Consultando datos de la planta...', 'info');
    
    // Si estamos en modo desarrollo o no hay un endpoint configurado,
    // devolver datos de prueba
    if (!window.apiUrl || location.hostname === 'localhost') {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    planta_id: plantaId,
                    codigo: 'P-' + plantaId.toString().padStart(4, '0'),
                    nombre: 'Planta ' + plantaId,
                    ubicacion: 'Sector Industrial ' + Math.floor(Math.random() * 5 + 1),
                    fecha: new Date().toLocaleDateString('es-ES'),
                    tipo: 'Mantenimiento Preventivo',
                    tecnico: 'Técnico Asignado',
                    estado: 'Programado',
                    actividades: [
                        ['1', 'Inspección de equipos', '09:00', 'Pendiente'],
                        ['2', 'Mantenimiento de bombas', '10:30', 'Pendiente'],
                        ['3', 'Calibración de sensores', '12:00', 'Pendiente'],
                        ['4', 'Prueba de funcionamiento', '14:00', 'Pendiente']
                    ]
                });
            }, 1000); // Simular retraso de red
        });
    }
    
    // Si hay un endpoint, hacer la petición real
    try {
        const response = await fetch(`${window.apiUrl}/plantas/${plantaId}`);
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        return await response.json();
    } catch (error) {
        console.error('Error al obtener datos de la planta:', error);
        showNotification('Error al obtener datos: ' + error.message, 'error');
        throw error;
    }
}

/**
 * Función para inicializar datepickers
 */
function inicializarDatePickers() {
    // Verificar si hay campos de fecha
    const camposFecha = document.querySelectorAll('input[type="date"]');
    
    // Si no hay campos tipo date o ya tienen datepicker, salir
    if (camposFecha.length === 0 || document.querySelector('.datepicker')) {
        return;
    }
    
    // Verificar si existe jQuery y Bootstrap Datepicker
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.datepicker !== 'undefined') {
        // Inicializar datepickers
        jQuery('input[type="date"]').each(function() {
            const $this = jQuery(this);
            
            // Crear un input para el datepicker
            const dateInput = document.createElement('input');
            dateInput.type = 'text';
            dateInput.className = $this.attr('class') + ' datepicker';
            dateInput.id = $this.attr('id') + '_datepicker';
            dateInput.placeholder = 'Seleccione fecha';
            
            // Insertar después del campo date original
            $this.after(dateInput);
            
            // Ocultar el campo original
            $this.css('display', 'none');
            
            // Inicializar datepicker
            jQuery(dateInput).datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                language: 'es',
                todayHighlight: true
            }).on('changeDate', function(e) {
                // Actualizar el campo original
                const fecha = e.format('yyyy-mm-dd');
                $this.val(fecha);
                $this.trigger('change');
            });
            
            // Si hay valor, inicializarlo
            if ($this.val()) {
                const parts = $this.val().split('-');
                if (parts.length === 3) {
                    jQuery(dateInput).datepicker('setDate', new Date(parts[0], parts[1] - 1, parts[2]));
                }
            }
        });
    }
}

/**
 * Función para marcar campos obligatorios
 */
function marcarCamposObligatorios() {
    // Lista de IDs de campos obligatorios
    const camposObligatorios = ['codigo', 'nombre', 'fecha'];
    
    camposObligatorios.forEach(id => {
        const campo = document.getElementById(id);
        if (campo) {
            // Añadir asterisco rojo
            const label = document.querySelector(`label[for="${id}"]`);
            if (label && !label.querySelector('.required-mark')) {
                const asterisco = document.createElement('span');
                asterisco.className = 'required-mark';
                asterisco.textContent = ' *';
                asterisco.style.color = 'red';
                label.appendChild(asterisco);
            }
            
            // Añadir validación en tiempo real
            campo.addEventListener('blur', function() {
                if (!campo.value.trim()) {
                    campo.classList.add('is-invalid');
                    // Verificar si ya existe un mensaje de error
                    let feedbackDiv = campo.nextElementSibling;
                    if (!feedbackDiv || !feedbackDiv.classList.contains('invalid-feedback')) {
                        feedbackDiv = document.createElement('div');
                        feedbackDiv.className = 'invalid-feedback';
                        feedbackDiv.textContent = 'Este campo es obligatorio';
                        campo.after(feedbackDiv);
                    }
                } else {
                    campo.classList.remove('is-invalid');
                    const feedbackDiv = campo.nextElementSibling;
                    if (feedbackDiv && feedbackDiv.classList.contains('invalid-feedback')) {
                        feedbackDiv.remove();
                    }
                }
            });
        }
    });
}

/**
 * Función para verificar y cargar bibliotecas necesarias
 */
function checkAndLoadLibraries(callback) {
    // Lista de bibliotecas necesarias
    const libraries = {
        jspdf: {
            loaded: window.jspdf !== undefined,
            src: 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
            integrity: 'sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA=='
        },
        autoTable: {
            loaded: window.jspdf?.autoTable !== undefined,
            src: 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js',
            integrity: 'sha512-LGst4P1ocE7zZ3wxTl6xbJNvI05gQ32mnz8fctYDRgUKs5NHgR3mAQzFa93yPZcCqnTJO6CH11/+1zM8x0kQbg=='
        },
        bootstrap: {
            loaded: typeof bootstrap !== 'undefined',
            src: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            integrity: 'sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz'
        }
    };
    
    // Verificar si todas las bibliotecas están cargadas
    const allLoaded = Object.values(libraries).every(lib => lib.loaded);
    
    if (allLoaded) {
        // Si todas están cargadas, ejecutar callback si existe
        if (typeof callback === 'function') {
            callback();
        }
        return true;
    }
    
    // Si alguna no está cargada, mostrar notificación
    showNotification('Cargando bibliotecas necesarias...', 'info');
    
    // Array para almacenar promesas de carga
    const loadPromises = [];
    
    // Función para cargar una biblioteca
    function loadLibrary(lib) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = lib.src;
            if (lib.integrity) {
                script.integrity = lib.integrity;
                script.crossOrigin = 'anonymous';
            }
            
            script.onload = () => {
                console.log(`Biblioteca ${lib.src} cargada correctamente`);
                resolve();
            };
            
            script.onerror = () => {
                console.error(`Error al cargar la biblioteca ${lib.src}`);
                reject(new Error(`Error al cargar la biblioteca ${lib.src}`));
            };
            
            document.head.appendChild(script);
        });
    }
    
    // Cargar las bibliotecas que faltan
    Object.values(libraries).forEach(lib => {
        if (!lib.loaded) {
            loadPromises.push(loadLibrary(lib));
        }
    });
    
    // Esperar a que todas las bibliotecas se carguen
    Promise.all(loadPromises)
        .then(() => {
            showNotification('Bibliotecas cargadas correctamente', 'success');
            if (typeof callback === 'function') {
                setTimeout(callback, 500); // Pequeño retraso para asegurar que todo esté listo
            }
        })
        .catch(error => {
            console.error('Error al cargar bibliotecas:', error);
            showNotification('Error al cargar las bibliotecas necesarias. Verifique su conexión a Internet.', 'error');
        });
    
    return false;
}

/**
 * Función para cargar la biblioteca Excel
 */
function loadExcelLibrary(callback) {
    showNotification('Cargando biblioteca Excel...', 'info');
    
    // Verificar si ya está cargada
    if (window.XLSX) {
        showNotification('Biblioteca Excel ya está cargada.', 'success');
        if (typeof callback === 'function') setTimeout(callback, 500);
        return;
    }
    
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
    script.integrity = 'sha512-r22gChDnGvBylk90+2e/ycr3RVrDi8DIOkIGNhJlKfuyQM4tIRAI062MaV8sfjQKYVGjOBaZBOA87z+IhZE9DA==';
    script.crossOrigin = 'anonymous';
    
    script.onload = function() {
        console.log('Biblioteca Excel cargada dinámicamente');
        showNotification('Biblioteca Excel cargada correctamente.', 'success');
        if (typeof callback === 'function') setTimeout(callback, 500);
    };
    
    script.onerror = function() {
        console.error('Error al cargar la biblioteca Excel dinámicamente');
        showNotification('Error al cargar la biblioteca Excel. Verifique su conexión a Internet.', 'error');
    };
    
    document.head.appendChild(script);
}

/**
 * Función para mostrar notificaciones
 */
function showNotification(message, type = 'info', duration = 5000) {
    // Crear contenedor de notificaciones si no existe
    let notificationsContainer = document.getElementById('notificationsContainer');
    if (!notificationsContainer) {
        notificationsContainer = document.createElement('div');
        notificationsContainer.id = 'notificationsContainer';
        notificationsContainer.style.position = 'fixed';
        notificationsContainer.style.top = '20px';
        notificationsContainer.style.right = '20px';
        notificationsContainer.style.zIndex = '9999';
        notificationsContainer.style.display = 'flex';
        notificationsContainer.style.flexDirection = 'column';
        notificationsContainer.style.gap = '10px';
        document.body.appendChild(notificationsContainer);
    }
    
    // Crear el elemento de notificación
    const notification = document.createElement('div');
    
    // Asignar clases según Bootstrap
    notification.className = `alert alert-${type} notification-popup`;
    
    // Crear icono según el tipo
    let iconHtml = '';
    switch (type) {
        case 'success':
            iconHtml = '<i class="fas fa-check-circle" style="margin-right: 10px;"></i>';
            break;
        case 'warning':
            iconHtml = '<i class="fas fa-exclamation-triangle" style="margin-right: 10px;"></i>';
            break;
        case 'error':
        case 'danger':
            iconHtml = '<i class="fas fa-times-circle" style="margin-right: 10px;"></i>';
            break;
        default:
            iconHtml = '<i class="fas fa-info-circle" style="margin-right: 10px;"></i>';
    }
    
    // Añadir icono y mensaje
    notification.innerHTML = iconHtml + message;
    // Estilos CSS para la notificación
    notification.style.minWidth = '300px';
    notification.style.maxWidth = '400px';
    notification.style.padding = '15px';
    notification.style.borderRadius = '5px';
    notification.style.animation = 'fadeIn 0.3s ease-in';
    notification.style.position = 'relative';
    notification.style.marginBottom = '0';
    notification.style.display = 'flex';
    notification.style.alignItems = 'center';
    
    // Agregar botón para cerrar la notificación
    const closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'btn-close';
    closeButton.style.position = 'absolute';
    closeButton.style.top = '10px';
    closeButton.style.right = '10px';
    closeButton.addEventListener('click', function() {
        removeNotification(notification);
    });
    
    notification.appendChild(closeButton);
    
    // Agregar la notificación al contenedor
    notificationsContainer.appendChild(notification);
    
    // Eliminar la notificación después del tiempo especificado
    if (duration > 0) {
        setTimeout(function() {
            removeNotification(notification);
        }, duration);
    }
    
    // Función para eliminar la notificación con animación
    function removeNotification(element) {
        if (notificationsContainer.contains(element)) {
            element.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(function() {
                if (notificationsContainer.contains(element)) {
                    notificationsContainer.removeChild(element);
                    
                    // Si no quedan notificaciones, eliminar el contenedor
                    if (notificationsContainer.childElementCount === 0) {
                        document.body.removeChild(notificationsContainer);
                    }
                }
            }, 300);
        }
    }
    
    // Retornar la notificación para posibles interacciones adicionales
    return notification;
}

/**
 * Función para exportar a Excel (nueva funcionalidad)
 */
function exportarExcel() {
    // Verificar que la biblioteca XLSX esté cargada
    if (typeof XLSX === 'undefined') {
        loadExcelLibrary(function() {
            exportarExcel();
        });
        return;
    }
    
    try {
        // Obtener los datos del formulario
        const formData = getFormData();
        
        // Validar datos mínimos necesarios
        if (!validarDatosMinimos(formData)) {
            showNotification('Por favor complete al menos el código, nombre y fecha', 'warning');
            return;
        }
        
        // Crear la estructura del libro Excel
        const wb = XLSX.utils.book_new();
        
        // Crear hoja de datos generales
        const datosGenerales = [
            ['REPORTE DE MANTENIMIENTO'],
            [],
            ['INFORMACIÓN GENERAL'],
            ['Código', formData.codigo],
            ['Nombre', formData.nombre],
            ['Fecha', formData.fecha],
            ['Tipo', formData.tipo],
            ['Técnico', formData.tecnico],
            ['Estado', formData.estado],
            ['Ubicación', formData.ubicacion]
        ];
        
        // Agregar observaciones si existen
        if (formData.observaciones) {
            datosGenerales.push([]);
            datosGenerales.push(['OBSERVACIONES']);
            datosGenerales.push([formData.observaciones]);
        }
        
        // Crear hoja y agregarla al libro
        const ws = XLSX.utils.aoa_to_sheet(datosGenerales);
        
        // Ajustar estilos (mergear celdas para el título)
        if (!ws['!merges']) ws['!merges'] = [];
        ws['!merges'].push({ s: { r: 0, c: 0 }, e: { r: 0, c: 1 } }); // Merge para el título
        
        // Aplicar algunos estilos básicos (lo que XLSX permite)
        ws['A1'] = { v: 'REPORTE DE MANTENIMIENTO', t: 's', s: { font: { bold: true, sz: 16 } } };
        
        // Agregar la hoja al libro
        XLSX.utils.book_append_sheet(wb, ws, "Información General");
        
        // Crear hoja de actividades si existen
        if (formData.actividades && formData.actividades.length > 0) {
            const headings = ['#', 'Actividad', 'Hora', 'Estado'];
            const actividadesData = [headings].concat(formData.actividades);
            const wsActividades = XLSX.utils.aoa_to_sheet(actividadesData);
            XLSX.utils.book_append_sheet(wb, wsActividades, "Actividades");
        }
        
        // Generar el archivo y descargarlo
        const fileName = `Reporte_Mantenimiento_${formData.codigo}_${formData.fecha.replace(/\//g, '-')}.xlsx`;
        XLSX.writeFile(wb, fileName);
        
        showNotification(`Archivo Excel "${fileName}" generado correctamente`, 'success');
    } catch (error) {
        console.error('Error al generar Excel:', error);
        showNotification('Error al generar Excel: ' + error.message, 'error');
    }
}

// Agregar aquí cualquier inicialización que sea necesaria
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar datepickers si están disponibles
    inicializarDatePickers();
    
    // Marcar campos obligatorios
    marcarCamposObligatorios();
    
    // Inicializar tooltips, popovers u otros componentes de Bootstrap si son necesarios
    if (typeof bootstrap !== 'undefined') {
        // Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }
    
    // Inicializar los gráficos si existen Canvas
    if (document.getElementById('maintenanceTypeChart') && typeof Chart !== 'undefined') {
        initializeCharts();
    }
});

/**
 * Función para inicializar los gráficos de la página
 */
function initializeCharts() {
    // Gráfico de tipos de mantenimiento (ejemplo)
    const ctxType = document.getElementById('maintenanceTypeChart').getContext('2d');
    new Chart(ctxType, {
        type: 'pie',
        data: {
            labels: ['Lubricación', 'Calibración', 'Reemplazo', 'Limpieza', 'Inspección', 'Reparación'],
            datasets: [{
                data: [12, 8, 5, 15, 20, 10],
                backgroundColor: [
                    '#4361ee', '#4895ef', '#4cc9f0', '#4361ee', '#0a9396', '#005f73'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
    
    // Gráfico de estado de salud de plantas (ejemplo)
    const ctxHealth = document.getElementById('plantHealthChart').getContext('2d');
    new Chart(ctxHealth, {
        type: 'doughnut',
        data: {
            labels: ['Excelente', 'Bueno', 'Regular', 'Malo', 'Crítico'],
            datasets: [{
                data: [35, 25, 15, 10, 5],
                backgroundColor: [
                    '#38b000', '#70e000', '#ffdd00', '#f48c06', '#d00000'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
}

</script>