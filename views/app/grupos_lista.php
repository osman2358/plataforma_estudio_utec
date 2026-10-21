<?php
session_start();

// Validar inicio de sesión
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error_login'] = "Debes iniciar sesión para explorar los grupos.";
    header('Location: ../auth/login.php');
    exit();
}

require_once '../../models/GrupoEstudio.php';
require_once '../../models/MiembroGrupo.php';

$grupoModel = new GrupoEstudio();
$miembroModel = new MiembroGrupo();

$id_usuario = $_SESSION['usuario_id'];
$filtro = $_GET['filtro'] ?? 'todos'; // Puede ser 'todos' o 'mis_grupos'

// Obtenemos todos los grupos disponibles desde el modelo
$todos_los_grupos = $grupoModel->obtenerTodosDisponibles();
$grupos_mostrar = [];

// Filtramos la información según lo que el usuario quiere ver
foreach ($todos_los_grupos as $grupo) {
    // Verificamos si el usuario actual ya es miembro de este grupo iterado
    $es_miembro = $miembroModel->esMiembro($id_usuario, $grupo['id']);
    $grupo['es_miembro'] = $es_miembro; // Guardamos el booleano en el arreglo para usarlo en el HTML
    
    if ($filtro === 'mis_grupos') {
        if ($es_miembro) {
            $grupos_mostrar[] = $grupo;
        }
    } else {
        $grupos_mostrar[] = $grupo;
    }
}

include '../layouts/header.php'; 
?>

<main class="container my-5" style="min-height: 60vh;">
    <!-- Encabezado y Filtros -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="display-6 fw-bold" style="color: var(--color-primario);">
                <?= $filtro === 'mis_grupos' ? 'Mis Grupos de Estudio' : 'Explorar Grupos'; ?>
            </h1>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="grupos_lista.php?filtro=todos" class="btn <?= $filtro === 'todos' ? 'btn-primary' : 'btn-outline-primary'; ?> me-2">Todos los Grupos</a>
            <a href="grupos_lista.php?filtro=mis_grupos" class="btn <?= $filtro === 'mis_grupos' ? 'btn-primary' : 'btn-outline-primary'; ?>">Mis Grupos</a>
            <a href="grupo_crear.php" class="btn btn-accent ms-2">+ Nuevo</a>
        </div>
    </div>

    <!-- Buscador en tiempo real -->
    <div class="row mb-4">
        <div class="col-12">
            <label for="buscador_grupos_lista" class="visually-hidden">Buscar grupo o asignatura</label>
            <input type="text" class="form-control form-control-lg shadow-sm border-0" id="buscador_grupos_lista" placeholder="Buscar por asignatura o nombre del grupo...">
        </div>
    </div>

    <!-- Alertas del sistema (Éxito o Error) -->
    <?php if(isset($_SESSION['exito_grupo'])): ?>
        <div class="alert alert-success shadow-sm" role="alert">
            <?= $_SESSION['exito_grupo']; ?>
        </div>
        <?php unset($_SESSION['exito_grupo']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_grupo'])): ?>
        <div class="alert alert-danger shadow-sm" role="alert">
            <?= $_SESSION['error_grupo']; ?>
        </div>
        <?php unset($_SESSION['error_grupo']); ?>
    <?php endif; ?>

    <!-- Cuadrícula de Tarjetas de Grupos -->
    <div class="row g-4" id="contenedor_tarjetas_grupos">
        <?php if(empty($grupos_mostrar)): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">No se encontraron grupos disponibles.</p>
                <?php if($filtro === 'mis_grupos'): ?>
                    <a href="grupos_lista.php?filtro=todos" class="btn btn-outline-primary">Buscar grupos para unirme</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach($grupos_mostrar as $grupo): ?>
                <!-- El atributo data-busqueda almacena el texto para que el JS lo filtre fácilmente -->
                <div class="col-md-6 col-lg-4 tarjeta-grupo-item" data-busqueda="<?= strtolower(htmlspecialchars($grupo['nombre_grupo'] . ' ' . $grupo['nombre_asignatura'])); ?>">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($grupo['nombre_asignatura']); ?></span>
                            <h5 class="card-title fw-bold" style="color: var(--color-primario);"><?= htmlspecialchars($grupo['nombre_grupo']); ?></h5>
                        </div>
                        <div class="card-body py-2">
                            <p class="card-text text-muted small mb-2">
                                <strong>Tema:</strong> <?= htmlspecialchars($grupo['tema_especifico'] ?: 'General'); ?><br>
                                <strong>Creador:</strong> <?= htmlspecialchars($grupo['nombre_creador'] ?: 'Desconocido'); ?>
                            </p>
                            
                            <!-- Barra de progreso de cupos -->
                            <?php 
                                $porcentaje = ($grupo['actuales'] / $grupo['max_integrantes']) * 100; 
                                $color_barra = $porcentaje >= 100 ? 'bg-danger' : 'bg-success';
                            ?>
                            <div class="d-flex justify-content-between align-items-center mb-1 small">
                                <span class="text-muted">Cupos: <?= $grupo['actuales']; ?> / <?= $grupo['max_integrantes']; ?></span>
                                <span class="<?= $porcentaje >= 100 ? 'text-danger' : 'text-success'; ?> fw-bold"><?= $grupo['estado'] === 'lleno' ? 'Lleno' : 'Abierto'; ?></span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar <?= $color_barra; ?>" role="progressbar" style="width: <?= $porcentaje; ?>%;" aria-valuenow="<?= $grupo['actuales']; ?>" aria-valuemin="0" aria-valuemax="<?= $grupo['max_integrantes']; ?>"></div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-white border-top-0 pb-4 pt-3 text-center">
                            <?php if ($grupo['es_miembro']): ?>
                                <!-- Si ya es miembro, el botón lo lleva al foro -->
                                <a href="grupo_detalle.php?id=<?= $grupo['id']; ?>" class="btn btn-outline-success w-100 fw-bold">Entrar al Foro</a>
                            <?php elseif ($grupo['estado'] === 'lleno'): ?>
                                <!-- Si está lleno y no es miembro, se bloquea la acción -->
                                <button class="btn btn-secondary w-100" disabled>Grupo Lleno</button>
                            <?php else: ?>
                                <!-- Si hay cupo y no es miembro, formulario para unirse -->
                                <form action="../../controllers/GrupoController.php" method="POST">
                                    <input type="hidden" name="accion" value="unirse_grupo">
                                    <input type="hidden" name="id_grupo" value="<?= $grupo['id']; ?>">
                                    <button type="submit" class="btn btn-primary w-100">Unirse al Grupo</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<!-- Script para el buscador en tiempo real -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const buscador = document.getElementById("buscador_grupos_lista");
        const tarjetas = document.querySelectorAll(".tarjeta-grupo-item");

        if(buscador) {
            buscador.addEventListener("keyup", function() {
                const textoBusqueda = this.value.toLowerCase().trim();
                
                tarjetas.forEach(function(tarjeta) {
                    // Leemos la data concatenada que inyectamos en PHP
                    const datos = tarjeta.getAttribute("data-busqueda");
                    
                    if (datos.includes(textoBusqueda)) {
                        tarjeta.style.display = "block"; // Mostrar si coincide
                    } else {
                        tarjeta.style.display = "none";  // Ocultar si no coincide
                    }
                });
            });
        }
    });
</script>

<?php include '../layouts/footer.php'; ?>