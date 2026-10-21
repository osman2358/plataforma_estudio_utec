<?php
require_once '../config/conexion.php';

class MaterialApoyo {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Insertar (Rúbrica: Insertar registros)
    // Permite adjuntar un recurso al grupo
    public function subirMaterial($id_grupo, $id_usuario, $titulo, $enlace_recurso) {
        try {
            $sql = "INSERT INTO materiales_apoyo (id_grupo, id_usuario, titulo, enlace_recurso) 
                    VALUES (:id_grupo, :id_usuario, :titulo, :enlace_recurso)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':enlace_recurso', $enlace_recurso, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al subir material: " . $e->getMessage());
            return false;
        }
    }

    // Consultar (Rúbrica: Consultar registros)
    // Lista todos los materiales aportados en el grupo
    public function obtenerPorGrupo($id_grupo) {
        try {
            $sql = "SELECT 
                        ma.id, 
                        ma.titulo, 
                        ma.enlace_recurso, 
                        ma.fecha_subida, 
                        ma.id_usuario,
                        COALESCE(u.nombre_completo, 'Usuario Eliminado') AS nombre_autor
                    FROM materiales_apoyo ma
                    LEFT JOIN usuarios u ON ma.id_usuario = u.id
                    WHERE ma.id_grupo = :id_grupo
                    ORDER BY ma.fecha_subida DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al consultar materiales: " . $e->getMessage());
            return [];
        }
    }

    // Eliminar (Rúbrica: Eliminar información)
    // Permite borrar un material si el usuario es el autor
    public function eliminarMaterial($id_material, $id_usuario) {
        try {
            $sql = "DELETE FROM materiales_apoyo WHERE id = :id_material AND id_usuario = :id_usuario";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_material', $id_material, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar material: " . $e->getMessage());
            return false;
        }
    }
}
?>