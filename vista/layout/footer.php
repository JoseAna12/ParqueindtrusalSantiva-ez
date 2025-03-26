<!-- Bootstrap y otros scripts base -->
<script src="../public/bootstrap5/js/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>

<!-- Core Libraries (Bootstrap, jQuery, Tether - solo si es necesario) -->
<script src="../public/app/publico/js/lib/jquery/jquery.min.js"></script> <!-- Solo si es necesario -->
<script src="../public/app/publico/js/lib/tether/tether.min.js"></script> <!-- Solo si es necesario -->

<!-- DataTables con extensiones para PDF y Excel -->
<script src="../public/app/publico/js/lib/datatables-net/datatables.min.js"></script>
<script src="../public/app/publico/js/lib/datatables-net/dataTables.buttons.min.js"></script>
<script src="../public/app/publico/js/lib/datatables-net/buttons.html5.min.js"></script>
<script src="../public/app/publico/js/lib/datatables-net/buttons.print.min.js"></script>

<!-- Bibliotecas para exportación -->
<script src="../public/app/publico/js/lib/jszip/jszip.min.js"></script>
<script src="../public/app/publico/js/lib/pdfmake/pdfmake.min.js"></script>
<script src="../public/app/publico/js/lib/pdfmake/vfs_fonts.js"></script>

<!-- Sweet Alert -->
<script src="../public/sweet/js/sweetalert2.js"></script>
<script src="../public/sweet/js/sweet.js"></script>

<!-- Custom Scripts -->
<script src="../public/app/publico/js/lib/jqueryui/jquery-ui.min.js"></script> <!-- Si se usa jQuery UI -->
<script src="../public/app/publico/js/lib/lobipanel/lobipanel.min.js"></script>
<script src="../public/app/publico/js/lib/match-height/jquery.matchHeight.min.js"></script>
<script src="../public/loader/loader.js"></script>

<!-- Aplicación JS -->
<script src="../public/app/publico/js/app.js"></script>
<script src="../public/app/publico/js/lib/jquery-flex-label/jquery.flex.label.js"></script>

<!-- Inicialización -->
<script>
    $(function() {
        // Inicializar DataTables con botones de exportación
        $('#example').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: 'Exportar a PDF',
                    title: 'Datos exportados',
                    className: 'btn btn-danger',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Datos exportados',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    text: 'Enviar por correo',
                    className: 'btn btn-primary',
                    action: function (e, dt, node, config) {
                        enviarPorCorreo();
                    }
                }
            ],
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Registros del _START_ al _END_ de _TOTAL_",
                "sInfoEmpty": "Registros del 0 al 0 de 0 registros",
                "sInfoFiltered": "-",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }
            }
        });

        // Inicializar gráficos de Google
        google.charts.load('current', { packages: ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            const data = google.visualization.arrayToDataTable([
                ['Día', 'Valores'],
                ['Lun', 130],
                ['Mar', 180],
                ['Mié', 200],
                ['Jue', 175],
                ['Vie', 220]
            ]);

            const options = {
                title: 'Gráfica de Ejemplo',
                hAxis: { title: 'Días' },
                vAxis: { title: 'Valores' }
            };

            const chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    });

    // Función para enviar por correo
    function enviarPorCorreo() {
        Swal.fire({
            title: 'Enviar por correo',
            html: `
                <form id="emailForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico:</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Asunto:</label>
                        <input type="text" class="form-control" id="subject" value="Datos exportados" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje:</label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const email = document.getElementById('email').value;
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value;
                
                if (!email) {
                    Swal.showValidationMessage('Por favor ingrese un correo electrónico');
                    return false;
                }
                
                // Aquí iría la lógica para enviar el correo mediante AJAX
                return $.ajax({
                    url: '../controladores/enviar_correo.php',
                    type: 'POST',
                    data: {
                        email: email,
                        subject: subject,
                        message: message,
                        // Puedes pasar datos adicionales si es necesario
                        tipo: 'exportacion'
                    },
                    dataType: 'json'
                }).then(function(response) {
                    if (response.exito) {
                        return true;
                    } else {
                        Swal.showValidationMessage(response.mensaje || 'Error al enviar el correo');
                        return false;
                    }
                }).catch(function() {
                    Swal.showValidationMessage('Error en la conexión');
                    return false;
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('¡Enviado!', 'El correo ha sido enviado correctamente.', 'success');
            }
        });
    }

    // Flex Label Plugin
    $(document).ready(function() {
        $('.fl-flex-label').flexLabel();
    });
</script>

<!-- Footer HTML -->
<footer class="text-center mt-4">
    <p>&copy; 2025 Mi Sitio Web. Todos los derechos reservados.JCTV.</p>
</footer>