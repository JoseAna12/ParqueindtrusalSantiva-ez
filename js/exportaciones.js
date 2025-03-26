/**
 * Funciones de exportación y manipulación de documentos
 * Sistema de Parque Industrial Santiváñez
 * Versión: 2.0.0
 * Última actualización: 2025-03-20
 */

// Verificar que las bibliotecas necesarias estén cargadas
function checkAndLoadLibraries(callback) {
    let allLoaded = true;
    
    // Verificar jsPDF
    if (typeof window.jspdf === 'undefined') {
        loadJsPDF(() => {
            // Verificar si también necesitamos cargar autoTable
            if (typeof window.jspdf.jsPDF !== 'undefined' && 
                typeof window.jspdf.jsPDF.API.autoTable === 'undefined') {
                loadAutoTable(() => {
                    if (typeof callback === 'function') callback();
                });
            } else {
                if (typeof callback === 'function') callback();
            }
        });
        allLoaded = false;
        return false;
    }
    
    // Verificar autoTable para jsPDF
    if (typeof window.jspdf !== 'undefined' && 
        typeof window.jspdf.jsPDF !== 'undefined' && 
        typeof window.jspdf.jsPDF.API.autoTable === 'undefined') {
        loadAutoTable(() => {
            if (typeof callback === 'function') callback();
        });
        allLoaded = false;
        return false;
    }
    
    // Verificar SheetJS (librería Excel)
    if (typeof XLSX === 'undefined') {
        loadExcelLibrary(() => {
            if (typeof callback === 'function') callback();
        });
        allLoaded = false;
        return false;
    }
    
    return allLoaded;
}

// Cargar jsPDF
function loadJsPDF(callback) {
    showNotification('Cargando biblioteca PDF...', 'info');
    
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
    script.integrity = 'sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==';
    script.crossOrigin = 'anonymous';
    
    script.onload = function() {
        console.log('jsPDF cargado correctamente');
        showNotification('Biblioteca PDF cargada correctamente', 'success');
        if (typeof callback === 'function') setTimeout(callback, 500);
    };
    
    script.onerror = function() {
        console.error('Error al cargar jsPDF');
        showNotification('Error al cargar la biblioteca PDF. Verifique su conexión a Internet.', 'error');
    };
    
    document.head.appendChild(script);
}

// Cargar autoTable para jsPDF
function loadAutoTable(callback) {
    showNotification('Cargando plugin de tablas...', 'info');
    
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js';
    script.integrity = 'sha512-NzRGAMwaRdAJgu0VgiUO28UTdmZI/LfcCEJLjQ6CgTAibxDQoL/CI2u/hlDFDFG1xfKu9UfoA38JRdxBN+rhpA==';
    script.crossOrigin = 'anonymous';
    
    script.onload = function() {
        console.log('AutoTable cargado correctamente');
        showNotification('Plugin de tablas cargado correctamente', 'success');
        if (typeof callback === 'function') setTimeout(callback, 500);
    };
    
    script.onerror = function() {
        console.error('Error al cargar AutoTable');
        showNotification('Error al cargar plugin de tablas. Verifique su conexión a Internet.', 'error');
    };
    
    document.head.appendChild(script);
}

// Cargar librería Excel
function loadExcelLibrary(callback) {
    showNotification('Cargando biblioteca Excel...', 'info');
    
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
    script.integrity = 'sha512-r22gChDnGvBylk90+2e/ycr3RVrDi8DIOkIGNhJlKfuyQM4tIRAI062MaV8sfjQKYVGjOBaZBOA87z+IhZE9DA==';
    script.crossOrigin = 'anonymous';
    
    script.onload = function() {
        console.log('Biblioteca Excel cargada correctamente');
        showNotification('Biblioteca Excel cargada correctamente', 'success');
        if (typeof callback === 'function') setTimeout(callback, 500);
    };
    
    script.onerror = function() {
        console.error('Error al cargar la biblioteca Excel');
        showNotification('Error al cargar la biblioteca Excel. Verifique su conexión a Internet.', 'error');
    };
    
    document.head.appendChild(script);
}

/**
 * FUNCIONES PARA LA HOJA DE RUTA
 */

// Generar PDF de hoja de ruta con diseño exactamente igual al de la imagen
function generateRoutingPDF() {
    // Verificar bibliotecas
    if (!checkAndLoadLibraries(function() { generateRoutingPDF(); })) {
        return;
    }
    
    try {
        // Recoger datos del formulario
        const formData = collectRoutingFormData();
        
        // Crear PDF con el formato exacto del documento
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });
        
        // Configuración de fuentes
        doc.setFont('helvetica', 'bold');
        
        // Agregar encabezado principal
        doc.setFontSize(14);
        doc.text('DIRECTORIO MIXTO PARQUE INDUSTRIAL SANTIVÁÑEZ', 105, 15, { align: 'center' });
        
        // Recuadro para "HOJA DE RUTA"
        doc.setLineWidth(0.5);
        doc.rect(60, 20, 90, 10);
        doc.text('HOJA DE RUTA', 105, 26.5, { align: 'center' });
        
        // Añadir logotipo (placeholder rectangular para el logo)
        doc.setDrawColor(150, 150, 150);
        doc.setFillColor(240, 240, 240);
        doc.rect(170, 10, 25, 25, 'FD');
        
        // Marco principal que contiene todo el documento
        doc.setLineWidth(0.7);
        doc.setDrawColor(0, 0, 0);
        doc.rect(10, 35, 190, 240);
        
        // Líneas horizontales principales
        const mainSections = [
            { y: 35, text: 'REFERENCIA:', x: 15, height: 15 },
            { y: 50, text: 'PROCEDENCIA:', x: 15, height: 15 },
            { y: 65, text: 'N° DE REGISTRO:', x: 15, height: 15 }
        ];
        
        mainSections.forEach(section => {
            // Dibujar línea horizontal
            doc.setLineWidth(0.5);
            doc.line(10, section.y + section.height, 200, section.y + section.height);
            
            // Agregar texto
            doc.setFontSize(11);
            doc.text(section.text, section.x, section.y + 10);
            
            // Agregar valores de formData correspondientes
            doc.setFont('helvetica', 'normal');
            if (section.text === 'REFERENCIA:') {
                doc.text(formData.referencia, 50, section.y + 10);
            } else if (section.text === 'PROCEDENCIA:') {
                doc.text(formData.procedencia, 50, section.y + 10);
                
                // Agregar Int. y Ext.
                doc.setFont('helvetica', 'bold');
                doc.text('Int.:', 145, section.y + 10);
                doc.setFont('helvetica', 'normal');
                doc.text(formData.int, 155, section.y + 10);
                
                doc.setFont('helvetica', 'bold');
                doc.text('Ext.:', 170, section.y + 10);
                doc.setFont('helvetica', 'normal');
                doc.text(formData.ext, 180, section.y + 10);
            } else if (section.text === 'N° DE REGISTRO:') {
                doc.text(formData.num_registro, 50, section.y + 10);
                
                // Agregar FECHA:
                doc.setFont('helvetica', 'bold');
                doc.text('FECHA:', 100, section.y + 10);
                
                // Dibujar los tres recuadros para la fecha
                const dateBoxWidth = 20;
                const dateStart = 115;
                doc.rect(dateStart, section.y + 5, dateBoxWidth, 10);
                doc.rect(dateStart + dateBoxWidth, section.y + 5, dateBoxWidth, 10);
                doc.rect(dateStart + 2 * dateBoxWidth, section.y + 5, dateBoxWidth, 10);
                
                // Agregar valores de fecha
                doc.setFont('helvetica', 'normal');
                doc.text(formData.fecha_dia, dateStart + (dateBoxWidth / 2), section.y + 10, { align: 'center' });
                doc.text(formData.fecha_mes, dateStart + dateBoxWidth + (dateBoxWidth / 2), section.y + 10, { align: 'center' });
                doc.text(formData.fecha_anio, dateStart + 2 * dateBoxWidth + (dateBoxWidth / 2), section.y + 10, { align: 'center' });
            }
            doc.setFont('helvetica', 'bold');
        });
        
        // Dibujar las secciones para cada destinatario
        const destinatariosData = [
            { index: 1, y: 80, data: collectDestinarioData(1) },
            { index: 2, y: 140, data: collectDestinarioData(2) },
            { index: 3, y: 200, data: collectDestinarioData(3) }
        ];
        
        destinatariosData.forEach((destinatario, index) => {
            drawDestinatarioSection(doc, destinatario.y, destinatario.data, index === destinatariosData.length - 1);
        });
        
        // Fecha de generación en el pie de página
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        const fechaGeneracion = new Date().toLocaleDateString('es-ES');
        doc.text(`Documento generado el ${fechaGeneracion}`, 105, 285, { align: 'center' });
        
        // Guardar el PDF
        const filename = `Hoja_Ruta_${formData.num_registro || 'Nueva'}_${fechaGeneracion.replace(/\//g, '-')}.pdf`;
        doc.save(filename);
        
        showNotification('Hoja de ruta generada en PDF correctamente', 'success');
    } catch (error) {
        console.error('Error al generar hoja de ruta PDF:', error);
        showNotification('Error al generar hoja de ruta PDF: ' + error.message, 'error');
    }
}

