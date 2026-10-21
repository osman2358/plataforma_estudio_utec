<?php
require_once '../config/conexion.php';

class GrupoEstudio {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

   

    // Crea un nuevo grupo de estudio y retorna su ID
    public function crear($id_asignatura, $id_creador, $nombre_grupo, $tema_especifico, $max_integrantes) {
        try {
            $sql = "INSERT INTO grupos_estudio (id_asignatura, id_creador, nombre_grupo, tema_especifico, max_integrantes) 
                    VALUES (:id_asignatura, :id_creador, :nombre_grupo, :tema_especifico, :max_integrantes)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_asignatura', $id_asignatura, PDO::PARAM_INT);
            $stmt->bindParam(':id_creador', $id_creador, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_grupo', $nombre_grupo, PDO::PARAM_STR);
            $stmt->bindParam(':tema_especifico', $tema_especifico, PDO::PARAM_STR);
            $stmt->bindParam(':max_integrantes', $max_integrantes, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Retorna el ID del registro recién insertado
                return $this->pdo->lastInsertId(); 
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear grupo de estudio: " . $e->getMessage());
            return false;
        }
    }

    // Obtener los detalles de un grupo por su ID
    public function obtenerPorId($id_grupo) {
        try {
            $sql = "SELECT 
                        g.*, 
                        a.nombre AS nombre_asignatura, 
                        u.nombre_completo AS nombre_creador 
                    FROM grupos_estudio g 
                    INNER JOIN asignaturas a ON g.id_asignatura = a.id 
                    LEFT JOIN usuarios u ON g.id_creador = u.id 
                    WHERE g.id = :id_grupo";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener grupo: " . $e->getMessage());
            return false;
        }
    }

  
    // Obtiene todos los grupos disponibles, uniendo con asignaturas y usuarios
    public function obtenerTodosDisponibles() {
        try {
            $sql = "SELECT 
                        g.id, 
                        g.nombre_grupo, 
                        g.tema_especifico, 
                        g.max_integrantes, 
                        g.estado,
                        g.fecha_creacion,
                        a.nombre AS nombre_asignatura,
                        u.nombre_completo AS nombre_creador,
                        (SELECT COUNT(*) FROM miembros_grupo WHERE id_grupo = g.id) AS actuales
                    FROM grupos_estudio g
                    INNER JOIN asignaturas a ON g.id_asignatura = a.id
                    LEFT JOIN usuarios u ON g.id_creador = u.id
                    WHERE g.estado IN ('abierto', 'lleno')
                    ORDER BY g.fecha_creacion DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al consultar grupos: " . $e->getMessage());
            return [];
        }
    }

  
    // Cierra o reabre un grupo específico (si el creador así lo decide)
    public function actualizarEstado($id_grupo, $nuevo_estado) {
        try {
            $sql = "UPDATE grupos_estudio SET estado = :estado WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':estado', $nuevo_estado, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_grupo, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar estado del grupo: " . $e->getMessage());
            return false;
        }
    }
}
?>