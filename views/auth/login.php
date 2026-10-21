<?php 
// Opcional: Iniciar sesión para mostrar mensajes de error si los hay
session_start();
include '../layouts/header.php'; 
?>

<main class="container my-5 d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <h2 class="form-label fs-3">Iniciar Sesión</h2>
            <p class="text-muted">Ingresa a tu cuenta para acceder a tus grupos de estudio.</p>
        </div>

        <?php if(isset($_SESSION['error_login'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= $_SESSION['error_login']; ?>
            </div>
            <?php unset($_SESSION['error_login']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['exito_login'])): ?>
            <div class="alert alert-success" role="alert">
                <?= $_SESSION['exito_login']; ?>
            </div>
            <?php unset($_SESSION['exito_login']); ?>
        <?php endif; ?>

        <!-- El formulario envía los datos al controlador mediante POST -->
        <form action="../../controllers/AuthController.php" method="POST">
            <input type="hidden" name="accion" value="login">
            
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Universitario</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@mail.utec.edu.sv" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
            </div>
            
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </div>
            
            <div class="text-center mt-3">
                <small>¿No tienes cuenta? <a href="registro.php" class="text-decoration-none">Regístrate aquí</a></small>
            </div>
        </form>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>