// Dibujar sección de destinatario con todos sus componentes
function drawDestinatarioSection(doc, startY, data, isLast = false) {
    // Altura de la sección
    const sectionHeight = 60;
    
    // Línea horizontal para iniciar la sección
    doc.line(10, startY, 200, startY);
    
    // Sección de DESTINATARIO
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.text('DESTINATARIO:', 15, startY + 10);
    
    // Valor del destinatario
    doc.setFont('helvetica', 'normal');
    doc.text(data.destinatario, 55, startY + 10);
    
    // Sección de FECHA DE RECEPCIÓN
    const receptionBoxWidth = 60;
    const receptionBoxX = 140;
    
    doc.setFont('helvetica', 'bold');
    doc.text('FECHA DE RECEPCIÓN:', receptionBoxX + (receptionBoxWidth / 2), startY + 5, { align: 'center' });
    
    // Dibujar la cuadrícula para la fecha de recepción
    const cellWidth = receptionBoxWidth / 4;
    const cellHeight = 10;
    const headerY = startY + 5;
    const valuesY = startY + 15;
    
    // Diseño del recuadro completo
    doc.rect(receptionBoxX, startY, receptionBoxWidth, 30);
    
    // Línea horizontal que separa encabezados de valores
    doc.line(receptionBoxX, valuesY, receptionBoxX + receptionBoxWidth, valuesY);
    
    // Líneas verticales que dividen las celdas
    for (let i = 1; i < 4; i++) {
        doc.line(receptionBoxX + (i * cellWidth), headerY, receptionBoxX + (i * cellWidth), headerY + 2 * cellHeight);
    }
    
    // Añadir etiquetas de encabezados
    doc.setFontSize(8);
    doc.text('HORA', receptionBoxX + (cellWidth / 2), headerY + 4, { align: 'center' });
    doc.text('DIA', receptionBoxX + (cellWidth * 1.5), headerY + 4, { align: 'center' });
    doc.text('MES', receptionBoxX + (cellWidth * 2.5), headerY + 4, { align: 'center' });
    doc.text('AÑO', receptionBoxX + (cellWidth * 3.5), headerY + 4, { align: 'center' });
    
    // Añadir valores de recepción si existen
    doc.setFont('helvetica', 'normal');
    if (data.recepcion_hora) doc.text(data.recepcion_hora, receptionBoxX + (cellWidth / 2), valuesY + 7, { align: 'center' });
    if (data.recepcion_dia) doc.text(data.recepcion_dia, receptionBoxX + (cellWidth * 1.5), valuesY + 7, { align: 'center' });
    if (data.recepcion_mes) doc.text(data.recepcion_mes, receptionBoxX + (cellWidth * 2.5), valuesY + 7, { align: 'center' });
    if (data.recepcion_anio) doc.text(data.recepcion_anio, receptionBoxX + (cellWidth * 3.5), valuesY + 7, { align: 'center' });
    
    // Línea horizontal después del destinatario
    doc.line(10, startY + 15, 140, startY + 15);
    
    // Sección de ASUNTO
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.text('ASUNTO:', 15, startY + 25);
    
    // Valor del asunto (con líneas punteadas)
    doc.setFont('helvetica', 'normal');
    const asuntoLines = doc.splitTextToSize(data.asunto, 120);
    doc.text(asuntoLines, 55, startY + 25);
    
    // Añadir líneas punteadas para el asunto (3 líneas)
    for (let i = 0; i < 3; i++) {
        drawDottedLine(doc, 15, startY + 30 + (i * 5), 130, startY + 30 + (i * 5));
    }
    
    // Sección inferior con DERIVADO POR y FIRMA Y SELLO
    const bottomY = startY + 45;
    
    // Recuadros redondeados
    const radius = 5;
    
    // Recuadro izquierdo (DERIVADO POR)
    drawRoundedRect(doc, 15, bottomY, 60, 25, radius);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(9);
    doc.text('DERIVADO POR:', 45, bottomY + 5, { align: 'center' });
    doc.text('FIRMA Y SELLO:', 45, bottomY + 20, { align: 'center' });
    
    // Sección de FECHA y HORA de derivación
    const dateRowY = bottomY + 5;
    const hourColX = 90;
    const hourBoxWidth = 15;
    
    doc.setFont('helvetica', 'bold');
    doc.text('FECHA:', 85, dateRowY);
    doc.text('HORA', hourColX + (hourBoxWidth / 2), dateRowY - 3, { align: 'center' });
    
    // Caja para la hora
    doc.rect(hourColX, dateRowY - 8, hourBoxWidth, 10);
    
    // Valores para la hora
    if (data.derivacion_hora) {
        doc.setFont('helvetica', 'normal');
        doc.text(data.derivacion_hora, hourColX + (hourBoxWidth / 2), dateRowY, { align: 'center' });
    }
    
    // Cajas para la fecha (día, mes, año)
    const dateColX = 85;
    const dateBoxWidth = 15;
    const dateBoxesY = dateRowY + 5;
    
    // Dibujar cajas
    for (let i = 0; i < 3; i++) {
        doc.rect(dateColX + (i * dateBoxWidth), dateBoxesY, dateBoxWidth, 10);
    }
    
    // Etiquetas de DÍA, MES, AÑO
    doc.setFontSize(8);
    doc.text('DIA', dateColX + (dateBoxWidth / 2), dateBoxesY + 15, { align: 'center' });
    doc.text('MES', dateColX + (dateBoxWidth * 1.5), dateBoxesY + 15, { align: 'center' });
    doc.text('AÑO', dateColX + (dateBoxWidth * 2.5), dateBoxesY + 15, { align: 'center' });
    
    // Valores para la fecha
    if (data.derivacion_dia || data.derivacion_mes || data.derivacion_anio) {
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(9);
        if (data.derivacion_dia) doc.text(data.derivacion_dia, dateColX + (dateBoxWidth / 2), dateBoxesY + 7, { align: 'center' });
        if (data.derivacion_mes) doc.text(data.derivacion_mes, dateColX + (dateBoxWidth * 1.5), dateBoxesY + 7, { align: 'center' });
        if (data.derivacion_anio) doc.text(data.derivacion_anio, dateColX + (dateBoxWidth * 2.5), dateBoxesY + 7, { align: 'center' });
    }
    
    // Recuadro derecho para firmar y sello
    doc.setFontSize(9);
    doc.setFont('helvetica', 'bold');
    doc.text('FIRMAR Y SELLO:', 170, bottomY + 5, { align: 'center' });
    drawRoundedRect(doc, 140, bottomY + 7, 60, 25, radius);
    
    // Si no es la última sección, dibujar línea horizontal final
    if (!isLast) {
        doc.line(10, startY + sectionHeight, 200, startY + sectionHeight);
    }
}

// Función para dibujar líneas punteadas
function drawDottedLine(doc, x1, y, x2, y2) {
    const dashLen = 2;
    const gapLen = 2;
    let dashCount = Math.floor((x2 - x1) / (dashLen + gapLen));
    
    for (let i = 0; i < dashCount; i++) {
        let xStart = x1 + (i * (dashLen + gapLen));
        let xEnd = xStart + dashLen;
        
        doc.line(xStart, y, xEnd, y2);
    }
}

// Función para dibujar rectángulos con esquinas redondeadas
function drawRoundedRect(doc, x, y, width, height, radius) {
    const r = radius || 5;
    
    // Comenzar el trazado
    doc.roundedRect(x, y, width, height, r, r);
}

// Recoger todos los datos del formulario de hoja de ruta
function collectRoutingFormData() {
    return {
        referencia: document.getElementById('referencia').value || '',
        procedencia: document.getElementById('procedencia').value || '',
        num_registro: document.getElementById('num_registro').value || '',
        fecha_dia: document.getElementById('fecha_dia').value || '',
        fecha_mes: document.getElementById('fecha_mes').value || '',
        fecha_anio: document.getElementById('fecha_anio').value || '',
        int: document.getElementById('int').value || '',
        ext: document.getElementById('ext').value || ''
    };
}

// Recoger datos específicos de un destinatario por índice
function collectDestinarioData(index) {
    return {
        destinatario: document.getElementById(`destinatario${index}`).value || '',
        asunto: document.getElementById(`asunto${index}`).value || '',
        recepcion_hora: document.getElementById(`recepcion${index}_hora`) ? document.getElementById(`recepcion${index}_hora`).value : '',
        recepcion_dia: document.getElementById(`recepcion${index}_dia`) ? document.getElementById(`recepcion${index}_dia`).value : '',
        recepcion_mes: document.getElementById(`recepcion${index}_mes`) ? document.getElementById(`recepcion${index}_mes`).value : '',
        recepcion_anio: document.getElementById(`recepcion${index}_anio`) ? document.getElementById(`recepcion${index}_anio`).value : '',
        derivacion_hora: document.getElementById(`derivacion${index}_hora`) ? document.getElementById(`derivacion${index}_hora`).value : '',
        derivacion_dia: document.getElementById(`derivacion${index}_dia`) ? document.getElementById(`derivacion${index}_dia`).value : '',
        derivacion_mes: document.getElementById(`derivacion${index}_mes`) ? document.getElementById(`derivacion${index}_mes`).value : '',
        derivacion_anio: document.getElementById(`derivacion${index}_anio`) ? document.getElementById(`derivacion${index}_anio`).value : ''
    };
}

