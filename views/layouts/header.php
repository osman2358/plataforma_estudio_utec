<?php
// Configuración dinámica de la ruta base del proyecto para evitar enlaces rotos.
// Asegúrate de que la carpeta en htdocs se llame exactamente 'plataforma_estudio'.
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$folder = "/plataforma_estudio"; 
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
                <ul class="navbar-nav">
                    <!-- Botones de Autenticación -->
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary me-2 px-3 mb-2 mb-lg-0" href="<?= $base_url ?>/views/auth/login.php">Ingresar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3" href="<?= $base_url ?>/views/auth/registro.php">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>