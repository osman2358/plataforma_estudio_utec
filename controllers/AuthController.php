<?php
// Iniciar la sesión para poder usar $_SESSION y guardar datos de usuario o mensajes de error
session_start();

// Incluir el modelo Usuario que contiene las consultas a la base de datos
require_once '../models/Usuario.php';

// Verificar si se están recibiendo datos por el método POST (envío de formularios)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Identificar qué formulario se envió a través del campo oculto 'accion'
    $accion = $_POST['accion'] ?? '';
    $usuarioModel = new Usuario();


    // PROCESO DE REGISTRO

    if ($accion === 'registro') {
        // Limpiar los datos recibidos
        $carne = trim($_POST['carne'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validar campos vacíos por seguridad adicional
        if (empty($carne) || empty($nombre) || empty($correo) || empty($password)) {
            $_SESSION['error_registro'] = "Todos los campos son obligatorios.";
            header('Location: ../views/auth/registro.php');
            exit();
        }

        // Ejecutar el método registrar del modelo
        $registroExitoso = $usuarioModel->registrar($carne, $nombre, $correo, $password);

        if ($registroExitoso) {
            // Si funciona, lo mandamos al login con un mensaje de éxito
            $_SESSION['exito_login'] = "Cuenta creada exitosamente. Ahora puedes iniciar sesión.";
            header('Location: ../views/auth/login.php');
            exit();
        } else {
            // Si falla (por ejemplo, carné o correo duplicado), lo regresamos al registro
            $_SESSION['error_registro'] = "Error al registrar. Es posible que el carné o correo ya estén en uso.";
            header('Location: ../views/auth/registro.php');
            exit();
        }
    }


    // PROCESO DE INICIO DE SESIÓN

    elseif ($accion === 'login') {
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($correo) || empty($password)) {
            $_SESSION['error_login'] = "Debes ingresar correo y contraseña.";
            header('Location: ../views/auth/login.php');
            exit();
        }

        // Validar credenciales
        $usuario = $usuarioModel->autenticar($correo, $password);

        if ($usuario) {
            // Login exitoso: Guardar datos en la sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
            $_SESSION['usuario_rol'] = $usuario['id_rol'];
            
            // Redirigir al panel principal del estudiante (dashboard)
            header('Location: ../views/app/dashboard.php');
            exit();
        } else {
            // Login fallido: regresar a la vista de login con error
            $_SESSION['error_login'] = "Credenciales incorrectas o cuenta inactiva.";
            header('Location: ../views/auth/login.php');
            exit();
        }
    }
}

// PROCESO DE CERRAR SESIÓN 

if (isset($_GET['accion']) && $_GET['accion'] === 'logout') {
    // Destruir todas las variables de sesión
    session_destroy();
    
    // Redirigir a la página principal (index)
    header('Location: ../index.php');
    exit();
}
?>