<?php
session_start();
require_once '../models/MaterialApoyo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // ==========================================
    // PROCESO DE SUBIR MATERIAL
    // ==========================================
    if ($accion === 'subir_material') {
        // Validar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ../views/auth/login.php');
            exit();
        }

        $id_usuario = $_SESSION['usuario_id'];
        $id_grupo = $_POST['id_grupo'] ?? '';
        $titulo = trim($_POST['titulo'] ?? '');
        $enlace_recurso = trim($_POST['enlace_recurso'] ?? '');

        // Validaciones básicas de campos vacíos
        if (empty($id_grupo) || empty($titulo) || empty($enlace_recurso)) {
            $_SESSION['error_grupo'] = "Debes proporcionar un título y un enlace válido.";
            header("Location: ../views/app/grupo_detalle.php?id=" . $id_grupo);
            exit();
        }

        // Validación de seguridad para asegurar que el enlace tenga formato de URL real
        if (!filter_var($enlace_recurso, FILTER_VALIDATE_URL)) {
            $_SESSION['error_grupo'] = "El formato del enlace no es válido. Asegúrate de incluir http:// o https://";
            header("Location: ../views/app/grupo_detalle.php?id=" . $id_grupo);
            exit();
        }

        // Instanciar modelo y guardar
        $materialModel = new MaterialApoyo();
        $resultado = $materialModel->subirMaterial($id_grupo, $id_usuario, $titulo, $enlace_recurso);

        if ($resultado) {
            $_SESSION['exito_grupo'] = "Recurso compartido correctamente con el grupo.";
            header("Location: ../views/app/grupo_detalle.php?id=" . $id_grupo);
            exit();
        } else {
            $_SESSION['error_grupo'] = "Ocurrió un error al guardar el material en la base de datos.";
            header("Location: ../views/app/grupo_detalle.php?id=" . $id_grupo);
            exit();
        }
    }
}
?>