<?php
session_start();
require_once '../models/MensajeForo.php';

// Validar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // ==========================================
    // PROCESO DE ENVIAR MENSAJE
    // ==========================================
    if ($accion === 'enviar_mensaje') {
        // Validar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ../views/auth/login.php');
            exit();
        }

        $id_usuario = $_SESSION['usuario_id'];
        $id_grupo = $_POST['id_grupo'] ?? '';
        $contenido = trim($_POST['contenido'] ?? '');

        // Validar que no se envíen mensajes vacíos
        if (empty($id_grupo) || empty($contenido)) {
            $_SESSION['error_grupo'] = "No puedes enviar un mensaje vacío.";
            header("Location: ../views/app/grupo_detalle.php?id=" . $id_grupo);
            exit();
        }

        // Instanciar modelo y guardar
        $foroModel = new MensajeForo();
        $resultado = $foroModel->enviarMensaje($id_grupo, $id_usuario, $contenido);

        if ($resultado) {
            // Recargamos la página del grupo sin alerta intrusiva para fluidez del chat
            header("Location: ../views/app/grupo_detalle.php?id=" . $id_grupo);
            exit();
        } else {
            $_SESSION['error_grupo'] = "Hubo un error al enviar tu mensaje. Inténtalo de nuevo.";
            header("Location: ../views/app/grupo_detalle.php?id=" . $id_grupo);
            exit();
        }
    }
}
?>