<?php
require_once '../config/conexion.php';

class MensajeForo {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

  
    // Guarda un nuevo mensaje en el foro del grupo
    public function enviarMensaje($id_grupo, $id_usuario, $contenido) {
        try {
            $sql = "INSERT INTO mensajes_foro (id_grupo, id_usuario, contenido) 
                    VALUES (:id_grupo, :id_usuario, :contenido)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al enviar mensaje: " . $e->getMessage());
            return false;
        }
    }


    // Obtiene todo el historial de mensajes de un grupo específico
    public function obtenerPorGrupo($id_grupo) {
        try {
            $sql = "SELECT 
                        m.id, 
                        m.contenido, 
                        m.fecha_envio, 
                        m.id_usuario,
                        COALESCE(u.nombre_completo, 'Usuario Eliminado') AS nombre_autor
                    FROM mensajes_foro m
                    LEFT JOIN usuarios u ON m.id_usuario = u.id
                    WHERE m.id_grupo = :id_grupo
                    ORDER BY m.fecha_envio ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al consultar mensajes: " . $e->getMessage());
            return [];
        }
    }


    // Permite a un estudiante borrar su propio mensaje
    public function eliminarMensaje($id_mensaje, $id_usuario) {
        try {
            // Validamos id_usuario en el WHERE para asegurar que solo el autor pueda borrarlo
            $sql = "DELETE FROM mensajes_foro WHERE id = :id_mensaje AND id_usuario = :id_usuario";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_mensaje', $id_mensaje, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            
            $stmt->execute();
            // Retorna true si realmente se eliminó una fila
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar mensaje: " . $e->getMessage());
            return false;
        }
    }
}
?>