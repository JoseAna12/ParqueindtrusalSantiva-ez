<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parque Industrial Santivañez</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0c4a6e;
            --secondary-color: #0ea5e9;
            --accent-color: #f0f9ff;
            --dark-color: #0f172a;
            --light-color: #f8fafc;
            --gradient-bg: linear-gradient(120deg, var(--primary-color), #164e63);
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #334155;
            overflow-x: hidden;
        }

        .btn-primary {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }

        .hero-section {
            background: linear-gradient(rgba(12, 74, 110, 0.8), rgba(12, 74, 110, 0.9)), url('login/img/san.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--light-color), transparent);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            margin-bottom: 2rem;
        }

        .hero-text {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .scroll-down {
            position: absolute;
            bottom: 120px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            color: white;
            font-size: 2rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0) translateX(-50%);
            }
            40% {
                transform: translateY(-30px) translateX(-50%);
            }
            60% {
                transform: translateY(-15px) translateX(-50%);
            }
        }

        .section-spacing {
            padding: 6rem 0;
        }

        .section-title {
            color: var(--primary-color);
            position: relative;
            padding-bottom: 20px;
            margin-bottom: 40px;
            font-weight: 600;
            font-size: 2.5rem;
            text-align: center;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-bg);
            border-radius: 2px;
        }

        .carousel-container {
            background-color: var(--light-color);
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .carousel-container::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(to bottom, transparent, var(--light-color));
        }

        .custom-carousel {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .custom-carousel .carousel-item img {
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            padding: 20px;
            bottom: 40px;
        }

        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 5px;
        }

        .about-img {
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .about-img:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .check-list li {
            padding: 10px 0;
            display: flex;
            align-items: center;
        }

        .check-icon {
            color: var(--secondary-color);
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .feature-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .feature-card .card-body {
            padding: 2rem;
        }

        .feature-icon {
            background: var(--gradient-bg);
            color: white;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 20px rgba(12, 74, 110, 0.3);
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            height: 100%;
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .service-icon {
            color: var(--secondary-color);
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .price-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            text-align: center;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .price-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: var(--gradient-bg);
        }

        .price-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 1.5rem 0;
        }

        .price-currency {
            font-size: 1.5rem;
            vertical-align: super;
        }

        .price-period {
            font-size: 1rem;
            color: #64748b;
        }

        .price-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
        }

        .stats-section {
            background: var(--gradient-bg);
            color: white;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(to bottom, transparent, var(--primary-color));
            opacity: 0.5;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-10px);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .stat-title {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .mission-vision-section {
            background-color: var(--light-color);
            padding: 6rem 0;
        }

        .contact-section {
            background: white;
            padding: 5rem 0;
        }

        .contact-info {
            background: var(--accent-color);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .contact-icon {
            color: var(--secondary-color);
            font-size: 1.5rem;
            margin-right: 1rem;
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.2);
        }

        .contact-text {
            flex: 1;
        }

        .contact-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 2rem;
        }

        .social-link {
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .social-link:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-5px);
        }

        footer {
            background: var(--dark-color);
            color: white;
            padding: 4rem 0 2rem;
        }

        .footer-title {
            color: white;
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--secondary-color);
            padding-left: 5px;
        }

        .copyright {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 3rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--secondary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
            transition: all 0.3s ease;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
        }

        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--primary-color);
            transform: translateY(-5px);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-text {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .custom-carousel .carousel-item img {
                height: 300px;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <?php require('./layout/topbar.php'); ?>
    <?php require('./layout/sidebar.php'); ?>

    <!-- Hero Section -->
    <section class="hero-section text-center" id="inicio">
        <div class="container hero-content">
            <div data-aos="fade-up" data-aos-duration="1200">
                <h1 class="hero-title">Parque Industrial Santivañez</h1>
                <p class="hero-text">El futuro del desarrollo industrial en Bolivia comienza aquí. Infraestructura de clase mundial para impulsar el crecimiento económico del país.</p>
                <a href="#nosotros" class="btn btn-primary btn-lg">Descubre más</a>
            </div>
        </div>
        <a href="#carousel" class="scroll-down">
            <i class="fas fa-chevron-down"></i>
        </a>
    </section>

    <!-- Carrusel Mejorado -->
    <div class="carousel-container" id="carousel">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Nuestras Instalaciones</h2>
        </div>
        <div class="custom-carousel" data-aos="fade-up" data-aos-delay="200">
            <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="login/img/aji.jpg" class="d-block w-100" alt="Instalaciones">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Instalaciones de Primer Nivel</h5>
                            <p>Infraestructura moderna diseñada para satisfacer las necesidades empresariales</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="login/img/ti.jpg" class="d-block w-100" alt="Infraestructura">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Servicios Básicos Garantizados</h5>
                            <p>Todos los servicios para el óptimo funcionamiento de tu empresa</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="login/img/yi.jpg" class="d-block w-100" alt="Tecnología">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Acceso Estratégico</h5>
                            <p>Ubicación privilegiada con conexiones logísticas eficientes</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="login/img/sal.jpg" class="d-block w-100" alt="Servicios">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Soluciones Integrales</h5>
                            <p>Acompañamiento completo para el éxito de tu proyecto industrial</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Sección Acerca de -->
    <section class="section-spacing" id="nosotros">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Acerca de Nosotros</h2>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    <img src="login/img/sani.jpg" alt="Infraestructura" class="img-fluid about-img">
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <h3 class="mb-4" style="color: var(--primary-color);">La Nueva Era Industrial de Bolivia</h3>
                    <p class="lead mb-4">
                        El Parque Industrial Santivañez representa la evolución del desarrollo industrial en Bolivia. 
                        Ubicado estratégicamente en Cochabamba, ofrecemos una plataforma completa para el crecimiento 
                        empresarial sostenible.
                    </p>
                    <ul class="list-unstyled check-list">
                        <li><i class="fas fa-check-circle check-icon"></i> Más de 100 hectáreas de terreno industrial</li>
                        <li><i class="fas fa-check-circle check-icon"></i> Infraestructura de primer nivel con estándares internacionales</li>
                        <li><i class="fas fa-check-circle check-icon"></i> Conexiones logísticas estratégicas para optimizar distribución</li>
                        <li><i class="fas fa-check-circle check-icon"></i> Servicios básicos garantizados 24/7</li>
                        <li><i class="fas fa-check-circle check-icon"></i> Asesoría legal y técnica especializada</li>
                    </ul>
                    <a href="#servicios" class="btn btn-primary mt-3">Nuestros Servicios</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Misión y Visión -->
    <section class="mission-vision-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Misión y Visión</h2>
            <div class="row g-4">
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card card h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h3 class="mb-4" style="color: var(--primary-color);">Nuestra Misión</h3>
                            <p class="lead">
                                Impulsar el desarrollo industrial sostenible de Bolivia, proporcionando infraestructura 
                                de clase mundial y servicios integrales que faciliten el crecimiento empresarial y 
                                la generación de empleo de calidad.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card card h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="mb-4" style="color: var(--primary-color);">Nuestra Visión</h3>
                            <p class="lead">
                                Ser reconocidos como el parque industrial líder en Bolivia, referente en innovación, 
                                sostenibilidad y desarrollo tecnológico, contribuyendo significativamente al 
                                crecimiento económico del país.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Servicios -->
    <section class="section-spacing bg-light" id="servicios">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Servicios Ofrecidos</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h4 class="mb-4" style="color: var(--primary-color);">Asesoría Legal y Gestión de Trámites</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Registro de propiedad en Derechos Reales</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Aprobación de planos de lote y construcción</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Trámite de Licencia de Funcionamiento</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Legalizaciones en diferentes instituciones</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Asesoría Legal en general</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-city"></i>
                        </div>
                        <h4 class="mb-4" style="color: var(--primary-color);">Urbanismo Industrial</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Visación de plano de lote</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Visación de plano de construcción</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Amojonamiento de lotes</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Línea y Nivel</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Planificación espacial optimizada</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-water"></i>
                        </div>
                        <h4 class="mb-4" style="color: var(--primary-color);">Servicios Generales</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Instalación de alcantarillado sanitario</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Instalación de acometida de agua</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Tratamiento de aguas residuales</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Mantenimiento de áreas comunes</li>
                            <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--secondary-color);"></i> Seguridad industrial y monitoreo</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Costo del Terreno -->
    <section class="section-spacing" id="precios">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Costo del Terreno</h2>
            <div class="row g-4">
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="price-card">
                        <h3 class="price-title">PREDIOS HASTA 1500 m²</h3>
                        <div class="price-value">
                            <span class="price-currency">UFV</span> 30
                            <span class="price-period">/ m² a cuotas</span>
                        </div>
                        <div class="price-value">
                            <span class="price-currency">UFV</span> 15
                            <span class="price-period">/ m² al contado</span>
                        </div>
                        <p class="mt-4">Ideal para pequeñas y medianas empresas que buscan establecerse en un entorno industrial de primer nivel.</p>
                        <a href="#contacto" class="btn btn-primary mt-3">Contactar</a>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="price-card">
                        <h3 class="price-title">PREDIOS MÁS DE 1500 m²</h3>
                        <div class="price-value">
                            <span class="price-currency">UFV</span> 40
                            <span class="price-period">/ m² a cuotas</span>
                        </div>
                        <div class="price-value">
                            <span class="price-currency">UFV</span> 20
                            <span class="price-period">/ m² al contado</span>
                        </div>
                        <p class="mt-4">Solución óptima para grandes empresas o industrias que requieren amplios espacios para sus operaciones.</p>
                        <a href="#contacto" class="btn btn-primary mt-3">Contactar</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Estadísticas -->
    <section class="stats-section" id="estadisticas">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <div class="stat-number">
                            <span class="counter">100</span>+
                        </div>
                        <div class="stat-title">Hectáreas de Terreno</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <div class="stat-number">
                            <span class="counter">50</span>+
                        </div>
                        <div class="stat-title">Empresas Instaladas</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <div class="stat-number">
                            <span class="counter">1000</span>+
                        </div>
                        <div class="stat-title">Empleos Generados</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-title">Seguridad y Soporte</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Oficina de Enlace / Contacto -->
<section class="contact-section" id="contacto">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Contáctanos</h2>
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-info">
                    <h3 class="mb-4" style="color: var(--primary-color);">Oficina de Enlace</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <div class="contact-label">Dirección</div>
                            <p>Av. Pando Nro 1185 Edif. FEPC</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-text">
                            <div class="contact-label">Teléfono</div>
                            <p>(+591) 4662866</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <div class="contact-label">Email</div>
                            <p><a href="mailto:info@parqueindustrialsantivanez.com" style="color: inherit; text-decoration: none;">info@parqueindustrialsantivanez.com</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-text">
                            <div class="contact-label">WhatsApp</div>
                            <p><a href="https://wa.me/59161022000" style="color: inherit; text-decoration: none;">(+591) 61022000</a></p>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="https://facebook.com/ParqueIndustrialSantiváñez" target="_blank" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="card-body">
                        <h3 class="mb-4" style="color: var(--primary-color);">Envíanos un mensaje</h3>
                        <form id="contactForm" action="procesar_contacto.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre completo" required>
                                        <label for="nombre">Nombre completo</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico" required>
                                        <label for="email">Correo electrónico</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto" required>
                                <label for="asunto">Asunto</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="mensaje" name="mensaje" style="height: 150px" placeholder="Mensaje" required></textarea>
                                <label for="mensaje">Mensaje</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Enviar mensaje</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="footer-title">Parque Industrial Santivañez</h4>
                    <p>El futuro del desarrollo industrial en Bolivia comienza aquí. Infraestructura de clase mundial para impulsar el crecimiento económico del país.</p>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h4 class="footer-title">Enlaces</h4>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#inicio"><i class="fas fa-chevron-right me-2"></i> Inicio</a></li>
                        <li><a href="#nosotros"><i class="fas fa-chevron-right me-2"></i> Nosotros</a></li>
                        <li><a href="#servicios"><i class="fas fa-chevron-right me-2"></i> Servicios</a></li>
                        <li><a href="#precios"><i class="fas fa-chevron-right me-2"></i> Precios</a></li>
                        <li><a href="#contacto"><i class="fas fa-chevron-right me-2"></i> Contacto</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-title">Servicios</h4>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Asesoría Legal</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Urbanismo Industrial</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Servicios Básicos</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Seguridad Industrial</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-title">Horario de Atención</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">Lunes - Viernes: 8:30 AM - 5:30 PM</li>
                        <li class="mb-2">Sábados: 9:00 AM - 1:00 PM</li>
                        <li>Domingos: Cerrado</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2025 Parque Industrial Santivañez. Todos los derechos reservados JCTV.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <?php require('./layout/footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            easing: 'ease-in-out'
        });

        // Counter Up
        $('.counter').counterUp({
            delay: 10,
            time: 1500
        });

        // Back to top button
        const backToTopButton = document.querySelector('.back-to-top');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('active');
            } else {
                backToTopButton.classList.remove('active');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>