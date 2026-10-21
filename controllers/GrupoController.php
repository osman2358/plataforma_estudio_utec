<?php
session_start();

require_once '../models/GrupoEstudio.php';
require_once '../models/MiembroGrupo.php';

// Validar que se reciba una petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    $grupoModel = new GrupoEstudio();
    $miembroModel = new MiembroGrupo();


    // PROCESO DE CREACIÓN DE GRUPO

    if ($accion === 'crear_grupo') {
        // Validar seguridad de sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ../views/auth/login.php');
            exit();
        }

        $id_creador = $_SESSION['usuario_id'];
        $nombre_grupo = trim($_POST['nombre_grupo'] ?? '');
        $id_asignatura = $_POST['id_asignatura'] ?? '';
        $max_integrantes = (int)($_POST['max_integrantes'] ?? 0);
        $tema_especifico = trim($_POST['tema_especifico'] ?? '');

        // Validación básica de campos
        if (empty($nombre_grupo) || empty($id_asignatura) || $max_integrantes < 2) {
            $_SESSION['error_grupo'] = "Datos inválidos. Verifica el nombre, la asignatura y que el límite sea de al menos 2 integrantes.";
            header('Location: ../views/app/grupo_crear.php');
            exit();
        }

        // Crear el grupo
        $id_nuevo_grupo = $grupoModel->crear($id_asignatura, $id_creador, $nombre_grupo, $tema_especifico, $max_integrantes);

        if ($id_nuevo_grupo) {
            // Si el grupo se creó con éxito, unir al creador automáticamente
            $miembroModel->unirse($id_creador, $id_nuevo_grupo);
            
            $_SESSION['exito_grupo'] = "Grupo creado exitosamente. Ya eres miembro.";
            header('Location: ../views/app/grupos_lista.php');
            exit();
        } else {
            $_SESSION['error_grupo'] = "Ocurrió un error al registrar el grupo en la base de datos.";
            header('Location: ../views/app/grupo_crear.php');
            exit();
        }
    }


    // PROCESO DE UNIRSE A UN GRUPO EXISTENTE

    elseif ($accion === 'unirse_grupo') {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ../views/auth/login.php');
            exit();
        }

        $id_usuario = $_SESSION['usuario_id'];
        $id_grupo = $_POST['id_grupo'] ?? '';

        if (empty($id_grupo)) {
            $_SESSION['error_grupo'] = "No se especificó un grupo válido.";
            header('Location: ../views/app/grupos_lista.php');
            exit();
        }

        // Ejecutar la acción de unirse
        $resultado = $miembroModel->unirse($id_usuario, $id_grupo);

        // Procesar las posibles respuestas del Modelo / Triggers MySQL
        if ($resultado === true) {
            $_SESSION['exito_grupo'] = "Te has unido al grupo con éxito.";
            // Si funciona, redirigimos directamente al foro del grupo
            header("Location: ../views/app/grupo_detalle.php?id=" . $id_grupo);
            exit();
        } elseif ($resultado === "Lleno") {
            $_SESSION['error_grupo'] = "Operación denegada: El grupo ya alcanzó su límite máximo de integrantes.";
            header('Location: ../views/app/grupos_lista.php');
            exit();
        } elseif ($resultado === "Duplicado") {
            $_SESSION['error_grupo'] = "Ya perteneces a este grupo.";
            header('Location: ../views/app/grupos_lista.php');
            exit();
        } else {
            $_SESSION['error_grupo'] = "Ocurrió un error inesperado al intentar unirte.";
            header('Location: ../views/app/grupos_lista.php');
            exit();
        }
    }
}
?>