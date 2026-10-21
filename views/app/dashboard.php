<?php
session_start();

// 1. Protección de la ruta: Validar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, lo enviamos al login con un mensaje de error
    $_SESSION['error_login'] = "Debes iniciar sesión para acceder a tu panel.";
    header('Location: ../auth/login.php');
    exit();
}

include '../layouts/header.php'; 
?>

<main class="container my-5" style="min-height: 60vh;">
    <!-- Banner de Bienvenida -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="p-5 text-white rounded shadow-sm" style="background-color: var(--color-primario);">
                <!-- Imprimimos el nombre del usuario de forma segura usando htmlspecialchars -->
                <h1 class="display-5 fw-bold">¡Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre']); ?>!</h1>
                <p class="lead mt-3">Este es tu panel de control estudiantil. Desde aquí puedes gestionar tus grupos de estudio, interactuar en los foros y compartir material de apoyo con tus compañeros.</p>
            </div>
        </div>
    </div>

    <!-- Sección de Acciones Rápidas (Tarjetas) -->
    <div class="row g-4">
        <!-- Tarjeta 1: Buscar Grupos -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 text-center p-3">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold" style="color: var(--color-primario);">Explorar Grupos</h5>
                    <p class="card-text text-muted mb-4">Encuentra salas de estudio disponibles filtradas por tus asignaturas actuales y únete a ellas.</p>
                    <a href="grupos_lista.php" class="btn btn-outline-primary mt-auto w-100">Buscar Compañeros</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Crear Grupo -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 text-center p-3">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold" style="color: var(--color-primario);">Crear un Grupo</h5>
                    <p class="card-text text-muted mb-4">¿No encuentras lo que buscas? Crea tu propio grupo, define el tema y establece un límite de integrantes.</p>
                    <a href="grupo_crear.php" class="btn btn-primary mt-auto w-100">Nuevo Grupo</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3: Mis Grupos -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 text-center p-3">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold" style="color: var(--color-primario);">Mis Grupos</h5>
                    <p class="card-text text-muted mb-4">Accede directamente a los foros y materiales de los equipos de estudio a los que ya perteneces.</p>
                    <a href="grupos_lista.php?filtro=mis_grupos" class="btn btn-accent mt-auto w-100">Ver Mis Grupos</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>