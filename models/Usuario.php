<?php
require_once '../config/conexion.php';

class Usuario {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }


    // Registra un nuevo estudiante en la base de datos
    public function registrar($carne, $nombre_completo, $correo, $password) {
        try {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            // Asignamos por defecto el id_rol = 1 (Estudiante)
            $id_rol = 1; 

            $sql = "INSERT INTO usuarios (id_rol, carne, nombre_completo, correo, password) 
                    VALUES (:id_rol, :carne, :nombre_completo, :correo, :password)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
            $stmt->bindParam(':carne', $carne, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_completo', $nombre_completo, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':password', $passwordHash, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }


    // Valida las credenciales para el inicio de sesión
    public function autenticar($correo, $password_ingresada) {
        try {
            $sql = "SELECT id, id_rol, carne, nombre_completo, correo, password, estado 
                    FROM usuarios 
                    WHERE correo = :correo AND estado = 'activo' LIMIT 1";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password_ingresada, $usuario['password'])) {
                unset($usuario['password']); // Eliminamos el hash por seguridad antes de retornarlo
                return $usuario;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error en autenticación: " . $e->getMessage());
            return false;
        }
    }


    // Permite al usuario modificar su nombre en el perfil
    public function actualizarPerfil($id, $nombre_completo) {
        try {
            $sql = "UPDATE usuarios SET nombre_completo = :nombre_completo WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre_completo', $nombre_completo, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar perfil: " . $e->getMessage());
            return false;
        }
    }
}
?>