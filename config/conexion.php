<?php
// config/conexion.php

$host = "localhost";
$dbname = "plataforma_estudio"; 
$username = "webuser";             
$password = "proyecto_utec";                 
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Se crea la instancia global de conexión
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // Si MySQL está apagado o el nombre de la BD está mal, el script se detiene aquí y da un aviso
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>