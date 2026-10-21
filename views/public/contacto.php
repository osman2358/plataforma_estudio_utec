<?php 
session_start();
include '../layouts/header.php'; 
?>

<main class="container my-5" style="min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold" style="color: var(--color-primario);">Contáctanos</h1>
                <p class="text-muted">¿Tienes algún problema con la plataforma o alguna sugerencia? Escríbenos.</p>
            </div>

  
            <?php if(isset($_SESSION['exito_contacto'])): ?>
                <div class="alert alert-success shadow-sm" role="alert">
                    <?= $_SESSION['exito_contacto']; ?>
                </div>
                <?php unset($_SESSION['exito_contacto']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['error_contacto'])): ?>
                <div class="alert alert-danger shadow-sm" role="alert">
                    <?= $_SESSION['error_contacto']; ?>
                </div>
                <?php unset($_SESSION['error_contacto']); ?>
            <?php endif; ?>
        

            <div class="card p-4 shadow border-0">
                <form action="../../controllers/ContactoController.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre_contacto" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_contacto" name="nombre_contacto" placeholder="Tu nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="correo_contacto" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo_contacto" name="correo_contacto" placeholder="ejemplo@mail.com" required>
                        </div>
                        <div class="col-12">
                            <label for="asunto_contacto" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="asunto_contacto" name="asunto_contacto" placeholder="¿Sobre qué nos escribes?" required>
                        </div>
                        <div class="col-12">
                            <label for="mensaje_contacto" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje_contacto" name="mensaje_contacto" rows="5" placeholder="Detalla tu consulta o sugerencia aquí..." required></textarea>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Enviar Mensaje</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>