// Exportar hoja de ruta a Excel con formato mejorado
function exportRoutingToExcel() {
    // Verificar bibliotecas
    if (!checkAndLoadLibraries(function() { exportRoutingToExcel(); })) {
        return;
    }
    
    try {
        // Recoger datos del formulario
        const formData = collectRoutingFormData();
        
        // Recoger datos de los destinatarios
        const destinatario1 = collectDestinarioData(1);
        const destinatario2 = collectDestinarioData(2);
        const destinatario3 = collectDestinarioData(3);
        
        // Formatear fecha completa
        const fechaCompleta = `${formData.fecha_dia}/${formData.fecha_mes}/${formData.fecha_anio}`;
        
        // Crear estructura de datos para Excel con formato mejorado
        const data = [
            [{ v: 'DIRECTORIO MIXTO PARQUE INDUSTRIAL SANTIVÁÑEZ', s: { font: { bold: true, sz: 14 }, alignment: { horizontal: 'center' } } }],
            [{ v: 'HOJA DE RUTA', s: { font: { bold: true, sz: 12 }, alignment: { horizontal: 'center' }, border: { top: { style: 'thin' }, right: { style: 'thin' }, bottom: { style: 'thin' }, left: { style: 'thin' } } } }],
            [''],
            [{ v: 'REFERENCIA:', s: { font: { bold: true } } }, formData.referencia],
            [{ v: 'PROCEDENCIA:', s: { font: { bold: true } } }, formData.procedencia, { v: 'Int.:', s: { font: { bold: true } } }, formData.int, { v: 'Ext.:', s: { font: { bold: true } } }, formData.ext],
            [{ v: 'N° DE REGISTRO:', s: { font: { bold: true } } }, formData.num_registro, { v: 'FECHA:', s: { font: { bold: true } } }, fechaCompleta],
            [''],
            [{ v: 'DESTINATARIO 1:', s: { font: { bold: true } } }, destinatario1.destinatario],
            [{ v: 'FECHA DE RECEPCIÓN:', s: { font: { bold: true } } }, { v: 'HORA:', s: { font: { bold: true } } }, destinatario1.recepcion_hora, { v: 'DIA:', s: { font: { bold: true } } }, destinatario1.recepcion_dia, { v: 'MES:', s: { font: { bold: true } } }, destinatario1.recepcion_mes, { v: 'AÑO:', s: { font: { bold: true } } }, destinatario1.recepcion_anio],
            [{ v: 'ASUNTO:', s: { font: { bold: true } } }, destinatario1.asunto],
            [{ v: 'DERIVADO POR:', s: { font: { bold: true } } }, '', { v: 'FECHA/HORA:', s: { font: { bold: true } } }, `${destinatario1.derivacion_dia}/${destinatario1.derivacion_mes}/${destinatario1.derivacion_anio} ${destinatario1.derivacion_hora}`, { v: 'FIRMA Y SELLO:', s: { font: { bold: true } } }, ''],
            [''],
            [{ v: 'DESTINATARIO 2:', s: { font: { bold: true } } }, destinatario2.destinatario],
            [{ v: 'FECHA DE RECEPCIÓN:', s: { font: { bold: true } } }, { v: 'HORA:', s: { font: { bold: true } } }, destinatario2.recepcion_hora, { v: 'DIA:', s: { font: { bold: true } } }, destinatario2.recepcion_dia, { v: 'MES:', s: { font: { bold: true } } }, destinatario2.recepcion_mes, { v: 'AÑO:', s: { font: { bold: true } } }, destinatario2.recepcion_anio],
            [{ v: 'ASUNTO:', s: { font: { bold: true } } }, destinatario2.asunto],
            [{ v: 'DERIVADO POR:', s: { font: { bold: true } } }, '', { v: 'FECHA/HORA:', s: { font: { bold: true } } }, `${destinatario2.derivacion_dia}/${destinatario2.derivacion_mes}/${destinatario2.derivacion_anio} ${destinatario2.derivacion_hora}`, { v: 'FIRMA Y SELLO:', s: { font: { bold: true } } }, ''],
            [''],
            [{ v: 'DESTINATARIO 3:', s: { font: { bold: true } } }, destinatario3.destinatario],
            [{ v: 'FECHA DE RECEPCIÓN:', s: { font: { bold: true } } }, { v: 'HORA:', s: { font: { bold: true } } }, destinatario3.recepcion_hora, { v: 'DIA:', s: { font: { bold: true } } }, destinatario3.recepcion_dia, { v: 'MES:', s: { font: { bold: true } } }, destinatario3.recepcion_mes, { v: 'AÑO:', s: { font: { bold: true } } }, destinatario3.recepcion_anio],
            [{ v: 'ASUNTO:', s: { font: { bold: true } } }, destinatario3.asunto],
            [{ v: 'DERIVADO POR:', s: { font: { bold: true } } }, '', { v: 'FECHA/HORA:', s: { font: { bold: true } } }, `${destinatario3.derivacion_dia}/${destinatario3.derivacion_mes}/${destinatario3.derivacion_anio} ${destinatario3.derivacion_hora}`, { v: 'FIRMA Y SELLO:', s: { font: { bold: true } } }, ''],
            [''],
            [{ v: `Documento generado el ${new Date().toLocaleDateString('es-ES')}`, s: { alignment: { horizontal: 'center' }, font: { italic: true } } }]
        ];
        
        // Convertir datos en formato para XLSX
        const ws_data = [];
        data.forEach(row => {
            const new_row = [];
            row.forEach(cell => {
                if (typeof cell === 'object' && cell !== null) {
                    new_row.push(cell.v);
                } else {
                    new_row.push(cell);
                }
            });
            ws_data.push(new_row);
        });
        
        // Crear libro y hoja
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(ws_data);
        
        // Dar formato a celdas (configuración de anchos)
        ws['!cols'] = [
            { wch: 20 }, // Primera columna
            { wch: 30 }, // Segunda columna
            { wch: 15 }, // Tercera columna
            { wch: 15 }, // Cuarta columna
            { wch: 15 }, // Quinta columna
            { wch: 15 }  // Sexta columna
        ];
        
        // Añadir formatos y estilos (a través de la extensión de Excel)
        applyStylesToSheet(ws, data);
        
        // Añadir hoja al libro
        XLSX.utils.book_append_sheet(wb, ws, 'Hoja de Ruta');
        
        // Guardar archivo
        const filename = `Hoja_Ruta_${formData.num_registro || 'Nueva'}_${new Date().toLocaleDateString('es-ES').replace(/\//g, '-')}.xlsx`;
        XLSX.writeFile(wb, filename);
        
        showNotification('Hoja de ruta exportada a Excel correctamente', 'success');
    } catch (error) {
        console.error('Error al exportar hoja de ruta a Excel:', error);
        showNotification('Error al exportar hoja de ruta a Excel: ' + error.message, 'error');
    }
}

// Función auxiliar para aplicar estilos a la hoja de Excel
function applyStylesToSheet(ws, data) {
    if (!ws['!rows']) ws['!rows'] = [];
    
    // Aplicar altura a filas
    ws['!rows'][0] = { hpt: 30 }; // Altura para el título
    ws['!rows'][1] = { hpt: 25 }; // Altura para el subtítulo
    
    // Añadir información de estilos si la hoja los soporta
    if (!ws.Styles) ws.Styles = XLSX.utils.book_new().Styles;
    
    // Definir estilos básicos
    const styles = {
        title: { font: { bold: true, sz: 14 }, alignment: { horizontal: 'center', vertical: 'center' } },
        subtitle: { font: { bold: true, sz: 12 }, alignment: { horizontal: 'center', vertical: 'center' }, border: { top: true, right: true, bottom: true, left: true } },
        sectionHeader: { font: { bold: true, sz: 12 }, fill: { fgColor: { rgb: "DDDDDD" } } },
        header: { font: { bold: true } },
        centered: { alignment: { horizontal: 'center' } },
        leftAligned: { alignment: { horizontal: 'left' } },
        bordered: { border: { top: true, right: true, bottom: true, left: true } }
    };
    
    // Tratar de aplicar estilos básicos a las celdas principales
    try {
        // Aplicar estilos para la primera fila (título)
        setCellStyle(ws, 0, 0, styles.title);
        mergeCells(ws, 0, 0, 0, 5); // Combinar celdas para el título
        
        // Aplicar estilos para la segunda fila (subtítulo)
        setCellStyle(ws, 1, 0, styles.subtitle);
        mergeCells(ws, 1, 0, 1, 5); // Combinar celdas para el subtítulo
        
        // Aplicar estilos a encabezados de secciones
        for (let row = 3; row < 20; row++) {
            if (typeof ws[XLSX.utils.encode_cell({r: row, c: 0})] !== 'undefined') {
                const cellValue = ws[XLSX.utils.encode_cell({r: row, c: 0})].v;
                if (typeof cellValue === 'string' && cellValue.endsWith(':')) {
                    setCellStyle(ws, row, 0, styles.header);
                }
            }
        }
    } catch (e) {
        console.warn('No se pudieron aplicar todos los estilos al archivo Excel. Error:', e);
    }
}

// Funciones auxiliares para manejo de estilos en Excel (pueden ser limitadas según la implementación de SheetJS)
function setCellStyle(ws, row, col, style) {
    const cell = ws[XLSX.utils.encode_cell({r: row, c: col})];
    if (cell) {
        cell.s = style;
    }
}

function mergeCells(ws, startRow, startCol, endRow, endCol) {
    if (!ws['!merges']) ws['!merges'] = [];
    ws['!merges'].push({ s: {r: startRow, c: startCol}, e: {r: endRow, c: endCol} });
}

