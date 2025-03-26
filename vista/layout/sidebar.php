<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Monitoreo y Registros</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body>
    <!-- Botón del menú -->
    <button id="toggle-menu" class="menu-button">
        <span class="hamburger-icon"></span>
    </button>

    <!-- Menú lateral -->
    <nav id="side-menu" class="side-menu">
        <div class="menu-header">
            <div class="logo-container">
                <img src="../public/img-inicio/ari.png" alt="Logo" class="menu-logo">
                <div class="logo-glow"></div>
            </div>
            <h3>Sistema de Monitoreo y Registros</h3>
        </div>
        <div class="menu-container">
            <ul class="side-menu-list">
                <li class="nav-item">
                    <a href="#" class="nav-link submenu-toggle">
                        <i class="fas fa-qrcode nav-icon"></i>
                        <span class="nav-label">MONITOREO QR</span>
                        <i class="fas fa-chevron-right submenu-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="#" class="nav-link" onclick="openQRScanner(); return false;">
                            <i class="fas fa-crosshairs submenu-icon-small"></i> Escanear QR
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link submenu-toggle">
                        <i class="fas fa-industry nav-icon"></i>
                        <span class="nav-label">PLANTAS</span>
                        <i class="fas fa-chevron-right submenu-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="#" class="nav-link" id="btn-alta-produccion">
                            <span class="status-indicator high"></span> Alta Producción
                        </a></li>
                        <li><a href="#" class="nav-link" id="btn-baja-produccion">
                            <span class="status-indicator medium"></span> Baja Producción
                        </a></li>
                        <li><a href="#" class="nav-link" id="btn-zonas-riesgo">
                            <span class="status-indicator danger"></span> Zonas de Riesgo
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link submenu-toggle">
                        <i class="fas fa-clock nav-icon"></i>
                        <span class="nav-label">ASISTENCIA</span>
                        <i class="fas fa-chevron-right submenu-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="inicio.php" class="nav-link">
                            <i class="fas fa-clipboard-list submenu-icon-small"></i> Ver Asistencia
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="usuario.php" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <span class="nav-label">USUARIOS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="listar_reportes.php" class="nav-link">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <span class="nav-label">REPORTES</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="acerca.php" class="nav-link">
                        <i class="fas fa-info-circle nav-icon"></i>
                        <span class="nav-label">ACERCA DE</span>
                    </a>
                </li>
            </ul>
        </div>
       
    </nav>

    <!-- Overlay -->
    <div id="menu-overlay" class="menu-overlay"></div>

    <!-- Modal QR -->
    <div id="qr-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-qrcode"></i> Escáner QR</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div id="qr-reader" class="qr-reader-container"></div>
                <div id="qr-result" class="qr-result"></div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <main id="main-content" class="main-content">
        <div class="dashboard">
            <div class="welcome-card">
                <div class="welcome-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="welcome-text">
                    <h1>Bienvenido al Sistema de Monitoreo</h1>
                    <p>Gestiona y supervisa el estado de tus plantas en tiempo real</p>
                </div>
            </div>

            <div class="stats-container">
                <div class="stat-card" id="alta-produccion-card">
                    <div class="stat-icon high">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Alta Producción</h3>
                        <p class="stat-value">27 plantas</p>
                        <div class="progress-bar">
                            <div class="progress high" style="width: 85%;"></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card" id="baja-produccion-card">
                    <div class="stat-icon medium">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Baja Producción</h3>
                        <p class="stat-value">14 plantas</p>
                        <div class="progress-bar">
                            <div class="progress medium" style="width: 45%;"></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card" id="zonas-riesgo-card">
                    <div class="stat-icon danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Zonas de Riesgo</h3>
                        <p class="stat-value">5 plantas</p>
                        <div class="progress-bar">
                            <div class="progress danger" style="width: 15%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-area" id="content-display">
                <div class="default-content">
                    <div class="quick-actions">
                        <h2>Acciones Rápidas</h2>
                        <div class="action-buttons">
                            <button class="action-btn" onclick="openQRScanner()">
                                <i class="fas fa-qrcode"></i>
                                <span>Escanear QR</span>
                            </button>
                            <button class="action-btn" onclick="showDetailView('alta')">
                                <i class="fas fa-leaf"></i>
                                <span>Ver Alto Rendimiento</span>
                            </button>
                            <button class="action-btn" onclick="showDetailView('baja')">
                                <i class="fas fa-water"></i>
                                <span>Revisar Bajo Rendimiento</span>
                            </button>
                            <button class="action-btn" onclick="showDetailView('riesgo')">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Atender Zonas Críticas</span>
                            </button>
                        </div>
                    </div>

                    <div class="recent-activity">
                        <h2>Actividad Reciente</h2>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon high"><i class="fas fa-check-circle"></i></div>
                                <div class="activity-details">
                                    <h4>Planta #A12 registrada</h4>
                                    <p>Alta producción confirmada - Sector Norte</p>
                                    <span class="activity-time">Hace 15 minutos</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon medium"><i class="fas fa-exclamation"></i></div>
                                <div class="activity-details">
                                    <h4>Planta #B07 actualizada</h4>
                                    <p>Cambio a baja producción - Requiere revisión</p>
                                    <span class="activity-time">Hace 1 hora</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon danger"><i class="fas fa-exclamation-triangle"></i></div>
                                <div class="activity-details">
                                    <h4>Planta #C03 en alerta</h4>
                                    <p>Zona de riesgo detectada - Intervención inmediata</p>
                                    <span class="activity-time">Hace 3 horas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Plantillas de Contenido -->
    <template id="tpl-alta-produccion">
        <div class="detail-view">
            <div class="detail-header high">
                <h2><i class="fas fa-arrow-up"></i> Plantas en Alta Producción</h2>
                <p>Monitoreo de plantas con rendimiento óptimo</p>
            </div>
            
            <div class="filter-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar planta...">
                </div>
                <div class="filter-options">
                    <select>
                        <option>Todos los sectores</option>
                        <option>Sector Norte</option>
                        <option>Sector Sur</option>
                        <option>Sector Este</option>
                        <option>Sector Oeste</option>
                    </select>
                    <button class="filter-btn"><i class="fas fa-filter"></i> Filtrar</button>
                </div>
            </div>
            
            <div class="plant-grid">
                <div class="plant-card high">
                    <div class="plant-header">
                        <span class="plant-id">A12</span>
                        <span class="plant-status">96%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial A12</h3>
                        <p>Sector: Norte</p>
                        <p>Última actualización: Hoy, 09:45</p>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-small"><i class="fas fa-history"></i></button>
                    </div>
                </div>
                
                <div class="plant-card high">
                    <div class="plant-header">
                        <span class="plant-id">A08</span>
                        <span class="plant-status">92%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial A08</h3>
                        <p>Sector: Este</p>
                        <p>Última actualización: Hoy, 08:30</p>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-small"><i class="fas fa-history"></i></button>
                    </div>
                </div>
                
                <div class="plant-card high">
                    <div class="plant-header">
                        <span class="plant-id">A15</span>
                        <span class="plant-status">91%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial A15</h3>
                        <p>Sector: Oeste</p>
                        <p>Última actualización: Ayer, 16:20</p>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-small"><i class="fas fa-history"></i></button>
                    </div>
                </div>
                
                <div class="plant-card high">
                    <div class="plant-header">
                        <span class="plant-id">A23</span>
                        <span class="plant-status">88%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial A23</h3>
                        <p>Sector: Sur</p>
                        <p>Última actualización: Ayer, 14:45</p>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-small"><i class="fas fa-history"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="pagination">
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">4</button>
                <button class="page-btn">5</button>
                <button class="page-btn"><i class="fas fa-ellipsis-h"></i></button>
                <button class="page-btn">10</button>
            </div>
        </div>
    </template>
    
    <template id="tpl-baja-produccion">
        <div class="detail-view">
            <div class="detail-header medium">
                <h2><i class="fas fa-arrow-down"></i> Plantas en Baja Producción</h2>
                <p>Monitoreo de plantas con rendimiento reducido</p>
            </div>
            
            <div class="filter-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar planta...">
                </div>
                <div class="filter-options">
                    <select>
                        <option>Todos los sectores</option>
                        <option>Sector Norte</option>
                        <option>Sector Sur</option>
                        <option>Sector Este</option>
                        <option>Sector Oeste</option>
                    </select>
                    <button class="filter-btn"><i class="fas fa-filter"></i> Filtrar</button>
                </div>
            </div>
            
            <div class="plant-grid">
                <div class="plant-card medium">
                    <div class="plant-header">
                        <span class="plant-id">B07</span>
                        <span class="plant-status">65%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-industry"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial B07</h3>
                        <p>Sector: Este</p>
                        <p>Última actualización: Hoy, 10:15</p>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-small"><i class="fas fa-history"></i></button>
                    </div>
                </div>
                
                <div class="plant-card medium">
                    <div class="plant-header">
                        <span class="plant-id">B12</span>
                        <span class="plant-status">62%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-industry"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial B12</h3>
                        <p>Sector: Norte</p>
                        <p>Última actualización: Ayer, 17:30</p>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-small"><i class="fas fa-history"></i></button>
                    </div>
                </div>
                
                <div class="plant-card medium">
                    <div class="plant-header">
                        <span class="plant-id">B09</span>
                        <span class="plant-status">58%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-industry"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial B09</h3>
                        <p>Sector: Sur</p>
                        <p>Última actualización: Ayer, 13:45</p>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-small"><i class="fas fa-history"></i></button>
                    </div>
                </div>
                
                <div class="plant-card medium">
                    <div class="plant-header">
                        <span class="plant-id">B15</span>
                        <span class="plant-status">53%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-industry"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial B15</h3>
                        <p>Sector: Oeste</p>
                        <p>Última actualización: 22/02/2025</p>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-small"><i class="fas fa-history"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="pagination">
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">4</button>
            </div>
        </div>
    </template>
    
    <template id="tpl-zonas-riesgo">
        <div class="detail-view">
            <div class="detail-header danger">
                <h2><i class="fas fa-exclamation-triangle"></i> Zonas de Riesgo</h2>
                <p>Plantas que requieren atención inmediata</p>
            </div>
            
            <div class="filter-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar planta...">
                </div>
                <div class="filter-options">
                    <select>
                        <option>Todos los sectores</option>
                        <option>Sector Norte</option>
                        <option>Sector Sur</option>
                        <option>Sector Este</option>
                        <option>Sector Oeste</option>
                    </select>
                    <button class="filter-btn"><i class="fas fa-filter"></i> Filtrar</button>
                </div>
            </div>
            
            <div class="alert-message">
                <i class="fas fa-bell"></i>
                <div>
                    <h3>¡Atención!</h3>
                    <p>Las plantas en zonas de riesgo requieren intervención inmediata. Por favor, revise los protocolos de seguridad antes de proceder.</p>
                </div>
            </div>
            
            <div class="plant-grid">
                <div class="plant-card danger">
                    <div class="plant-header">
                        <span class="plant-id">C03</span>
                        <span class="plant-status">32%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-radiation"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial C03</h3>
                        <p>Sector: Sur</p>
                        <p>Última actualización: Hoy, 08:15</p>
                        <span class="priority-tag">¡CRÍTICA!</span>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-tools"></i></button>
                        <button class="action-btn-small"><i class="fas fa-exclamation"></i></button>
                    </div>
                </div>
                
                <div class="plant-card danger">
                    <div class="plant-header">
                        <span class="plant-id">C07</span>
                        <span class="plant-status">29%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-radiation"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial C07</h3>
                        <p>Sector: Norte</p>
                        <p>Última actualización: Ayer, 16:40</p>
                        <span class="priority-tag">¡CRÍTICA!</span>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-tools"></i></button>
                        <button class="action-btn-small"><i class="fas fa-exclamation"></i></button>
                    </div>
                </div>
                
                <div class="plant-card danger">
                    <div class="plant-header">
                        <span class="plant-id">C11</span>
                        <span class="plant-status">25%</span>
                    </div>
                    <div class="plant-image">
                        <i class="fas fa-radiation"></i>
                    </div>
                    <div class="plant-info">
                        <h3>Planta Industrial C11</h3>
                        <p>Sector: Este</p>
                        <p>Última actualización: 23/02/2025</p>
                        <span class="priority-tag">¡CRÍTICA!</span>
                    </div>
                    <div class="plant-actions">
                        <button class="action-btn-small"><i class="fas fa-eye"></i></button>
                        <button class="action-btn-small"><i class="fas fa-tools"></i></button>
                        <button class="action-btn-small"><i class="fas fa-exclamation"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <style>
       :root {
    --primary-color: #1E88E5;
    --primary-dark: #1565C0;
    --primary-light: #64B5F6;
    --secondary-color: #FFC107;
    --danger-color: #F44336;
    --success-color: #4CAF50;
    --warning-color: #FF9800;
    --text-light: #FFFFFF;
    --text-dark: #333333;
    --text-gray: #757575;
    --bg-light: #F5F5F5;
    --bg-dark: #212121;
    --card-bg: #FFFFFF;
    --border-radius: 12px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition-speed: 0.3s;
    --success-gradient: linear-gradient(135deg, #43A047, #2E7D32);
    --warning-gradient: linear-gradient(135deg, #FFB300, #FF8F00);
    --danger-gradient: linear-gradient(135deg, #E53935, #C62828);
    --primary-gradient: linear-gradient(135deg, #1976D2, #0D47A1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--bg-light);
    line-height: 1.6;
    overflow-x: hidden;
    color: var(--text-dark);
}

/* Menú lateral */
.side-menu {
    width: 280px;
    height: 100vh;
    background: var(--primary-gradient);
    color: var(--text-light);
    position: fixed;
    top: 0;
    left: -280px;
    transition: left var(--transition-speed) ease;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    box-shadow: var(--box-shadow);
}

.side-menu.active {
    left: 0;
}

.menu-header {
    padding: 25px 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
}

.logo-container {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 15px;
}

.menu-logo {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.8);
    transition: transform var(--transition-speed);
    background-color: white;
    padding: 5px;
}

.logo-glow {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    filter: blur(10px);
    animation: glowPulse 3s infinite;
}

@keyframes glowPulse {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.05); }
}

.menu-header h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-top: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.menu-container {
    flex: 1;
    overflow-y: auto;
    padding: 10px 0;
}

.side-menu-list {
    list-style: none;
}

.nav-item {
    margin: 5px 10px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: var(--text-light);
    text-decoration: none;
    border-radius: var(--border-radius);
    transition: background-color var(--transition-speed);
    position: relative;
}

.nav-link:hover, 
.nav-link.active {
    background-color: rgba(255, 255, 255, 0.15);
}

.nav-icon {
    margin-right: 15px;
    font-size: 1.2rem;
    width: 20px;
    text-align: center;
}

.nav-label {
    font-weight: 500;
    flex: 1;
}

.submenu-icon {
    font-size: 0.8rem;
    transition: transform var(--transition-speed);
}

.submenu-toggle.active .submenu-icon {
    transform: rotate(90deg);
}

.submenu {
    list-style: none;
    margin-left: 25px;
    max-height: 0;
    overflow: hidden;
    transition: max-height var(--transition-speed) ease;
}

.submenu.active {
    max-height: 500px;
}

.submenu .nav-link {
    padding: 8px 15px;
    font-size: 0.9rem;
}

.submenu-icon-small {
    font-size: 0.8rem;
    margin-right: 10px;
    opacity: 0.8;
}

.status-indicator {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 10px;
}

.high {
    background-color: var(--success-color);
}

.medium {
    background-color: var(--warning-color);
}

.danger {
    background-color: var(--danger-color);
}

.menu-footer {
    padding: 15px 20px;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
}

.logout-btn {
    display: inline-block;
    color: var(--text-light);
    text-decoration: none;
    padding: 8px 15px;
    border-radius: var(--border-radius);
    transition: background-color var(--transition-speed);
}

.logout-btn:hover {
    background-color: rgba(255, 255, 255, 0.15);
}

.logout-btn i {
    margin-right: 8px;
}

/* Botón del menú */
.menu-button {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1001;
    background-color: var(--primary-color);
    color: white;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color var(--transition-speed);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    border: none;
}

.menu-button:hover {
    background-color: var(--primary-dark);
}

.hamburger-icon {
    position: relative;
    width: 22px;
    height: 2px;
    background-color: white;
    transition: all var(--transition-speed);
}

.hamburger-icon::before,
.hamburger-icon::after {
    content: '';
    position: absolute;
    width: 22px;
    height: 2px;
    background-color: white;
    transition: all var(--transition-speed);
}

.hamburger-icon::before {
    transform: translateY(-7px);
}

.hamburger-icon::after {
    transform: translateY(7px);
}

.menu-button.active .hamburger-icon {
    background-color: transparent;
}

.menu-button.active .hamburger-icon::before {
    transform: rotate(45deg);
}

.menu-button.active .hamburger-icon::after {
    transform: rotate(-45deg);
}

/* Overlay */
.menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all var(--transition-speed);
}

