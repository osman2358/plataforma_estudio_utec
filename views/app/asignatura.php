<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/Asignatura.php';
$asignaturaModel = new Asignatura();
$asignaturas = $asignaturaModel->obtenerActivas();

include '../layouts/header.php'; 
?>

<main class="container my-5" style="min-height: 60vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="form-label fs-3 mb-0" style="color: var(--color-primario);">Gestión de Asignaturas</h2>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <!-- Alertas -->
    <?php if(isset($_SESSION['exito_asignatura'])): ?>
        <div class="alert alert-success shadow-sm" role="alert"><?= $_SESSION['exito_asignatura']; ?></div>
        <?php unset($_SESSION['exito_asignatura']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_asignatura'])): ?>
        <div class="alert alert-danger shadow-sm" role="alert"><?= $_SESSION['error_asignatura']; ?></div>
        <?php unset($_SESSION['error_asignatura']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Columna Izquierda: Formulario de Creación -->
        <div class="col-lg-4">
            <div class="card p-4 shadow-sm border-0">
                <h5 class="fw-bold mb-3" style="color: var(--color-primario);">Añadir Nueva</h5>
                <form action="../../controllers/AsignaturaController.php" method="POST">
                    <input type="hidden" name="accion" value="crear_asignatura">
                    
                    <div class="mb-3">
                        <label for="codigo_materia" class="form-label small">Código</label>
                        <input type="text" class="form-control form-control-sm" id="codigo_materia" name="codigo_materia" placeholder="Ej. DPW-101" required>
                    </div>

                    <div class="mb-4">
                        <label for="nombre_materia" class="form-label small">Nombre Completo</label>
                        <input type="text" class="form-control form-control-sm" id="nombre_materia" name="nombre" placeholder="Ej. Desarrollo web" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>
            </div>
        </div>

        <!-- Columna Derecha: Tabla de Listado -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Asignatura</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($asignaturas)): ?>
                                    <tr><td colspan="3" class="text-center text-muted">No hay asignaturas registradas.</td></tr>
                                <?php else: ?>
                                    <?php foreach($asignaturas as $asig): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($asig['codigo_materia']); ?></span></td>
                                        <td><?= htmlspecialchars($asig['nombre']); ?></td>
                                        <td class="text-end">
                                            
                                            <!-- Botón Editar (Abre el Modal) -->
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $asig['id']; ?>">
                                                Editar
                                            </button>

                                            <!-- Botón Eliminar -->
                                            <form action="../../controllers/AsignaturaController.php" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta asignatura? No se podrá borrar si ya tiene grupos vinculados.');">
                                                <input type="hidden" name="accion" value="eliminar_asignatura">
                                                <input type="hidden" name="id" value="<?= $asig['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Borrar</button>
                                            </form>

                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ZONA DE MODALES (Fuera de la tabla HTML)   -->

    <?php if(!empty($asignaturas)): ?>
        <?php foreach($asignaturas as $asig): ?>
        <div class="modal fade" id="modalEditar<?= $asig['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: var(--color-primario);">Editar Asignatura</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <form action="../../controllers/AsignaturaController.php" method="POST">
                        <div class="modal-body text-start">
                            <input type="hidden" name="accion" value="actualizar_asignatura">
                            <input type="hidden" name="id" value="<?= $asig['id']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label small">Código</label>
                                <input type="text" class="form-control" name="codigo_materia" value="<?= htmlspecialchars($asig['codigo_materia']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Nombre Completo</label>
                                <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($asig['nombre']); ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</main>

<?php include '../layouts/footer.php'; ?>