// Función para imprimir la hoja de ruta con vista previa mejorada
function printRoutingForm() {
    try {
        // Crear una ventana de impresión
        const printWindow = window.open('', '_blank');
        
        // Estilos CSS mejorados para la impresión
        const styleContent = `
            @page {
                size: A4;
                margin: 10mm;
            }
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f9f9f9;
            }
            .print-container {
                width: 21cm;
                min-height: 29.7cm;
                margin: 10mm auto;
                padding: 5mm;
                background-color: white;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                position: relative;
            }
            .print-header {
                text-align: center;
                margin-bottom: 5mm;
                position: relative;
            }
            .print-title {
                font-size: 14pt;
                font-weight: bold;
                margin: 0;
                padding: 5pt 0;
            }
            .print-subtitle {
                font-size: 12pt;
                font-weight: bold;
                border: 1px solid #000;
                padding: 2mm;
                width: 6cm;
                margin: 0 auto;
                text-align: center;
            }
            .logo {
                position: absolute;
                top: 0;
                right: 0;
                width: 25mm;
                height: auto;
            }
            .print-document {
                border: 1px solid #000;
                padding: 0;
            }
            .print-row {
                display: flex;
                border-bottom: 1px solid #000;
                min-height: 10mm;
            }
            .print-row:last-child {
                border-bottom: none;
            }
            .print-label {
                font-weight: bold;
                width: 35mm;
                padding: 2mm;
                display: flex;
                align-items: center;
            }
            .print-value {
                flex: 1;
                padding: 2mm;
                border-left: 1px solid #ddd;
            }
            .int-ext-container {
                display: flex;
                margin-left: auto;
                width: 80mm;
            }
            .int-ext-item {
                display: flex;
                align-items: center;
                margin-right: 5mm;
            }
            .int-ext-label {
                font-weight: bold;
                margin-right: 2mm;
            }
            .int-ext-value {
                border: 1px solid #ddd;
                padding: 1mm 3mm;
                min-width: 15mm;
            }
            .registro-fecha {
                display: flex;
            }
            .fecha-container {
                display: flex;
                margin-left: 20mm;
            }
            .fecha-label {
                font-weight: bold;
                margin-right: 2mm;
                display: flex;
                align-items: center;
            }
            .fecha-boxes {
                display: flex;
            }
            .fecha-box {
                border: 1px solid #000;
                width: 10mm;
                height: 8mm;
                margin-right: 1mm;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .destinatario-section {
                border-bottom: 1px solid #000;
            }
            .destinatario-row {
                display: flex;
                min-height: 10mm;
            }
            .recepcion-container {
                width: 60mm;
                border-left: 1px solid #000;
            }
            .recepcion-title {
                font-weight: bold;
                font-size: 8pt;
                text-align: center;
                padding: 1mm;
                border-bottom: 1px solid #000;
            }
            .recepcion-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                text-align: center;
            }
            .recepcion-header {
                font-weight: bold;
                font-size: 7pt;
                padding: 1mm;
                border-right: 1px solid #000;
            }
            .recepcion-header:last-child {
                border-right: none;
            }
            .recepcion-value {
                height: 8mm;
                display: flex;
                align-items: center;
                justify-content: center;
                border-right: 1px solid #000;
            }
            .recepcion-value:last-child {
                border-right: none;
            }
            .asunto-container {
                padding: 2mm;
                min-height: 15mm;
                border-top: 1px solid #000;
                border-bottom: 1px solid #000;
            }
            .asunto-label {
                font-weight: bold;
                display: inline-block;
                width: 35mm;
                vertical-align: top;
            }
            .asunto-content {
                display: inline-block;
                width: calc(100% - 40mm);
            }
            .dotted-line {
                border-bottom: 1px dotted #000;
                height: 5mm;
            }
            .bottom-row {
                display: flex;
                min-height: 30mm;
            }
            .derivado-container {
                width: 60mm;
                padding: 2mm;
                position: relative;
            }
            .derivado-box {
                border: 1px solid #000;
                border-radius: 5mm;
                height: 25mm;
                margin-top: 3mm;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .fecha-derivacion {
                width: 60mm;
                padding: 2mm;
                position: relative;
            }
            .fecha-title {
                font-weight: bold;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .hora-container {
                display: flex;
                align-items: center;
            }
            .hora-label {
                font-weight: bold;
                font-size: 8pt;
                margin-right: 2mm;
            }
            .hora-value {
                border: 1px solid #000;
                width: 15mm;
                height: 8mm;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .fecha-row {
                display: flex;
                margin-top: 5mm;
            }
            .fecha-values {
                display: flex;
            }
            .fecha-value {
                border: 1px solid #000;
                width: 15mm;
                height: 8mm;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 1mm;
            }
            .fecha-labels {
                display: flex;
                margin-top: 1mm;
            }
            .fecha-label-item {
                width: 15mm;
                text-align: center;
                font-size: 7pt;
                margin-right: 1mm;
            }
            .firma-container {
                width: 70mm;
                padding: 2mm;
            }
            .firma-title {
                font-weight: bold;
                text-align: center;
            }
            .firma-box {
                border: 1px solid #000;
                border-radius: 5mm;
                height: 25mm;
                margin-top: 3mm;
            }
            .footer {
                margin-top: 10mm;
                text-align: center;
                font-size: 8pt;
                color: #666;
            }
            .print-actions {
                text-align: center;
                margin-top: 5mm;
                padding: 3mm;
            }
            .print-button {
                background-color: #4361ee;
                color: white;
                border: none;
                padding: 3mm 5mm;
                font-size: 10pt;
                cursor: pointer;
                border-radius: 2mm;
            }
            .print-button:hover {
                background-color: #3a56d4;
            }
            @media print {
                body {
                    background-color: white;
                }
                .print-container {
                    margin: 0;
                    padding: 0;
                    box-shadow: none;
                    width: 100%;
                }
                .print-actions {
                    display: none;
                }
            }
        `;
        
        // Obtener datos del formulario
        const formData = collectRoutingFormData();
        const destinatario1 = collectDestinarioData(1);
        const destinatario2 = collectDestinarioData(2);
        const destinatario3 = collectDestinarioData(3);
        
        // Obtener la fecha actual para mostrarla en el pie de página
        const fechaGeneracion = new Date().toLocaleDateString('es-ES');
        
        // Contenido HTML de la página de impresión con diseño mejorado
        const htmlContent = `
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Hoja de Ruta - Parque Industrial Santiváñez</title>
                <style>${styleContent}</style>
            </head>
            <body>
                <div class="print-container">
                    <div class="print-header">
                        <h1 class="print-title">DIRECTORIO MIXTO PARQUE INDUSTRIAL SANTIVÁÑEZ</h1>
                        <div class="print-subtitle">HOJA DE RUTA</div>
                        <img src="../public/img-inicio/ari.png" alt="Logo Parque Industrial" class="logo">
                    </div>
                    
                    <div class="print-document">
                        <!-- Referencia -->
                        <div class="print-row">
                            <div class="print-label">REFERENCIA:</div>
                            <div class="print-value">${formData.referencia}</div>
                        </div>
                        
                        <!-- Procedencia -->
                        <div class="print-row">
                            <div class="print-label">PROCEDENCIA:</div>
                            <div class="print-value">
                                ${formData.procedencia}
                                <div class="int-ext-container">
                                    <div class="int-ext-item">
                                        <div class="int-ext-label">Int.:</div>
                                        <div class="int-ext-value">${formData.int}</div>
                                    </div>
                                    <div class="int-ext-item">
                                        <div class="int-ext-label">Ext.:</div>
                                        <div class="int-ext-value">${formData.ext}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nº de Registro y Fecha -->
                        <div class="print-row">
                            <div class="print-label">N° DE REGISTRO:</div>
                            <div class="print-value registro-fecha">
                                ${formData.num_registro}
                                <div class="fecha-container">
                                    <div class="fecha-label">FECHA:</div>
                                    <div class="fecha-boxes">
                                        <div class="fecha-box">${formData.fecha_dia}</div>
                                        <div class="fecha-box">${formData.fecha_mes}</div>
                                        <div class="fecha-box">${formData.fecha_anio}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Primer destinatario -->
                        <div class="destinatario-section">
                            <div class="destinatario-row">
                                <div class="print-label">DESTINATARIO:</div>
                                <div class="print-value">${destinatario1.destinatario}</div>
                                <div class="recepcion-container">
                                    <div class="recepcion-title">FECHA DE RECEPCIÓN:</div>
                                    <div class="recepcion-grid">
                                        <div class="recepcion-header">HORA</div>
                                        <div class="recepcion-header">DIA</div>
                                        <div class="recepcion-header">MES</div>
                                        <div class="recepcion-header">AÑO</div>
                                        <div class="recepcion-value">${destinatario1.recepcion_hora}</div>
                                        <div class="recepcion-value">${destinatario1.recepcion_dia}</div>
                                        <div class="recepcion-value">${destinatario1.recepcion_mes}</div>
                                        <div class="recepcion-value">${destinatario1.recepcion_anio}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="asunto-container">
                                <div class="asunto-label">ASUNTO:</div>
                                <div class="asunto-content">
                                    ${destinatario1.asunto}
                                    <div class="dotted-line"></div>
                                    <div class="dotted-line"></div>
                                    <div class="dotted-line"></div>
                                </div>
                            </div>
                            
                            <div class="bottom-row">
                                <div class="derivado-container">
                                    <div class="derivado-title">DERIVADO POR:</div>
                                    <div class="derivado-box">
                                        <div class="firma-title">FIRMA Y SELLO</div>
                                    </div>
                                </div>
                                
                                <div class="fecha-derivacion">
                                    <div class="fecha-title">
                                        FECHA:
                                        <div class="hora-container">
                                            <div class="hora-label">HORA</div>
                                            <div class="hora-value">${destinatario1.derivacion_hora}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="fecha-row">
                                        <div class="fecha-values">
                                            <div class="fecha-value">${destinatario1.derivacion_dia}</div>
                                            <div class="fecha-value">${destinatario1.derivacion_mes}</div>
                                            <div class="fecha-value">${destinatario1.derivacion_anio}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="fecha-labels">
                                        <div class="fecha-label-item">DIA</div>
                                        <div class="fecha-label-item">MES</div>
                                        <div class="fecha-label-item">AÑO</div>
                                    </div>
                                </div>
                                
                                <div class="firma-container">
                                    <div class="firma-title">FIRMAR Y SELLO:</div>
                                    <div class="firma-box"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Segundo destinatario (estructura repetida) -->
                        <div class="destinatario-section">
                            <div class="destinatario-row">
                                <div class="print-label">DESTINATARIO:</div>
                                <div class="print-value">${destinatario2.destinatario}</div>
                                <div class="recepcion-container">
                                    <div class="recepcion-title">FECHA DE RECEPCIÓN:</div>
                                    <div class="recepcion-grid">
                                        <div class="recepcion-header">HORA</div>
                                        <div class="recepcion-header">DIA</div>
                                        <div class="recepcion-header">MES</div>
                                        <div class="recepcion-header">AÑO</div>
                                        <div class="recepcion-value">${destinatario2.recepcion_hora}</div>
                                        <div class="recepcion-value">${destinatario2.recepcion_dia}</div>
                                        <div class="recepcion-value">${destinatario2.recepcion_mes}</div>
                                        <div class="recepcion-value">${destinatario2.recepcion_anio}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="asunto-container">
                                <div class="asunto-label">ASUNTO:</div>
                                <div class="asunto-content">
                                    ${destinatario2.asunto}
                                    <div class="dotted-line"></div>
                                    <div class="dotted-line"></div>
                                    <div class="dotted-line"></div>
                                </div>
                            </div>
                            
                            <div class="bottom-row">
                                <div class="derivado-container">
                                    <div class="derivado-title">DERIVADO POR:</div>
                                    <div class="derivado-box">
                                        <div class="firma-title">FIRMA Y SELLO</div>
                                    </div>
                                </div>
                                
                                <div class="fecha-derivacion">
                                    <div class="fecha-title">
                                        FECHA:
                                        <div class="hora-container">
                                            <div class="hora-label">HORA</div>
                                            <div class="hora-value">${destinatario2.derivacion_hora}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="fecha-row">
                                        <div class="fecha-values">
                                            <div class="fecha-value">${destinatario2.derivacion_dia}</div>
                                            <div class="fecha-value">${destinatario2.derivacion_mes}</div>
                                            <div class="fecha-value">${destinatario2.derivacion_anio}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="fecha-labels">
                                        <div class="fecha-label-item">DIA</div>
                                        <div class="fecha-label-item">MES</div>
                                        <div class="fecha-label-item">AÑO</div>
                                    </div>
                                </div>
                                
                                <div class="firma-container">
                                    <div class="firma-title">FIRMAR Y SELLO:</div>
                                    <div class="firma-box"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tercer destinatario (estructura repetida) -->
                        <div class="destinatario-section">
                            <div class="destinatario-row">
                                <div class="print-label">DESTINATARIO:</div>
                                <div class="print-value">${destinatario3.destinatario}</div>
                                <div class="recepcion-container">
                                    <div class="recepcion-title">FECHA DE RECEPCIÓN:</div>
                                    <div class="recepcion-grid">
                                        <div class="recepcion-header">HORA</div>
                                        <div class="recepcion-header">DIA</div>
                                        <div class="recepcion-header">MES</div>
                                        <div class="recepcion-header">AÑO</div>
                                        <div class="recepcion-value">${destinatario3.recepcion_hora}</div>
                                        <div class="recepcion-value">${destinatario3.recepcion_dia}</div>
                                        <div class="recepcion-value">${destinatario3.recepcion_mes}</div>
                                        <div class="recepcion-value">${destinatario3.recepcion_anio}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="asunto-container">
                                <div class="asunto-label">ASUNTO:</div>
                                <div class="asunto-content">
                                    ${destinatario3.asunto}
                                    <div class="dotted-line"></div>
                                    <div class="dotted-line"></div>
                                    <div class="dotted-line"></div>
                                </div>
                            </div>
                            
                            <div class="bottom-row">
                                <div class="derivado-container">
                                    <div class="derivado-title">DERIVADO POR:</div>
                                    <div class="derivado-box">
                                        <div class="firma-title">FIRMA Y SELLO</div>
                                    </div>
                                </div>
                                
                                <div class="fecha-derivacion">
                                    <div class="fecha-title">
                                        FECHA:
                                        <div class="hora-container">
                                            <div class="hora-label">HORA</div>
                                            <div class="hora-value">${destinatario3.derivacion_hora}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="fecha-row">
                                        <div class="fecha-values">
                                            <div class="fecha-value">${destinatario3.derivacion_dia}</div>
                                            <div class="fecha-value">${destinatario3.derivacion_mes}</div>
                                            <div class="fecha-value">${destinatario3.derivacion_anio}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="fecha-labels">
                                        <div class="fecha-label-item">DIA</div>
                                        <div class="fecha-label-item">MES</div>
                                        <div class="fecha-label-item">AÑO</div>
                                    </div>
                                </div>
                                
                                <div class="firma-container">
                                    <div class="firma-title">FIRMAR Y SELLO:</div>
                                    <div class="firma-box"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="footer">
                        Documento generado el ${fechaGeneracion}
                    </div>
                    
                    <div class="print-actions">
                        <button onclick="window.print();" class="print-button">
                            <i class="fas fa-print"></i> Imprimir ahora
                        </button>
                    </div>
                </div>
            </body>
            </html>
        `;
        
        // Escribir el contenido en la nueva ventana
        printWindow.document.open();
        printWindow.document.write(htmlContent);
        printWindow.document.close();
        
        // Dar tiempo a que cargue el contenido antes de mostrar el diálogo de impresión
        setTimeout(() => {
            printWindow.focus();
            // Mostrar notificación
            showNotification('Preparando documento para impresión...', 'info');
        }, 1000);
        
    } catch (error) {
        console.error('Error al preparar impresión:', error);
        showNotification('Error al preparar impresión: ' + error.message, 'error');
    }
}

