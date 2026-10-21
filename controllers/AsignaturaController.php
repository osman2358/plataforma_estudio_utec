<?php
session_start();
require_once '../models/Asignatura.php';

// Validar seguridad de sesión para todas las acciones de este controlador
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../views/auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $asignaturaModel = new Asignatura();


    // CREAR ASIGNATURA
  
    if ($accion === 'crear_asignatura') {
        $codigo_materia = trim($_POST['codigo_materia'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');

        if (empty($codigo_materia) || empty($nombre)) {
            $_SESSION['error_asignatura'] = "El código y el nombre de la materia son obligatorios.";
            header('Location: ../views/app/asignatura.php');
            exit();
        }

        $resultado = $asignaturaModel->crear($codigo_materia, $nombre);

        if ($resultado) {
            $_SESSION['exito_asignatura'] = "Asignatura registrada correctamente.";
        } else {
            $_SESSION['error_asignatura'] = "Error al guardar. Es posible que el código ya exista.";
        }
        header('Location: ../views/app/asignatura.php');
        exit();
    }


    // ACTUALIZAR ASIGNATURA

    elseif ($accion === 'actualizar_asignatura') {
        $id = $_POST['id'] ?? '';
        $codigo_materia = trim($_POST['codigo_materia'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');

        if (empty($id) || empty($codigo_materia) || empty($nombre)) {
            $_SESSION['error_asignatura'] = "Datos incompletos para actualizar.";
            header('Location: ../views/app/asignatura.php');
            exit();
        }

        $resultado = $asignaturaModel->actualizar($id, $codigo_materia, $nombre);

        if ($resultado) {
            $_SESSION['exito_asignatura'] = "Asignatura actualizada con éxito.";
        } else {
            $_SESSION['error_asignatura'] = "Error al intentar actualizar la asignatura.";
        }
        header('Location: ../views/app/asignatura.php'); // Redirige a la vista de gestión
        exit();
    }

  
    // ELIMINAR ASIGNATURA

    elseif ($accion === 'eliminar_asignatura') {
        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            $_SESSION['error_asignatura'] = "No se especificó la asignatura a eliminar.";
            header('Location: ../views/app/asignatura.php');
            exit();
        }

        $resultado = $asignaturaModel->eliminar($id);

        if ($resultado === true) {
            $_SESSION['exito_asignatura'] = "Asignatura eliminada del sistema.";
        } elseif ($resultado === "en_uso") {
            $_SESSION['error_asignatura'] = "No puedes eliminar esta asignatura porque ya existen grupos de estudio vinculados a ella.";
        } else {
            $_SESSION['error_asignatura'] = "Ocurrió un error interno al intentar eliminar.";
        }
        header('Location: ../views/app/asignatura.php');
        exit();
    }
}
?>