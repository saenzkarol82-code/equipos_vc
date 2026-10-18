<?php
// 1. Incluir la conexión a la base de datos
require 'conexion.php';

echo "<h2>Iniciando diagnóstico de equipos...</h2>";

try {
    // 2. Obtener todos los equipos registrados en el inventario
    $stmt = $pdo->query("SELECT id, ip, nombre FROM equipos");
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($equipos as $equipo) {
        $id_equipo = $equipo['id'];
        $ip = $equipo['ip'];
        $nombre = $equipo['nombre'];

        echo "Escaneando: <strong>$nombre</strong> ($ip)...<br>";

        // 3. Ejecutar el script de Python
        // Nota: Si usaste 'py' en la consola, cambia 'python' por 'py' en la siguiente línea
        $comando = escapeshellcmd("py agente.py " . escapeshellarg($ip));
        $resultado_json = shell_exec($comando);

        // 4. Decodificar el JSON devuelto por Python a un Array asociativo de PHP
        $datos = json_decode($resultado_json, true);

        // Validar que Python haya devuelto información válida
        if ($datos && isset($datos['estado'])) {
            $estado = $datos['estado'];
            $version_os = $datos['version_os'];
            
            // Convertir el arreglo de aplicaciones en un texto separado por comas para la base de datos
            $aplicaciones = implode(", ", $datos['aplicaciones']); 

            // 5. Insertar el reporte en la tabla de historial
            $sql_insert = "INSERT INTO historial_escaneos (equipo_id, estado, aplicaciones, version_os) 
                           VALUES (:equipo_id, :estado, :aplicaciones, :version_os)";
            
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([
                ':equipo_id' => $id_equipo,
                ':estado' => $estado,
                ':aplicaciones' => $aplicaciones,
                ':version_os' => $version_os
            ]);

            echo "✅ Diagnóstico guardado. Estado: <strong>$estado</strong>.<br><hr>";
        } else {
            echo "❌ Error al comunicarse con el agente de Python para este equipo.<br><hr>";
        }
    }
    
    echo "<h3>Escaneo finalizado con éxito.</h3>";

} catch (Exception $e) {
    echo "Error en el controlador: " . $e->getMessage();
}
?>