// Función para enviar por correo con Outlook
function sendEmail() {
    try {
        // Obtener datos del formulario
        const formData = collectRoutingFormData();
        const destinatario1 = collectDestinarioData(1);
        
        // Crear el asunto del correo
        const mailSubject = `Hoja de Ruta - ${formData.referencia || 'Nueva'} - ${formData.num_registro || 'Sin registro'}`;
        
        // Crear el cuerpo del correo con mejor formato
        const mailBody = `
Estimado/a,

Adjunto la Hoja de Ruta con la siguiente información:

* REFERENCIA: ${formData.referencia || '[Sin referencia]'}
* N° DE REGISTRO: ${formData.num_registro || '[Sin registro]'}
* PROCEDENCIA: ${formData.procedencia || '[Sin procedencia]'}
* DESTINATARIO: ${destinatario1.destinatario || '[Sin destinatario]'}
* ASUNTO: ${destinatario1.asunto || '[Sin asunto]'}

Por favor, proceder según corresponda.

Saludos cordiales,
Sistema de Gestión - Parque Industrial Santiváñez
        `;
        
        // Crear URL para abrir cliente de correo
        const mailtoUrl = `mailto:?subject=${encodeURIComponent(mailSubject)}&body=${encodeURIComponent(mailBody)}`;
        
        // Abrir cliente de correo predeterminado
        window.location.href = mailtoUrl;
        
        // Verificar bibliotecas para generar el PDF adjunto
        checkAndLoadLibraries(function() {
            // Generar el PDF para que el usuario lo adjunte manualmente
            generateRoutingPDF();
            
            // Mostrar instrucciones
            setTimeout(function() {
                showNotification('Se ha abierto el cliente de correo. Por favor, adjunte manualmente el archivo PDF generado.', 'info', 8000);
            }, 1500);
        });
    } catch (error) {
        console.error('Error al preparar correo:', error);
        showNotification('Error al preparar correo: ' + error.message, 'error');
    }
}

/**
 * FUNCIONES DE MANTENIMIENTO
 */

