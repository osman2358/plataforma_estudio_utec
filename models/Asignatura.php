<?php
require_once __DIR__ . '/../config/conexion.php';

class Asignatura {
    private $pdo;

    public function __construct() {
        global $pdo; 
        $this->pdo = $pdo;
    }

    // ================== CREAR ==================
    public function crear($codigo_materia, $nombre) {
        try {
            $sql = "INSERT INTO asignaturas (codigo_materia, nombre, estado) 
                    VALUES (:codigo_materia, :nombre, 'activa')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':codigo_materia', $codigo_materia, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al crear asignatura: " . $e->getMessage());
            return false;
        }
    }

    // ================== CONSULTAR ==================
    public function obtenerActivas() {
        try {
            $sql = "SELECT id, codigo_materia, nombre 
                    FROM asignaturas 
                    WHERE estado = 'activa' 
                    ORDER BY nombre ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al consultar asignaturas: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT id, codigo_materia, nombre FROM asignaturas WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================== ACTUALIZAR ==================
    public function actualizar($id, $codigo_materia, $nombre) {
        try {
            $sql = "UPDATE asignaturas 
                    SET codigo_materia = :codigo_materia, nombre = :nombre 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':codigo_materia', $codigo_materia, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar asignatura: " . $e->getMessage());
            return false;
        }
    }

    // ================== ELIMINAR ==================
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM asignaturas WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // El código 23000 corresponde a una violación de restricción de llave foránea (RESTRICT)
            if ($e->getCode() == '23000') {
                return "en_uso"; 
            }
            error_log("Error al eliminar asignatura: " . $e->getMessage());
            return false;
        }
    }
}
?>