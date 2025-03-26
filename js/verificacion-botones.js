/**
 * Archivo para verificar el funcionamiento de los botones
 * Sistema de mantenimiento de plantas - Parque Industrial Santiváñez
 */

// Función para verificar que los botones estén correctamente conectados
function verificarBotones() {
    console.log('Verificando botones de exportación...');
    
    // Verificar si los scripts están cargados
    const scriptsLoaded = {
        'exportaciones.js': !!document.querySelector('script[src*="exportaciones.js"]'),
        'integracion-exportaciones.js': !!document.querySelector('script[src*="integracion-exportaciones.js"]')
    };
    
    console.log('Scripts cargados:', scriptsLoaded);
    
    // Verificar botones de exportación
    const botones = {
        'PDF Mantenimiento': document.querySelector('button[onclick="generateMaintenancePDF()"]'),
        'Excel Mantenimiento': document.querySelector('button[onclick="exportMaintenanceToExcel()"]'),
        'PDF Hoja de Ruta': document.querySelector('button[onclick="generateRoutingPDF()"]'),
        'Excel Hoja de Ruta': document.querySelector('button[onclick="exportRoutingToExcel()"]'),
        'PDF Historial': document.querySelector('button[onclick="generateMaintenanceHistoryPDF()"]'),
        'Excel Historial': document.querySelector('button[onclick="exportMaintenanceHistoryToExcel()"]'),
        'Imprimir Hoja de Ruta': document.querySelector('button[onclick="printRoutingForm()"]'),
        'Enviar Email': document.querySelector('button[onclick="sendEmail()"]')
    };
    
    console.log('Botones encontrados:', Object.entries(botones).reduce((acc, [key, val]) => {
        acc[key] = !!val;
        return acc;
    }, {}));
    
    // Verificar si las funciones están definidas
    const funciones = {
        'generateMaintenancePDF': typeof generateMaintenancePDF === 'function',
        'exportMaintenanceToExcel': typeof exportMaintenanceToExcel === 'function',
        'generateRoutingPDF': typeof generateRoutingPDF === 'function',
        'exportRoutingToExcel': typeof exportRoutingToExcel === 'function',
        'generateMaintenanceHistoryPDF': typeof generateMaintenanceHistoryPDF === 'function',
        'exportMaintenanceHistoryToExcel': typeof exportMaintenanceHistoryToExcel === 'function',
        'printRoutingForm': typeof printRoutingForm === 'function',
        'sendEmail': typeof sendEmail === 'function'
    };
    
    console.log('Funciones definidas:', funciones);
    
    // Verificar si se cargaron las librerías
    const librerias = {
        'jsPDF': typeof window.jspdf !== 'undefined',
        'SheetJS (XLSX)': typeof XLSX !== 'undefined'
    };
    
    console.log('Librerías cargadas:', librerias);
    
    // Mostrar resultados en la interfaz
    mostrarResultadosVerificacion(scriptsLoaded, botones, funciones, librerias);
}

