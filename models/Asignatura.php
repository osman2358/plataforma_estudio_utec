<?php
require_once '../config/conexion.php';

class Asignatura {
    private $pdo;

    public function __construct() {
        // Obtenemos la conexión global declarada en conexion.php
        global $pdo; 
        $this->pdo = $pdo;
    }


    // Obtiene todas las asignaturas activas para mostrarlas en un menú desplegable <select>
    public function obtenerActivas() {
        try {
            $sql = "SELECT id, codigo_materia, nombre 
                    FROM asignaturas 
                    WHERE estado = 'activa' 
                    ORDER BY nombre ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            // Retorna un arreglo asociativo con los registros
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al consultar asignaturas: " . $e->getMessage());
            return [];
        }
    }
}
?>