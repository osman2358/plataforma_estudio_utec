<?php 
session_start();
include '../layouts/header.php'; 
?>

<main class="container my-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card p-4 shadow" style="width: 100%; max-width: 500px;">
        <div class="text-center mb-4">
            <h2 class="form-label fs-3">Crear Cuenta</h2>
            <p class="text-muted">Regístrate para unirte o crear grupos de estudio.</p>
        </div>

        <?php if(isset($_SESSION['error_registro'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= $_SESSION['error_registro']; ?>
            </div>
            <?php unset($_SESSION['error_registro']); ?>
        <?php endif; ?>

        <form action="../../controllers/AuthController.php" method="POST">
            <input type="hidden" name="accion" value="registro">
            
            <div class="mb-3">
                <label for="carne" class="form-label">Carné Estudiantil</label>
                <input type="text" class="form-control" id="carne" name="carne" placeholder="Ej. 29-1564-2025" required>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Escribe tu nombre completo" required>
            </div>
            
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Universitario</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@mail.utec.edu.sv" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" minlength="8" placeholder="Mínimo 8 caracteres" required>
            </div>
            
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-accent">Registrarse</button>
            </div>
            
            <div class="text-center mt-3">
                <small>¿Ya tienes cuenta? <a href="login.php" class="text-decoration-none">Inicia sesión aquí</a></small>
            </div>
        </form>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>