// Exportar formulario de mantenimiento a PDF con mejor formato
function generateMaintenancePDF() {
    // Verificar bibliotecas
    if (!checkAndLoadLibraries(function() { generateMaintenancePDF(); })) {
        return;
    }
    
    try {
        // Recoger datos del formulario
        const formData = {
            nombre_planta: document.getElementById('nombre_planta').value || '[Sin nombre]',
            ubicacion: document.getElementById('ubicacion').value || '[Sin ubicación]',
            codigo: document.getElementById('codigo').value || '[Sin código]',
            fecha: document.getElementById('fecha_mantenimiento').value || new Date().toISOString().split('T')[0],
            tipo: document.getElementById('tipo_mantenimiento').value || 'No especificado',
            responsable: document.getElementById('responsable').value || '[Sin responsable]',
            descripcion: document.getElementById('descripcion_actividad').value || 'No hay descripción disponible',
            materiales: document.getElementById('materiales').value || 'No especificados',
            observaciones: document.getElementById('observaciones').value || 'Sin observaciones',
            estado: document.getElementById('estado_salud').value || 'No evaluado',
            proximo: document.getElementById('fecha_proximo').value || 'No programado',
            firma: document.getElementById('firma').value || ''
        };
        
        // Crear PDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });
        
        // Añadir un fondo sutil
        doc.setFillColor(250, 250, 250);
        doc.rect(0, 0, doc.internal.pageSize.width, doc.internal.pageSize.height, 'F');
        
        // Configurar encabezado con diseño mejorado
        doc.setDrawColor(70, 130, 180); // Azul acero
        doc.setFillColor(70, 130, 180);
        doc.rect(0, 0, doc.internal.pageSize.width, 35, 'F');
        
        doc.setTextColor(255, 255, 255);
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(18);
        doc.text('SISTEMA DE MANTENIMIENTO DE PLANTAS', doc.internal.pageSize.width / 2, 15, { align: 'center' });
        doc.setFontSize(14);
        doc.text('PARQUE INDUSTRIAL SANTIVÁÑEZ', doc.internal.pageSize.width / 2, 25, { align: 'center' });
        
        // Rectángulo para el título del reporte
        doc.setFillColor(240, 240, 240);
        doc.setDrawColor(200, 200, 200);
        doc.roundedRect(10, 40, doc.internal.pageSize.width - 20, 10, 2, 2, 'FD');
        
        // Título del documento
        doc.setTextColor(70, 130, 180);
        doc.setFontSize(14);
        doc.text('REPORTE DE MANTENIMIENTO', doc.internal.pageSize.width / 2, 47, { align: 'center' });
        
        // Información principal en formato de tabla (con colores mejorados)
        doc.setTextColor(0, 0, 0);
        const datos = [
            ['Nombre de la planta:', formData.nombre_planta],
            ['Ubicación:', formData.ubicacion],
            ['Código de planta:', formData.codigo],
            ['Fecha:', formData.fecha],
            ['Tipo de mantenimiento:', formData.tipo],
            ['Responsable:', formData.responsable]
        ];
        
        doc.autoTable({
            startY: 55,
            body: datos,
            theme: 'grid',
            styles: {
                font: 'helvetica',
                fontSize: 10,
                cellPadding: 5
            },
            columnStyles: {
                0: { 
                    fontStyle: 'bold', 
                    cellWidth: 60,
                    fillColor: [240, 240, 240]
                }
            },
            alternateRowStyles: {
                fillColor: [249, 249, 249]
            },
            headStyles: {
                fillColor: [70, 130, 180],
                textColor: [255, 255, 255]
            }
        });
        
        // Descripción de la actividad con sección destacada
        let currentY = doc.lastAutoTable.finalY + 10;
        
        // Título de sección
        doc.setFillColor(70, 130, 180, 0.1);
        doc.setDrawColor(70, 130, 180);
        doc.roundedRect(10, currentY - 5, doc.internal.pageSize.width - 20, 8, 1, 1, 'FD');
        
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.setTextColor(70, 130, 180);
        doc.text('Descripción de la Actividad:', 14, currentY);
        currentY += 8;
        
        // Contenido
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);
        const descripcionSplit = doc.splitTextToSize(formData.descripcion, 180);
        doc.text(descripcionSplit, 14, currentY);
        currentY += (descripcionSplit.length * 5) + 10;
        
        // Materiales utilizados
        // Título de sección
        doc.setFillColor(70, 130, 180, 0.1);
        doc.setDrawColor(70, 130, 180);
        doc.roundedRect(10, currentY - 5, doc.internal.pageSize.width - 20, 8, 1, 1, 'FD');
        
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.setTextColor(70, 130, 180);
        doc.text('Materiales utilizados:', 14, currentY);
        currentY += 8;
        
        // Contenido
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);
        const materialesSplit = doc.splitTextToSize(formData.materiales, 180);
        doc.text(materialesSplit, 14, currentY);
        currentY += (materialesSplit.length * 5) + 10;
        
        // Observaciones
        // Título de sección
        doc.setFillColor(70, 130, 180, 0.1);
        doc.setDrawColor(70, 130, 180);
        doc.roundedRect(10, currentY - 5, doc.internal.pageSize.width - 20, 8, 1, 1, 'FD');
        
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.setTextColor(70, 130, 180);
        doc.text('Observaciones:', 14, currentY);
        currentY += 8;
        
        // Contenido
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);
        const observacionesSplit = doc.splitTextToSize(formData.observaciones, 180);
        doc.text(observacionesSplit, 14, currentY);
        currentY += (observacionesSplit.length * 5) + 15;
        
        // Información adicional con formato condicional según el estado
        const infoAdicional = [
            ['Estado de Salud:', formData.estado],
            ['Próximo Mantenimiento:', formData.proximo]
        ];
        
        doc.autoTable({
            startY: currentY,
            body: infoAdicional,
            theme: 'grid',
            styles: {
                font: 'helvetica',
                fontSize: 10,
                cellPadding: 5
            },
            columnStyles: {
                0: { 
                    fontStyle: 'bold', 
                    cellWidth: 60,
                    fillColor: [240, 240, 240]
                }
            },
            didDrawCell: function(data) {
                // Colorear la celda de estado según su valor
                if (data.section === 'body' && data.row.index === 0 && data.column.index === 1) {
                    const estado = data.cell.raw.toLowerCase();
                    let fillColor;
                    
                    if (estado.includes('excelente')) {
                        fillColor = [56, 176, 0, 0.2]; // Verde claro
                    } else if (estado.includes('bueno')) {
                        fillColor = [76, 201, 240, 0.2]; // Azul claro
                    } else if (estado.includes('regular')) {
                        fillColor = [244, 140, 6, 0.2]; // Naranja claro
                    } else if (estado.includes('malo') || estado.includes('crítico')) {
                        fillColor = [208, 0, 0, 0.2]; // Rojo claro
                    }
                    
                    if (fillColor) {
                        doc.setFillColor(fillColor[0], fillColor[1], fillColor[2], fillColor[3]);
                        doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'F');
                        doc.setTextColor(0, 0, 0);
                        doc.text(data.cell.raw, data.cell.x + data.cell.width / 2, data.cell.y + data.cell.height / 2, {
                            align: 'center',
                            baseline: 'middle'
                        });
                    }
                }
            }
        });
        
        // Firma con diseño mejorado
        currentY = doc.lastAutoTable.finalY + 20;
        
        // Rectángulo para la firma
        doc.setDrawColor(120, 120, 120);
        doc.setFillColor(250, 250, 250);
        doc.roundedRect(50, currentY - 15, 110, 25, 3, 3, 'FD');
        
        // Línea de firma
        doc.setDrawColor(70, 130, 180);
        doc.setLineWidth(0.5);
        doc.line(60, currentY, 150, currentY);
        
        // Etiqueta de firma
        doc.setFontSize(10);
        doc.setTextColor(100, 100, 100);
        doc.text('Firma del Responsable', doc.internal.pageSize.width / 2, currentY + 5, { align: 'center' });
        
        // Si hay texto de firma, agregarlo
        if (formData.firma) {
            doc.setFontSize(12);
            doc.setTextColor(0, 0, 0);
            doc.text(formData.firma, doc.internal.pageSize.width / 2, currentY - 5, { align: 'center' });
        }
        
        // Añadir pie de página con código QR simulado y fecha
        currentY = doc.internal.pageSize.height - 20;
        
        // Rectángulo para el pie de página
        doc.setFillColor(240, 240, 240);
        doc.rect(0, currentY - 5, doc.internal.pageSize.width, 25, 'F');
        
        // Simulación de código QR (pequeño cuadrado negro)
        doc.setFillColor(0, 0, 0);
        doc.roundedRect(10, currentY - 3, 15, 15, 1, 1, 'F');
        
        // Fecha de generación y código de referencia
        const fechaActual = new Date().toLocaleDateString('es-ES');
        doc.setFontSize(8);
        doc.setTextColor(100, 100, 100);
        doc.text(`Documento generado el ${fechaActual} | Ref: MANT-${formData.codigo}-${formData.fecha.replace(/-/g, '')}`, 35, currentY + 5);
        
        // Guardar el PDF con nombre elaborado
        const filename = `Mantenimiento_${formData.codigo}_${formData.fecha}.pdf`;
        doc.save(filename);
        
        showNotification('Documento PDF generado correctamente', 'success');
    } catch (error) {
        console.error('Error al generar PDF:', error);
        showNotification('Error al generar PDF: ' + error.message, 'error');
    }
}

// Exportar formulario de mantenimiento a Excel con formato mejorado
function exportMaintenanceToExcel() {
    // Verificar bibliotecas
    if (!checkAndLoadLibraries(function() { exportMaintenanceToExcel(); })) {
        return;
    }
    
    try {
        // Recoger datos del formulario
        const formData = {
            nombre_planta: document.getElementById('nombre_planta').value || '[Sin nombre]',
            ubicacion: document.getElementById('ubicacion').value || '[Sin ubicación]',
            codigo: document.getElementById('codigo').value || '[Sin código]',
            fecha: document.getElementById('fecha_mantenimiento').value || new Date().toISOString().split('T')[0],
            tipo: document.getElementById('tipo_mantenimiento').value || 'No especificado',
            responsable: document.getElementById('responsable').value || '[Sin responsable]',
            descripcion: document.getElementById('descripcion_actividad').value || 'No hay descripción disponible',
            materiales: document.getElementById('materiales').value || 'No especificados',
            observaciones: document.getElementById('observaciones').value || 'Sin observaciones',
            estado: document.getElementById('estado_salud').value || 'No evaluado',
            proximo: document.getElementById('fecha_proximo').value || 'No programado',
            firma: document.getElementById('firma').value || ''
        };
        
        // Crear estructura de datos para Excel con formato mejorado
        const data = [
            [{ v: 'SISTEMA DE MANTENIMIENTO DE PLANTAS - PARQUE INDUSTRIAL SANTIVÁÑEZ', s: { font: { bold: true, sz: 14 }, alignment: { horizontal: 'center' } } }],
            [{ v: 'REPORTE DE MANTENIMIENTO', s: { font: { bold: true, sz: 12 }, alignment: { horizontal: 'center' } } }],
            [''],
            [{ v: 'DATOS GENERALES', s: { font: { bold: true, sz: 12 }, fill: { fgColor: { rgb: "4361EE" } }, font: { color: { rgb: "FFFFFF" } } } }],
            [{ v: 'Nombre de la planta:', s: { font: { bold: true } } }, formData.nombre_planta],
            [{ v: 'Ubicación:', s: { font: { bold: true } } }, formData.ubicacion],
            [{ v: 'Código de planta:', s: { font: { bold: true } } }, formData.codigo],
            [{ v: 'Fecha:', s: { font: { bold: true } } }, formData.fecha],
            [{ v: 'Tipo de mantenimiento:', s: { font: { bold: true } } }, formData.tipo],
            [{ v: 'Responsable:', s: { font: { bold: true } } }, formData.responsable],
            [''],
            [{ v: 'DETALLES DE LA ACTIVIDAD', s: { font: { bold: true, sz: 12 }, fill: { fgColor: { rgb: "4361EE" } }, font: { color: { rgb: "FFFFFF" } } } }],
            [{ v: 'Descripción:', s: { font: { bold: true } } }, formData.descripcion],
            [{ v: 'Materiales utilizados:', s: { font: { bold: true } } }, formData.materiales],
            [{ v: 'Observaciones:', s: { font: { bold: true } } }, formData.observaciones],
            [''],
            [{ v: 'INFORMACIÓN ADICIONAL', s: { font: { bold: true, sz: 12 }, fill: { fgColor: { rgb: "4361EE" } }, font: { color: { rgb: "FFFFFF" } } } }],
            [{ v: 'Estado de Salud:', s: { font: { bold: true } } }, { v: formData.estado, s: getEstadoStyle(formData.estado) }],
            [{ v: 'Próximo Mantenimiento:', s: { font: { bold: true } } }, formData.proximo],
            [{ v: 'Firma del Responsable:', s: { font: { bold: true } } }, formData.firma],
            [''],
            [{ v: `Documento generado el ${new Date().toLocaleDateString('es-ES')}`, s: { font: { italic: true }, alignment: { horizontal: 'center' } } }]
        ];
        
        // Función para obtener estilo según estado
        function getEstadoStyle(estado) {
            const estadoLower = estado.toLowerCase();
            let fillColor = "FFFFFF"; // Blanco por defecto
            
            if (estadoLower.includes('excelente')) {
                fillColor = "DCFFD6"; // Verde claro
            } else if (estadoLower.includes('bueno')) {
                fillColor = "D6EEFF"; // Azul claro
            } else if (estadoLower.includes('regular')) {
                fillColor = "FFECD6"; // Naranja claro
            } else if (estadoLower.includes('malo') || estadoLower.includes('crítico')) {
                fillColor = "FFD6D6"; // Rojo claro
            }
            
            return { fill: { fgColor: { rgb: fillColor } } };
        }
        
        // Convertir datos en formato para XLSX
        const ws_data = [];
        data.forEach(row => {
            const new_row = [];
            row.forEach(cell => {
                if (typeof cell === 'object' && cell !== null) {
                    new_row.push(cell.v);
                } else {
                    new_row.push(cell);
                }
            });
            ws_data.push(new_row);
        });
        
        // Crear libro y hoja
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(ws_data);
        
        // Dar formato a celdas
        ws['!cols'] = [{ wch: 25 }, { wch: 50 }];
        
        // Añadir formatos y estilos (a través de la extensión de Excel)
        applyStylesToSheet(ws, data);
        
        // Añadir hoja al libro
        XLSX.utils.book_append_sheet(wb, ws, 'Mantenimiento');
        
        // Guardar archivo
        const filename = `Mantenimiento_${formData.codigo}_${formData.fecha}.xlsx`;
        XLSX.writeFile(wb, filename);
        
        showNotification('Documento Excel generado correctamente', 'success');
    } catch (error) {
        console.error('Error al generar Excel:', error);
        showNotification('Error al generar Excel: ' + error.message, 'error');
    }
}

