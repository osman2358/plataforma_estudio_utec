<?php
// Iniciar sesión de forma segura si no se ha iniciado antes en el archivo que llama al header
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración dinámica de la ruta base del proyecto para evitar enlaces rotos.
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$folder = "/plataforma_estudio_utec"; 
$base_url = $protocol . "://" . $host . $folder;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de Grupos de Estudio</title>
    
    <!-- Favicon (Requisito de Rúbrica) -->
    <link rel="icon" type="image/x-icon" href="<?= $base_url ?>/assets/img/favicon.ico">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Estilos Personalizados -->
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/estilos.css">
</head>
<body>

<!-- Encabezado Semántico (Requisito WCAG) -->
<header>
    <!-- Menú de navegación responsivo (Requisito de Rúbrica) -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="<?= $base_url ?>/index.php">Plataforma UTEC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Menú de navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>/index.php">Inicio</a>
                    </li>
                    
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-primary" href="<?= $base_url ?>/views/app/dashboard.php">Mi Panel</a>
                    </li>
                    <?php endif; ?>


                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>/views/public/quienes_somos.php">Quiénes somos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>/views/public/servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>/views/public/contacto.php">Contacto</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <!-- Si el usuario inició sesión, mostramos su nombre y botón de salir -->
                        <li class="nav-item me-3 d-flex align-items-center mb-2 mb-lg-0">
                            <span class="text-muted fw-bold">Hola, <?= htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger px-3 w-100" href="<?= $base_url ?>/controllers/AuthController.php?accion=logout">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <!-- Si no ha iniciado sesión, mostramos los botones normales -->
                        <li class="nav-item me-2 mb-2 mb-lg-0">
                            <a class="btn btn-outline-primary px-3 w-100" href="<?= $base_url ?>/views/auth/login.php">Ingresar</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary px-3 w-100" href="<?= $base_url ?>/views/auth/registro.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>