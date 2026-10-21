<?php
session_start();

// Validación de sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Validar que se reciba el ID del grupo por la URL (ej. grupo_detalle.php?id=5)
$id_grupo = $_GET['id'] ?? null;
if (!$id_grupo) {
    header('Location: dashboard.php');
    exit();
}

// Incluir e instanciar todos los modelos necesarios
require_once '../../models/GrupoEstudio.php';
require_once '../../models/MiembroGrupo.php';
require_once '../../models/MensajeForo.php';
require_once '../../models/MaterialApoyo.php';

$grupoModel = new GrupoEstudio();
$miembroModel = new MiembroGrupo();
$foroModel = new MensajeForo();
$materialModel = new MaterialApoyo();

// Obtener la información general del grupo
$grupo = $grupoModel->obtenerPorId($id_grupo);

// Validar seguridad: ¿El usuario actual es miembro de este grupo?
$es_miembro = $miembroModel->esMiembro($_SESSION['usuario_id'], $id_grupo);

if (!$grupo || !$es_miembro) {
    $_SESSION['error_grupo'] = "Acceso denegado: No perteneces a este grupo o el grupo no existe.";
    header('Location: grupos_lista.php');
    exit();
}

// Cargar el contenido del grupo
$mensajes = $foroModel->obtenerPorGrupo($id_grupo);
$materiales = $materialModel->obtenerPorGrupo($id_grupo);

include '../layouts/header.php'; 
?>

<main class="container my-5" style="min-height: 60vh;">
    <!-- Cabecera del Grupo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-left: 5px solid var(--color-secundario) !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1" style="color: var(--color-primario);"><?= htmlspecialchars($grupo['nombre_grupo']); ?></h2>
                            <p class="text-muted mb-0">
                                <strong>Asignatura:</strong> <?= htmlspecialchars($grupo['nombre_asignatura']); ?> | 
                                <strong>Tema:</strong> <?= htmlspecialchars($grupo['tema_especifico'] ?: 'Estudio general'); ?>
                            </p>
                        </div>
                        <a href="dashboard.php" class="btn btn-outline-secondary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas del sistema para foro y material -->
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

    <div class="row g-4">
        <!-- Columna Izquierda: Foro de Mensajes -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom pt-3 pb-2">
                    <h5 class="fw-bold" style="color: var(--color-primario);">Foro de Discusión</h5>
                </div>
                <div class="card-body d-flex flex-column" style="max-height: 500px; overflow-y: auto;">
                    
                    <!-- Historial de mensajes -->
                    <?php if (empty($mensajes)): ?>
                        <p class="text-muted text-center mt-4">Aún no hay mensajes. ¡Sé el primero en saludar!</p>
                    <?php else: ?>
                        <?php foreach ($mensajes as $msg): ?>
                            <div class="mb-3 p-3 rounded" style="background-color: var(--color-fondo);">
                                <div class="d-flex justify-content-between">
                                    <strong style="color: var(--color-primario);"><?= htmlspecialchars($msg['nombre_autor']); ?></strong>
                                    <small class="text-muted"><?= date('d M, h:i A', strtotime($msg['fecha_envio'])); ?></small>
                                </div>
                                <p class="mb-0 mt-1"><?= nl2br(htmlspecialchars($msg['contenido'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
                <div class="card-footer bg-white border-top p-3">
                    <!-- Formulario para enviar mensaje -->
                    <form action="../../controllers/ForoController.php" method="POST">
                        <input type="hidden" name="accion" value="enviar_mensaje">
                        <input type="hidden" name="id_grupo" value="<?= $grupo['id']; ?>">
                        
                        <div class="input-group">
                            <label for="mensaje_foro_detalle" class="visually-hidden">Escribe un mensaje</label>
                            <input type="text" class="form-control" id="mensaje_foro_detalle" name="contenido" placeholder="Escribe tu mensaje aquí..." required>
                            <button class="btn btn-primary" type="submit">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Materiales de Apoyo -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom pt-3 pb-2">
                    <h5 class="fw-bold" style="color: var(--color-primario);">Materiales de Apoyo</h5>
                </div>
                <div class="card-body">
                    
                    <!-- Formulario para subir recurso -->
                    <form action="../../controllers/MaterialController.php" method="POST" class="mb-4 bg-light p-3 rounded">
                        <input type="hidden" name="accion" value="subir_material">
                        <input type="hidden" name="id_grupo" value="<?= $grupo['id']; ?>">
                        
                        <div class="mb-2">
                            <label for="titulo_material_detalle" class="form-label small fw-bold">Título del Recurso</label>
                            <input type="text" class="form-control form-control-sm" id="titulo_material_detalle" name="titulo" placeholder="Ej. Presentación Canva" required>
                        </div>
                        <div class="mb-3">
                            <label for="enlace_material_detalle" class="form-label small fw-bold">Enlace (URL)</label>
                            <input type="url" class="form-control form-control-sm" id="enlace_material_detalle" name="enlace_recurso" placeholder="https://..." required>
                        </div>
                        <button type="submit" class="btn btn-accent btn-sm w-100">Compartir Recurso</button>
                    </form>

                    <!-- Lista de Materiales -->
                    <?php if (empty($materiales)): ?>
                        <p class="text-muted text-center small">No hay materiales compartidos todavía.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($materiales as $mat): ?>
                                <li class="list-group-item px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?= htmlspecialchars($mat['enlace_recurso']); ?>" target="_blank" class="text-decoration-none fw-bold" style="color: var(--color-secundario);">
                                            <?= htmlspecialchars($mat['titulo']); ?>
                                        </a>
                                        <small class="text-muted">Por <?= htmlspecialchars($mat['nombre_autor']); ?></small>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>