.menu-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* Área de contenido principal */
.main-content {
    margin-left: 0;
    padding: 20px;
    transition: margin-left var(--transition-speed);
    min-height: 100vh;
}

.main-content.shifted {
    margin-left: 280px;
}

/* Dashboard */
.dashboard {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.welcome-card {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.welcome-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
}

.welcome-text h1 {
    font-size: 1.6rem;
    margin-bottom: 5px;
    color: var(--text-dark);
}

.welcome-text p {
    color: var(--text-gray);
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.stat-card {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform var(--transition-speed);
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.stat-icon.high {
    background: var(--success-gradient);
}

.stat-icon.medium {
    background: var(--warning-gradient);
}

.stat-icon.danger {
    background: var(--danger-gradient);
}

.stat-info {
    flex: 1;
}

.stat-info h3 {
    font-size: 1.1rem;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.progress-bar {
    height: 6px;
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

.progress {
    height: 100%;
    border-radius: 10px;
}

.progress.high {
    background-color: var(--success-color);
}

.progress.medium {
    background-color: var(--warning-color);
}

.progress.danger {
    background-color: var(--danger-color);
}

.content-area {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
}

.default-content {
    padding: 25px;
}

.quick-actions {
    margin-bottom: 30px;
}

.quick-actions h2 {
    font-size: 1.3rem;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 15px;
    background-color: rgba(0, 0, 0, 0.03);
    border-radius: var(--border-radius);
    border: none;
    transition: all var(--transition-speed);
    cursor: pointer;
}

.action-btn:hover {
    background-color: var(--primary-color);
    color: white;
    transform: translateY(-3px);
}

.action-btn i {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.action-btn span {
    font-weight: 500;
}

.recent-activity h2 {
    font-size: 1.3rem;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    background-color: rgba(0, 0, 0, 0.02);
    border-radius: var(--border-radius);
    transition: transform var(--transition-speed);
}

.activity-item:hover {
    transform: translateX(5px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
}

.activity-icon.high {
    background: var(--success-gradient);
}

.activity-icon.medium {
    background: var(--warning-gradient);
}

.activity-icon.danger {
    background: var(--danger-gradient);
}

.activity-details {
    flex: 1;
}

.activity-details h4 {
    font-size: 1rem;
    margin-bottom: 3px;
}

.activity-details p {
    color: var(--text-gray);
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.activity-time {
    font-size: 0.8rem;
    color: var(--text-gray);
    font-style: italic;
}

/* Modal QR */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 2000;
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    width: 90%;
    max-width: 500px;
    box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.modal-header {
    padding: 15px 20px;
    background-color: var(--primary-color);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    font-size: 1.2rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.modal-header h2 i {
    margin-right: 10px;
}

.close-modal {
    font-size: 1.5rem;
    cursor: pointer;
    transition: transform var(--transition-speed);
}

.close-modal:hover {
    transform: rotate(90deg);
}

.modal-body {
    padding: 20px;
}

.qr-reader-container {
    width: 100%;
    height: 300px;
    margin-bottom: 20px;
    position: relative;
    background-color: #f0f0f0;
    border-radius: var(--border-radius);
    overflow: hidden;
}

.qr-result {
    padding: 15px;
    border-radius: var(--border-radius);
    background-color: rgba(0, 0, 0, 0.05);
    display: none;
}

.qr-result.active {
    display: block;
}

/* Vistas detalladas */
.detail-view {
    padding-bottom: 30px;
}

.detail-header {
    padding: 25px;
    color: white;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.detail-header.high {
    background: var(--success-gradient);
}

.detail-header.medium {
    background: var(--warning-gradient);
}

.detail-header.danger {
    background: var(--danger-gradient);
}

.detail-header h2 {
    font-size: 1.5rem;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
}

.detail-header h2 i {
    margin-right: 10px;
}

.detail-header p {
    opacity: 0.9;
}

.filter-bar {
    padding: 20px 25px;
    background-color: rgba(0, 0, 0, 0.02);
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    justify-content: space-between;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 200px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-gray);
}

.search-box input {
    width: 100%;
    padding: 12px 15px 12px 40px;
    border-radius: var(--border-radius);
    border: 1px solid rgba(0, 0, 0, 0.1);
    font-family: inherit;
    transition: border-color var(--transition-speed);
}

.search-box input:focus {
    outline: none;
    border-color: var(--primary-color);
}

.filter-options {
    display: flex;
    gap: 10px;
    align-items: center;
}

.filter-options select {
    padding: 10px 15px;
    border-radius: var(--border-radius);
    border: 1px solid rgba(0, 0, 0, 0.1);
    background-color: white;
    font-family: inherit;
    min-width: 150px;
}

.filter-btn {
    padding: 10px 15px;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: background-color var(--transition-speed);
}

.filter-btn:hover {
    background-color: var(--primary-dark);
}

.alert-message {
    margin: 20px 25px;
    padding: 15px;
    background-color: rgba(244, 67, 54, 0.1);
    border-left: 4px solid var(--danger-color);
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    gap: 15px;
}

.alert-message i {
    font-size: 1.5rem;
    color: var(--danger-color);
}

.alert-message h3 {
    margin-bottom: 3px;
    color: var(--danger-color);
}

.plant-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    padding: 20px 25px;
}

.plant-card {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    transition: transform var(--transition-speed);
}

.plant-card:hover {
    transform: translateY(-5px);
}

.plant-card.high {
    border-top: 4px solid var(--success-color);
}

.plant-card.medium {
    border-top: 4px solid var(--warning-color);
}

.plant-card.danger {
    border-top: 4px solid var(--danger-color);
}

.plant-header {
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.plant-id {
    font-weight: 700;
    background-color: rgba(0, 0, 0, 0.05);
    padding: 5px 10px;
    border-radius: 20px;
}

.plant-status {
    font-weight: 700;
}

.plant-card.high .plant-status {
    color: var(--success-color);
}

.plant-card.medium .plant-status {
    color: var(--warning-color);
}

.plant-card.danger .plant-status {
    color: var(--danger-color);
}

.plant-image {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-size: 3rem;
}

.plant-card.high .plant-image {
    color: var(--success-color);
}

.plant-card.medium .plant-image {
    color: var(--warning-color);
}

.plant-card.danger .plant-image {
    color: var(--danger-color);
}

.plant-info {
    padding: 15px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.plant-info h3 {
    margin-bottom: 10px;
}

.plant-info p {
    color: var(--text-gray);
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.priority-tag {
    display: inline-block;
    background-color: var(--danger-color);
    color: white;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 10px;
}

.plant-actions {
    padding: 15px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.action-btn-small {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.05);
    border: none;
    transition: all var(--transition-speed);
    cursor: pointer;
}

.action-btn-small:hover {
    background-color: var(--primary-color);
    color: white;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 30px;
    gap: 10px;
}

.page-btn {
    width: 35px;
    height: 35px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border: 1px solid rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: all var(--transition-speed);
}

.page-btn:hover, .page-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

/* Adaptabilidad */
@media (max-width: 992px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .welcome-card {
        flex-direction: column;
        text-align: center;
    }
    
    .filter-bar {
        flex-direction: column;
    }
    
    .filter-options {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .side-menu {
        width: 260px;
        left: -260px;
    }
    
    .main-content.shifted {
        margin-left: 0;
    }
    
    .action-buttons {
        grid-template-columns: 1fr 1fr;
    }
    
    .plant-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .action-buttons {
        grid-template-columns: 1fr;
    }
    
    .welcome-text h1 {
        font-size: 1.4rem;
    }
    
    .pagination {
        flex-wrap: wrap;
    }
}
</style>
<script>
// Esperar a que el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del DOM
    const toggleMenu = document.getElementById('toggle-menu');
    const sideMenu = document.getElementById('side-menu');
    const menuOverlay = document.getElementById('menu-overlay');
    const mainContent = document.getElementById('main-content');
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    const qrModal = document.getElementById('qr-modal');
    const closeModalBtn = document.querySelector('.close-modal');
    const contentDisplay = document.getElementById('content-display');

    // Elementos de tarjetas estadísticas
    const altaProduccionCard = document.getElementById('alta-produccion-card');
    const bajaProduccionCard = document.getElementById('baja-produccion-card');
    const zonasRiesgoCard = document.getElementById('zonas-riesgo-card');

    // Botones del menú
    const btnAltaProduccion = document.getElementById('btn-alta-produccion');
    const btnBajaProduccion = document.getElementById('btn-baja-produccion');
    const btnZonasRiesgo = document.getElementById('btn-zonas-riesgo');

    // Alternar menú lateral
    toggleMenu.addEventListener('click', function() {
        sideMenu.classList.toggle('active');
        toggleMenu.classList.toggle('active');
        menuOverlay.classList.toggle('active');
        mainContent.classList.toggle('shifted');
    });

    // Cerrar menú al hacer clic en el overlay
    menuOverlay.addEventListener('click', function() {
        sideMenu.classList.remove('active');
        toggleMenu.classList.remove('active');
        menuOverlay.classList.remove('active');
        mainContent.classList.remove('shifted');
    });

    // Alternar submenús
    submenuToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('active');
            const submenu = this.nextElementSibling;
            if (submenu) submenu.classList.toggle('active');
        });
    });

    // Función para mostrar diferentes vistas de contenido
    window.showDetailView = function(type) {
        let template;
        switch(type) {
            case 'alta':
                template = document.getElementById('tpl-alta-produccion').content.cloneNode(true);
                break;
            case 'baja':
                template = document.getElementById('tpl-baja-produccion').content.cloneNode(true);
                break;
            case 'riesgo':
                template = document.getElementById('tpl-zonas-riesgo').content.cloneNode(true);
                break;
            default:
                contentDisplay.innerHTML = '<p>Seleccione una vista desde el menú</p>';
                return;
        }
        contentDisplay.innerHTML = '';
        contentDisplay.appendChild(template);
    };

    // Event listeners para las tarjetas estadísticas
    altaProduccionCard.addEventListener('click', () => showDetailView('alta'));
    bajaProduccionCard.addEventListener('click', () => showDetailView('baja'));
    zonasRiesgoCard.addEventListener('click', () => showDetailView('riesgo'));

    // Event listeners para los botones del menú
    [btnAltaProduccion, btnBajaProduccion, btnZonasRiesgo].forEach((btn, i) => {
        const views = ['alta', 'baja', 'riesgo'];
        btn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevenir comportamiento por defecto del enlace
            showDetailView(views[i]);
            if (window.innerWidth < 768) {
                sideMenu.classList.remove('active');
                toggleMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
                mainContent.classList.remove('shifted');
            }
        });
    });

    // Mostrar vista por defecto al cargar
    showDetailView('alta');

    // Gestión de responsive
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sideMenu.classList.remove('active');
            toggleMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            mainContent.classList.remove('shifted');
        }
    });

    // Instancia global del escáner QR
    let html5QrCode = null;

    // Función para abrir el escáner QR
    window.openQRScanner = function() {
        qrModal.classList.add('active');
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("qr-reader");
        }
        const qrConfig = { fps: 10, qrbox: 250 };
        const qrResult = document.getElementById('qr-result');

        html5QrCode.start(
            { facingMode: "environment" },
            qrConfig,
            (decodedText) => {
                qrResult.textContent = "Código QR detectado: " + decodedText;
                qrResult.classList.add('active');
                html5QrCode.stop().catch(err => console.error("Error al detener el escáner QR", err));
            },
            (errorMessage) => console.log(errorMessage)
        ).catch(err => console.error("Error al iniciar el escáner QR", err));
    };

    // Cerrar el modal QR
    closeModalBtn.addEventListener('click', function() {
        qrModal.classList.remove('active');
        if (html5QrCode) {
            html5QrCode.stop().catch(err => console.warn("No se pudo detener el escáner QR", err));
            html5QrCode = null; // Limpiar la instancia
        }
    });
});
</script>


