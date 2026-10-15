<?php
// 1. Llamamos a nuestra conexión PDO
require 'conexion.php';

// 2. Validamos que los datos vengan realmente del formulario por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Capturamos los datos enviados por los inputs (usando el atributo 'name')
    $nombre = $_POST['nombre'];
    $ip = $_POST['ip'];
    $tipo = $_POST['tipo'];
    
    // Si dejaron la MAC vacía, le asignamos un texto por defecto para evitar errores
    $mac = !empty($_POST['mac']) ? $_POST['mac'] : 'PENDIENTE';

    try {
        // 4. Preparamos la consulta SQL (Usamos :variables para evitar Inyección SQL)
        $sql = "INSERT INTO equipos (nombre, ip, mac_address, tipo) 
                VALUES (:nombre, :ip, :mac, :tipo)";
        
        $stmt = $pdo->prepare($sql);
        
        // 5. Ejecutamos la consulta inyectando las variables reales
        $stmt->execute([
            ':nombre' => $nombre,
            ':ip' => $ip,
            ':mac' => $mac,
            ':tipo' => $tipo
        ]);

        // 6. Si todo fue exitoso, redirigimos al usuario de vuelta al Dashboard
        header("Location: dashboard.php?mensaje=registrado");
        exit(); // Siempre usar exit después de un header de redirección

    } catch (PDOException $e) {
        // Si hay un error (ejemplo: metieron una IP o MAC que ya existe y es UNIQUE)
        echo "Error al registrar el equipo: " . $e->getMessage();
        echo "<br><a href='registro_equipo.php'>Volver al formulario</a>";
    }
} else {
    // Si alguien intenta entrar a este archivo escribiendo la URL directamente
    echo "Acceso no autorizado.";
}
?>