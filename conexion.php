<?php
// Configuracion de la base de datos
$host = '127.0.0.1'; // También puedes usar 'localhost'
$dbname = 'equipos_vc';
$username = 'root'; // Usuario por defecto en XAMPP/Laragon
$password = ''; // Contraseña por defecto (vacía en XAMPP/Laragon)

try {
    // Creación de la instancia PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar PDO para que reporte errores lanzando excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Descomenta la siguiente línea solo para probar que funciona, luego vuelve a comentarla
     //echo "Conexión exitosa a la base de datos.";

} catch (PDOException $e) {
    // Si la conexión falla, se captura el error y se detiene la ejecución
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>