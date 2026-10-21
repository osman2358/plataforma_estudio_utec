<?php
session_start();

// Validar que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error_login'] = "Debes iniciar sesión para crear un grupo.";
    header('Location: ../auth/login.php');
    exit();
}

// Incluir el modelo de Asignatura para cargar el select del formulario
require_once '../../models/Asignatura.php';
$asignaturaModel = new Asignatura();
$lista_asignaturas = $asignaturaModel->obtenerActivas();

include '../layouts/header.php'; 
?>

<main class="container my-5" style="min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="form-label fs-3 mb-0" style="color: var(--color-primario);">Crear Nuevo Grupo de Estudio</h2>
                <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
            </div>

            <!-- Mostrar mensajes de error si existen -->
            <?php if(isset($_SESSION['error_grupo'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error_grupo']; ?>
                </div>
                <?php unset($_SESSION['error_grupo']); ?>
            <?php endif; ?>

            <div class="card p-4 shadow border-0">
                <form action="../../controllers/GrupoController.php" method="POST">
                    <input type="hidden" name="accion" value="crear_grupo">
                    
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label for="crear_nombre_grupo" class="form-label">Nombre del Grupo</label>
                            <input type="text" class="form-control" id="crear_nombre_grupo" name="nombre_grupo" placeholder="Ej. Los Hackers de la UTEC, Equipo Alpha..." required>
                        </div>

                        <div class="col-md-6">
                            <label for="crear_id_asignatura" class="form-label">Asignatura</label>
                            <select class="form-select" id="crear_id_asignatura" name="id_asignatura" required>
                                <option value="" selected disabled>Selecciona una materia...</option>
                                <?php foreach ($lista_asignaturas as $asignatura): ?>
                                    <option value="<?= $asignatura['id']; ?>">
                                        <?= htmlspecialchars($asignatura['codigo_materia'] . ' - ' . $asignatura['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="crear_max_integrantes" class="form-label">Límite de Integrantes</label>
                            <input type="number" class="form-control" id="crear_max_integrantes" name="max_integrantes" min="2" max="15" placeholder="Mínimo 2, Máximo 15" required>
                            <div class="form-text">Incluyéndote a ti como creador.</div>
                        </div>

                        <div class="col-md-12">
                            <label for="crear_tema_especifico" class="form-label">Tema Específico (Opcional)</label>
                            <input type="text" class="form-control" id="crear_tema_especifico" name="tema_especifico" placeholder="Ej. Repaso para parcial 2, Proyecto final, etc.">
                        </div>

                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Crear Grupo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>