/**
 * FUNCIONES DE HISTORIAL DE MANTENIMIENTO
 */

// Exportar historial de mantenimiento a PDF con formato mejorado
function generateMaintenanceHistoryPDF() {
    // Verificar bibliotecas
    if (!checkAndLoadLibraries(function() { generateMaintenanceHistoryPDF(); })) {
        return;
    }
    
    try {
        // Obtener los datos de la tabla
        const table = document.getElementById('maintenanceTable');
        const rows = table.querySelectorAll('tbody tr');
        
        // Crear arreglo para almacenar los datos
        const data = [];
        const headers = [];
        
        // Obtener los encabezados (excluyendo la columna de acciones)
        table.querySelectorAll('thead th').forEach((th, index) => {
            if (index < 8) { // Excluir la columna de acciones
                // Extraer solo el texto, no los íconos
                const headerText = th.textContent.trim();
                headers.push(headerText);
            }
        });
        
        // Obtener los datos de las filas
        rows.forEach(row => {
            const rowData = [];
            row.querySelectorAll('td').forEach((cell, index) => {
                if (index < 8) { // Excluir la columna de acciones
                    // Si es la columna de estado, extraer solo el texto
                    if (index === 7) {
                        const badge = cell.querySelector('.badge');
                        rowData.push(badge ? badge.textContent.trim() : cell.textContent.trim());
                    } else {
                        rowData.push(cell.textContent.trim());
                    }
                }
            });
            data.push(rowData);
        });
        
        // Crear PDF con mejor diseño
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });
        
        // Fondo
        doc.setFillColor(250, 250, 250);
        doc.rect(0, 0, doc.internal.pageSize.width, doc.internal.pageSize.height, 'F');
        
        // Encabezado con estilo
        doc.setDrawColor(70, 130, 180); // Azul acero
        doc.setFillColor(70, 130, 180);
        doc.rect(0, 0, doc.internal.pageSize.width, 30, 'F');
        
        doc.setTextColor(255, 255, 255);
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(18);
        doc.text('SISTEMA DE MANTENIMIENTO DE PLANTAS', doc.internal.pageSize.width / 2, 15, { align: 'center' });
        doc.setFontSize(14);
        doc.text('PARQUE INDUSTRIAL SANTIVÁÑEZ', doc.internal.pageSize.width / 2, 25, { align: 'center' });
        
        // Título del documento con diseño
        doc.setFillColor(240, 240, 240);
        doc.setDrawColor(200, 200, 200);
        doc.roundedRect(10, 35, doc.internal.pageSize.width - 20, 10, 2, 2, 'FD');
        
        doc.setTextColor(70, 130, 180);
        doc.setFontSize(14);
        doc.text('HISTORIAL DE MANTENIMIENTO', doc.internal.pageSize.width / 2, 42, { align: 'center' });
        
        // Información de la tabla
        const totalMantenimientos = data.length;
        const totalPlantas = new Set(data.map(row => row[1])).size;
        
        // Añadir estadísticas resumen
        doc.setTextColor(80, 80, 80);
        doc.setFontSize(10);
        doc.text(`Total de registros: ${totalMantenimientos} | Total de plantas: ${totalPlantas}`, doc.internal.pageSize.width / 2, 52, { align: 'center' });
        
        // Crear tabla con los datos
        doc.autoTable({
            startY: 60,
            head: [headers],
            body: data,
            theme: 'grid',
            styles: {
                font: 'helvetica',
                fontSize: 9,
                cellPadding: 3,
                lineWidth: 0.2,
                lineColor: [220, 220, 220]
            },
            headStyles: {
                fillColor: [67, 97, 238],
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                halign: 'center'
            },
            alternateRowStyles: {
                fillColor: [245, 247, 250]
            },
            columnStyles: {
                0: { cellWidth: 10, halign: 'center' }, // ID
                1: { cellWidth: 30 }, // Planta
                2: { cellWidth: 30 }, // Ubicación
                3: { cellWidth: 20 }, // Tipo
                4: { cellWidth: 25 }, // Responsable
                5: { cellWidth: 'auto' }, // Descripción
                6: { cellWidth: 20, halign: 'center' }, // Fecha
                7: { cellWidth: 20, halign: 'center' }, // Estado
            },
            didDrawCell: function(data) {
                // Colorear las celdas de estado
                if (data.section === 'body' && data.column.index === 7) {
                    const estado = data.cell.raw.toLowerCase();
                    let fillColor;
                    
                    if (estado.includes('excelente')) {
                        fillColor = [56, 176, 0, 0.2]; // Verde claro
                    } else if (estado.includes('bueno')) {
                        fillColor = [76, 201, 240, 0.2]; // Azul claro
                    } else if (estado.includes('regular')) {
                        fillColor = [244, 140, 6, 0.2]; // Naranja claro
                    } else if (estado.includes('malo') || estado.includes('crítico')) {
                        fillColor = [208, 0, 0, 0.2]; // Rojo claro
                    }
                    
                    if (fillColor) {
                        doc.setFillColor(fillColor[0], fillColor[1], fillColor[2], fillColor[3]);
                        doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'F');
                        doc.setTextColor(0, 0, 0);
                        doc.text(data.cell.raw, data.cell.x + data.cell.width / 2, data.cell.y + data.cell.height / 2, {
                            align: 'center',
                            baseline: 'middle'
                        });
                    }
                }
            },
            didDrawPage: function(data) {
                // Añadir número de página
                const pageSize = doc.internal.pageSize;
                const pageNumber = `Página ${doc.internal.getCurrentPageInfo().pageNumber} de ${doc.internal.getNumberOfPages()}`;
                
                // Fecha de generación
                const fechaActual = new Date().toLocaleDateString('es-ES');
                
                // Añadir pie de página
                doc.setFillColor(240, 240, 240);
                doc.rect(0, pageSize.height - 15, pageSize.width, 15, 'F');
                
                doc.setFontSize(8);
                doc.setTextColor(100, 100, 100);
                doc.text(`Documento generado el ${fechaActual}`, 10, pageSize.height - 5);
                doc.text(pageNumber, pageSize.width - 10, pageSize.height - 5, { align: 'right' });
                
                // Añadir marca de agua sutil en cada página
                doc.setTextColor(230, 230, 230);
                doc.setFontSize(60);
                doc.text('SANTIVÁÑEZ', pageSize.width / 2, pageSize.height / 2, {
                    align: 'center',
                    angle: 45,
                    renderingMode: 'fillThenStroke',
                    strokeStyle: 'rgba(230, 230, 230, 0.3)',
                    lineWidth: 1
                });
            }
        });
        
        // Guardar el PDF
        const fechaActual = new Date().toLocaleDateString('es-ES');
        doc.save(`Historial_Mantenimiento_${fechaActual.replace(/\//g, '-')}.pdf`);
        
        showNotification('Historial de mantenimiento exportado a PDF correctamente', 'success');
    } catch (error) {
        console.error('Error al exportar historial a PDF:', error);
        showNotification('Error al exportar historial a PDF: ' + error.message, 'error');
    }
}

