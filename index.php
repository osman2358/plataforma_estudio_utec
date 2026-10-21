<?php 
session_start();
include 'views/layouts/header.php'; 
?>

<!-- Estilos específicos para el carrusel -->
<style>
    .carousel-item img {
        height: 60vh; /* Altura responsiva */
        min-height: 400px;
        object-fit: cover; /* Evita que la imagen se deforme */
        filter: brightness(0.6); /* Oscurece la imagen para que el texto blanco sea legible (WCAG Contraste) */
    }
</style>

<main>
    <!-- Carrusel de Bootstrap (Requisito de Rúbrica) -->
    <div id="carruselInicio" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicadores inferiores -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carruselInicio" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Diapositiva 1"></button>
            <button type="button" data-bs-target="#carruselInicio" data-bs-slide-to="1" aria-label="Diapositiva 2"></button>
            <button type="button" data-bs-target="#carruselInicio" data-bs-slide-to="2" aria-label="Diapositiva 3"></button>
        </div>
        
    <!-- Contenedor de las imágenes -->
        <div class="carousel-inner">
            <!-- Diapositiva 1 -->
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1920&auto=format&fit=crop" class="d-block w-100" alt="Estudiantes universitarios trabajando en equipo de forma colaborativa">
                <!-- Se eliminó d-none d-md-block y se añadieron clases de padding y texto responsivo -->
                <div class="carousel-caption pb-4">
                    <h2 class="fw-bold text-white h3">Trabajo Colaborativo</h2>
                    <p class="text-white small mb-0">Únete a grupos de estudio específicos para tus materias y alcanza tus metas académicas.</p>
                </div>
            </div>
            
            <!-- Diapositiva 2 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1920&auto=format&fit=crop" class="d-block w-100" alt="Persona estudiando en línea desde su computadora portátil">
                <div class="carousel-caption pb-4">
                    <h2 class="fw-bold text-white h3">Educación Virtual</h2>
                    <p class="text-white small mb-0">Herramientas diseñadas para facilitar el aprendizaje a distancia y la comunicación asíncrona.</p>
                </div>
            </div>
            
            <!-- Diapositiva 3 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=1920&auto=format&fit=crop" class="d-block w-100" alt="Libros y libretas en un escritorio de estudio">
                <div class="carousel-caption pb-4">
                    <h2 class="fw-bold text-white h3">Material Centralizado</h2>
                    <p class="text-white small mb-0">Comparte y organiza apuntes, enlaces y documentos importantes en un solo lugar.</p>
                </div>
            </div>
        </div>
        
        <!-- Controles de navegación -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carruselInicio" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carruselInicio" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <!-- Sección de Llamado a la Acción (CTA) -->
    <div class="container my-5 py-5 text-center">
        <h1 class="display-5 fw-bold" style="color: var(--color-primario);">Conecta con tus compañeros</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4 text-muted">
                Nuestra plataforma te permite encontrar estudiantes de tu misma asignatura, crear salas de discusión, 
                compartir material de apoyo y resolver dudas académicas de manera eficiente y segura.
            </p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <a href="views/app/dashboard.php" class="btn btn-primary btn-lg px-4 gap-3">Ir a mi Panel</a>
                <?php else: ?>
                    <a href="views/auth/registro.php" class="btn btn-primary btn-lg px-4 gap-3">Crear Cuenta</a>
                    <a href="views/auth/login.php" class="btn btn-outline-secondary btn-lg px-4">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include 'views/layouts/footer.php'; ?>