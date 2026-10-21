<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y limpiar los datos usando los IDs/names únicos que configuramos
    $nombre = trim($_POST['nombre_contacto'] ?? '');
    $correo = trim($_POST['correo_contacto'] ?? '');
    $asunto = trim($_POST['asunto_contacto'] ?? '');
    $mensaje = trim($_POST['mensaje_contacto'] ?? '');

    // Validación básica
    if (empty($nombre) || empty($correo) || empty($mensaje)) {
        $_SESSION['error_contacto'] = "Por favor, completa todos los campos obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_contacto'] = "El formato del correo electrónico no es válido.";
    } else {
        // Simulación de envío exitoso
        $_SESSION['exito_contacto'] = "¡Gracias, $nombre! Tu mensaje sobre '$asunto' ha sido recibido. El equipo de soporte te contactará pronto a tu correo.";
    }

    // Redirigir de regreso a la vista visual
    header('Location: ../views/public/contacto.php');
    exit();
} else {
    // Si alguien intenta entrar directamente escribiendo la URL, lo devolvemos
    header('Location: ../views/public/contacto.php');
    exit();
}
?>