// Función para mostrar los resultados de la verificación en la interfaz
function mostrarResultadosVerificacion(scriptsLoaded, botones, funciones, librerias) {
    // Crear un contenedor para los resultados
    const contenedor = document.createElement('div');
    contenedor.style.position = 'fixed';
    contenedor.style.bottom = '10px';
    contenedor.style.left = '10px';
    contenedor.style.zIndex = '9999';
    contenedor.style.backgroundColor = '#fff';
    contenedor.style.border = '1px solid #ddd';
    contenedor.style.borderRadius = '5px';
    contenedor.style.padding = '10px';
    contenedor.style.boxShadow = '0 0 10px rgba(0,0,0,0.1)';
    contenedor.style.maxWidth = '500px';
    contenedor.style.maxHeight = '400px';
    contenedor.style.overflow = 'auto';
    
    // Título
    const titulo = document.createElement('h3');
    titulo.textContent = 'Verificación de Botones de Exportación';
    titulo.style.margin = '0 0 10px 0';
    titulo.style.color = '#4361ee';
    contenedor.appendChild(titulo);
    
    // Crear tabla para mostrar resultados
    const tabla = document.createElement('table');
    tabla.style.borderCollapse = 'collapse';
    tabla.style.width = '100%';
    
    // Función para agregar sección a la tabla
    function agregarSeccion(nombre, datos) {
        // Título de sección
        const filaTitulo = document.createElement('tr');
        const celdaTitulo = document.createElement('th');
        celdaTitulo.textContent = nombre;
        celdaTitulo.colSpan = 2;
        celdaTitulo.style.backgroundColor = '#f5f5f5';
        celdaTitulo.style.padding = '5px';
        celdaTitulo.style.textAlign = 'left';
        celdaTitulo.style.borderBottom = '1px solid #ddd';
        filaTitulo.appendChild(celdaTitulo);
        tabla.appendChild(filaTitulo);
        
        // Datos
        Object.entries(datos).forEach(([clave, valor]) => {
            const fila = document.createElement('tr');
            
            const celdaClave = document.createElement('td');
            celdaClave.textContent = clave;
            celdaClave.style.padding = '5px';
            celdaClave.style.borderBottom = '1px solid #ddd';
            fila.appendChild(celdaClave);
            
            const celdaValor = document.createElement('td');
            celdaValor.textContent = valor ? '✅' : '❌';
            celdaValor.style.padding = '5px';
            celdaValor.style.textAlign = 'center';
            celdaValor.style.borderBottom = '1px solid #ddd';
            celdaValor.style.color = valor ? 'green' : 'red';
            fila.appendChild(celdaValor);
            
            tabla.appendChild(fila);
        });
    }
    
    // Agregar secciones a la tabla
    agregarSeccion('Scripts Cargados', scriptsLoaded);
    agregarSeccion('Botones Encontrados', Object.entries(botones).reduce((acc, [key, val]) => {
        acc[key] = !!val;
        return acc;
    }, {}));
    agregarSeccion('Funciones Definidas', funciones);
    agregarSeccion('Librerías Cargadas', librerias);
    
    contenedor.appendChild(tabla);
    
    // Botón para cerrar
    const btnCerrar = document.createElement('button');
    btnCerrar.textContent = 'Cerrar';
    btnCerrar.style.marginTop = '10px';
    btnCerrar.style.padding = '5px 10px';
    btnCerrar.style.backgroundColor = '#4361ee';
    btnCerrar.style.color = 'white';
    btnCerrar.style.border = 'none';
    btnCerrar.style.borderRadius = '3px';
    btnCerrar.style.cursor = 'pointer';
    btnCerrar.onclick = function() {
        document.body.removeChild(contenedor);
    };
    contenedor.appendChild(btnCerrar);
    
    // Agregar el contenedor al cuerpo del documento
    document.body.appendChild(contenedor);
}

// Función para cargar bibliotecas y activar los eventos
function solucionarProblemasExportacion() {
    // Verificar si ya están cargados los scripts necesarios
    if (!document.querySelector('script[src*="exportaciones.js"]')) {
        console.log('Cargando exportaciones.js...');
        const script1 = document.createElement('script');
        script1.src = '../public/js/exportaciones.js';
        document.head.appendChild(script1);
    }
    
    if (!document.querySelector('script[src*="integracion-exportaciones.js"]')) {
        console.log('Cargando integracion-exportaciones.js...');
        const script2 = document.createElement('script');
        script2.src = '../public/js/integracion-exportaciones.js';
        document.head.appendChild(script2);
    }
    
    // Verificar si existen botones sin eventos
    const botonesSinEvento = Array.from(document.querySelectorAll('button[onclick^="generate"], button[onclick^="export"]')).filter(btn => {
        const onclickAttr = btn.getAttribute('onclick');
        // Verificamos si la función existe
        return typeof window[onclickAttr.split('(')[0]] !== 'function';
    });
    
    console.log(`Se encontraron ${botonesSinEvento.length} botones sin eventos asociados.`);
    
    if (botonesSinEvento.length > 0) {
        // Intentamos asignar eventos manualmente
        botonesSinEvento.forEach(btn => {
            const funcionNombre = btn.getAttribute('onclick').split('(')[0];
            console.log(`Asignando evento para: ${funcionNombre}`);
            
            btn.onclick = function(e) {
                e.preventDefault();
                alert(`Se intentó llamar a la función "${funcionNombre}", pero no está disponible. Por favor verifica que los scripts estén cargados correctamente.`);
            };
        });
    }
    
    // Verificar librerías y cargarlas si es necesario
    if (typeof window.jspdf === 'undefined' || typeof XLSX === 'undefined') {
        console.log('Cargando bibliotecas necesarias...');
        checkAndLoadLibraries(function() {
            console.log('Bibliotecas cargadas correctamente');
            // Volver a verificar botones
            setTimeout(verificarBotones, 1000);
        });
    } else {
        verificarBotones();
    }
}

// Función que puedes llamar desde la consola para verificar botones
window.verificarBotones = verificarBotones;
window.solucionarProblemasExportacion = solucionarProblemasExportacion;

// Auto-ejecutar verificación cuando se incluye este script
document.addEventListener('DOMContentLoaded', function() {
    console.log('Verificación de botones lista para ejecutarse');
    // Para verificar automáticamente, descomenta la siguiente línea:
    // setTimeout(verificarBotones, 2000);
});