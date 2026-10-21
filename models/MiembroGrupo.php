<?php
require_once '../config/conexion.php';

class MiembroGrupo {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }


    // Intenta unir a un usuario al grupo. Aquí entra en acción el Trigger de MySQL.
    public function unirse($id_usuario, $id_grupo) {
        try {
            $sql = "INSERT INTO miembros_grupo (id_usuario, id_grupo) VALUES (:id_usuario, :id_grupo)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // Capturamos específicamente el error 45000 lanzado por nuestro Trigger
            if ($e->getCode() == '45000') {
                return "Lleno";
            }
            // Capturamos el error 23000 (Violación de UNIQUE) si ya está unido
            if ($e->getCode() == '23000') {
                return "Duplicado";
            }
            error_log("Error al unirse al grupo: " . $e->getMessage());
            return false;
        }
    }

  
    // Permite a un estudiante abandonar un grupo de estudio
    public function abandonar($id_usuario, $id_grupo) {
        try {
            $sql = "DELETE FROM miembros_grupo WHERE id_usuario = :id_usuario AND id_grupo = :id_grupo";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al abandonar grupo: " . $e->getMessage());
            return false;
        }
    }

    // Consultar
    // Verifica rápidamente si un usuario ya pertenece a un grupo específico
    public function esMiembro($id_usuario, $id_grupo) {
        try {
            $sql = "SELECT id FROM miembros_grupo WHERE id_usuario = :id_usuario AND id_grupo = :id_grupo LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn() !== false;
        } catch (PDOException $e) {
            error_log("Error al verificar membresía: " . $e->getMessage());
            return false;
        }
    }
}
?>