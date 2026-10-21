<?php
require_once '../config/conexion.php';

class Rol {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }


    // Obtiene la lista de roles disponibles en el sistema
    public function obtenerTodos() {
        try {
            $sql = "SELECT id, nombre_rol, descripcion FROM roles ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al consultar roles: " . $e->getMessage());
            return [];
        }
    }
}
?>