// Exportar historial de mantenimiento a Excel con formato mejorado
function exportMaintenanceHistoryToExcel() {
    // Verificar bibliotecas
    if (!checkAndLoadLibraries(function() { exportMaintenanceHistoryToExcel(); })) {
        return;
    }
    
    try {
        // Obtener los datos de la tabla
        const table = document.getElementById('maintenanceTable');
        const rows = table.querySelectorAll('tbody tr');
        
        // Crear arreglo para los encabezados
        const headers = [];
        
        // Obtener los encabezados (excluyendo la columna de acciones)
        table.querySelectorAll('thead th').forEach((th, index) => {
            if (index < 8) { // Excluir la columna de acciones
                // Extraer solo el texto, no los íconos
                headers.push(th.textContent.trim());
            }
        });
        
        // Crear arreglo para los datos con formato
        const data = [];
        
        // Añadir fila de encabezados con estilo
        const headerRow = headers.map(header => {
            return { 
                v: header, 
                s: { 
                    font: { bold: true, color: { rgb: "FFFFFF" } }, 
                    fill: { fgColor: { rgb: "4361EE" } },
                    alignment: { horizontal: 'center', vertical: 'center' }
                }
            };
        });
        data.push(headerRow);
        
        // Obtener los datos de las filas con formato condicional para estados
        rows.forEach((row, rowIndex) => {
            const rowData = [];
            row.querySelectorAll('td').forEach((cell, cellIndex) => {
                if (cellIndex < 8) { // Excluir la columna de acciones
                    let cellValue = cell.textContent.trim();
                    let cellStyle = {};
                    
                    // Aplicar estilos alternados a las filas
                    if (rowIndex % 2 === 1) {
                        cellStyle.fill = { fgColor: { rgb: "F5F7FA" } };
                    }
                    
                    // Formatear la columna de estado con colores
                    if (cellIndex === 7) { // Columna de estado
                        const badge = cell.querySelector('.badge');
                        cellValue = badge ? badge.textContent.trim() : cellValue;
                        cellStyle = getEstadoStyle(cellValue, rowIndex);
                        cellStyle.alignment = { horizontal: 'center' };
                    }
                    
                    // Centrar ID y fecha
                    if (cellIndex === 0 || cellIndex === 6) {
                        cellStyle.alignment = { horizontal: 'center' };
                    }
                    
                    rowData.push({ v: cellValue, s: cellStyle });
                }
            });
            data.push(rowData);
        });
        
        // Función para obtener estilo según estado
        function getEstadoStyle(estado, rowIndex) {
            const estadoLower = estado.toLowerCase();
            let style = rowIndex % 2 === 1 ? { fill: { fgColor: { rgb: "F5F7FA" } } } : {};
            
            if (estadoLower.includes('excelente')) {
                style.fill = { fgColor: { rgb: "DCFFD6" } }; // Verde claro
            } else if (estadoLower.includes('bueno')) {
                style.fill = { fgColor: { rgb: "D6EEFF" } }; // Azul claro
            } else if (estadoLower.includes('regular')) {
                style.fill = { fgColor: { rgb: "FFECD6" } }; // Naranja claro
            } else if (estadoLower.includes('malo') || estadoLower.includes('crítico')) {
                style.fill = { fgColor: { rgb: "FFD6D6" } }; // Rojo claro
            }
            
            return style;
        }
        
        // Crear título y encabezado con formato
        const title = [
            [{ v: 'SISTEMA DE MANTENIMIENTO DE PLANTAS - PARQUE INDUSTRIAL SANTIVÁÑEZ', s: { font: { bold: true, sz: 14 }, alignment: { horizontal: 'center' } } }],
            [{ v: 'HISTORIAL DE MANTENIMIENTO', s: { font: { bold: true, sz: 12 }, alignment: { horizontal: 'center' } } }],
            [{ v: `Fecha de generación: ${new Date().toLocaleDateString('es-ES')}`, s: { font: { italic: true }, alignment: { horizontal: 'center' } } }],
            ['']
        ];
        
        // Agregar información estadística 
        const estadisticas = [
            [{ v: 'Resumen:', s: { font: { bold: true }, fill: { fgColor: { rgb: "E6E6E6" } } } }],
            [{ v: `Total de registros: ${rows.length}`, s: { font: { bold: false } } }],
            [{ v: `Total de plantas: ${new Set([...rows].map(row => row.querySelectorAll('td')[1].textContent.trim())).size}`, s: { font: { bold: false } } }],
            ['']
        ];
        
        // Combinar todo para el Excel
        const fullData = [...title, ...estadisticas, ...data];
        
        // Convertir datos en formato para XLSX
        const ws_data = [];
        fullData.forEach(row => {
            const new_row = [];
            row.forEach(cell => {
                if (typeof cell === 'object' && cell !== null) {
                    new_row.push(cell.v);
                } else {
                    new_row.push(cell);
                }
            });
            ws_data.push(new_row);
        });
        
        // Crear libro y hoja
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(ws_data);
        
        // Configurar ancho de columnas
        ws['!cols'] = [
            { wch: 10 }, // ID
            { wch: 25 }, // Planta
            { wch: 20 }, // Ubicación
            { wch: 15 }, // Tipo
            { wch: 20 }, // Responsable
            { wch: 40 }, // Descripción
            { wch: 15 }, // Fecha
            { wch: 15 }, // Estado
        ];
        
        // Configurar altura de filas
        ws['!rows'] = [];
        ws['!rows'][0] = { hpt: 30 }; // Título
        ws['!rows'][1] = { hpt: 25 }; // Subtítulo
        
        // Aplicar fusión de celdas para títulos
        if (!ws['!merges']) ws['!merges'] = [];
        ws['!merges'].push(
            { s: {r: 0, c: 0}, e: {r: 0, c: 7} }, // Título
            { s: {r: 1, c: 0}, e: {r: 1, c: 7} }, // Subtítulo
            { s: {r: 2, c: 0}, e: {r: 2, c: 7} }  // Fecha
        );
        
        // Añadir hoja al libro
        XLSX.utils.book_append_sheet(wb, ws, 'Historial');
        
        // Guardar archivo
        const fechaActual = new Date().toLocaleDateString('es-ES').replace(/\//g, '-');
        XLSX.writeFile(wb, `Historial_Mantenimiento_${fechaActual}.xlsx`);
        
        showNotification('Historial de mantenimiento exportado a Excel correctamente', 'success');
    } catch (error) {
        console.error('Error al exportar historial a Excel:', error);
        showNotification('Error al exportar historial a Excel: ' + error.message, 'error');
    }
}

/**
 * FUNCIONES DE UTILIDAD
 */

// Función para mostrar notificaciones mejoradas
function showNotification(message, type = 'info', duration = 5000) {
    // Verificar si ya existe un contenedor de notificaciones
    let notificationContainer = document.querySelector('.notification-container');
    
    if (!notificationContainer) {
        // Crear contenedor si no existe
        notificationContainer = document.createElement('div');
        notificationContainer.className = 'notification-container';
        notificationContainer.style.position = 'fixed';
        notificationContainer.style.top = '20px';
        notificationContainer.style.right = '20px';
        notificationContainer.style.zIndex = '9999';
        document.body.appendChild(notificationContainer);
    }
    
    // Crear la notificación con diseño mejorado
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    // Estilos mejorados
    Object.assign(notification.style, {
        backgroundColor: getColorByType(type),
        color: '#fff',
        padding: '15px 20px',
        borderRadius: '8px',
        boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
        marginBottom: '12px',
        minWidth: '300px',
        position: 'relative',
        opacity: '0',
        transform: 'translateY(-20px) scale(0.95)',
        transition: 'opacity 0.3s, transform 0.3s',
        display: 'flex',
        alignItems: 'center',
        borderLeft: `6px solid ${getBorderColorByType(type)}`
    });
    
    // Agregar ícono según el tipo con estilo mejorado
    const icon = document.createElement('i');
    icon.className = 'fas ' + getIconByType(type);
    icon.style.marginRight = '12px';
    icon.style.fontSize = '20px';
    notification.appendChild(icon);
    
    // Contenedor para el texto (para mantener el diseño flexible)
    const textContainer = document.createElement('div');
    textContainer.style.flex = '1';
    
    // Agregar el mensaje
    const messageSpan = document.createElement('span');
    messageSpan.textContent = message;
    messageSpan.style.fontSize = '14px';
    messageSpan.style.lineHeight = '1.4';
    textContainer.appendChild(messageSpan);
    notification.appendChild(textContainer);
    
    // Agregar botón de cierre mejorado
    const closeButton = document.createElement('button');
    closeButton.innerHTML = '&times;';
    Object.assign(closeButton.style, {
        background: 'transparent',
        border: 'none',
        color: '#fff',
        fontSize: '22px',
        fontWeight: 'bold',
        marginLeft: '10px',
        cursor: 'pointer',
        opacity: '0.7',
        transition: 'opacity 0.2s',
        padding: '0 5px',
        lineHeight: '1'
    });
    
    closeButton.onmouseover = function() {
        closeButton.style.opacity = '1';
    };
    
    closeButton.onmouseout = function() {
        closeButton.style.opacity = '0.7';
    };
    
    closeButton.onclick = function() {
        hideNotification(notification);
    };
    
    notification.appendChild(closeButton);
    
    // Agregar al contenedor
    notificationContainer.appendChild(notification);
    
    // Mostrar con animación
    setTimeout(function() {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0) scale(1)';
    }, 10);
    
    // Ocultar después de cierto tiempo
    setTimeout(function() {
        hideNotification(notification);
    }, duration);
    
    // Función para ocultar la notificación con animación
    function hideNotification(notif) {
        notif.style.opacity = '0';
        notif.style.transform = 'translateY(-20px) scale(0.95)';
        setTimeout(function() {
            if (notif.parentNode) {
                notif.parentNode.removeChild(notif);
                
                // Remover el contenedor si no hay más notificaciones
                if (notificationContainer.children.length === 0) {
                    document.body.removeChild(notificationContainer);
                }
            }
        }, 300);
    }
    
    // Función para obtener el color según el tipo
    function getColorByType(type) {
        switch (type) {
            case 'success': return '#38b000';
            case 'error': return '#d00000';
            case 'warning': return '#f48c06';
            case 'info':
            default: return '#4361ee';
        }
    }
    
    // Función para obtener el color de borde según el tipo
    function getBorderColorByType(type) {
        switch (type) {
            case 'success': return '#2b9348';
            case 'error': return '#9d0208';
            case 'warning': return '#e85d04';
            case 'info':
            default: return '#3a56d4';
        }
    }
    
    // Función para obtener el ícono según el tipo
    function getIconByType(type) {
        switch (type) {
            case 'success': return 'fa-check-circle';
            case 'error': return 'fa-exclamation-circle';
            case 'warning': return 'fa-exclamation-triangle';
            case 'info':
            default: return 'fa-info-circle';
        }
    }
}

// Inicializar cuando se carga el documento
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de exportación de datos inicializado');
    
    // Verificar todas las bibliotecas necesarias
    checkAndLoadLibraries();
});