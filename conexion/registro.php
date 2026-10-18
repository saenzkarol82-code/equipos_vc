<?php
/* 
  CONTROLADOR DE REGISTRO
  Procesa los datos, guarda en la base de datos y maneja los errores amigablemente.
*/

// Subimos un nivel de carpeta para encontrar la conexión
require '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $ip = $_POST['ip'];
    $tipo = $_POST['tipo'];
    $mac = !empty($_POST['mac']) ? $_POST['mac'] : 'PENDIENTE';

    try {
        $sql = "INSERT INTO equipos (nombre, ip, mac_address, tipo) VALUES (:nombre, :ip, :mac, :tipo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':ip' => $ip,
            ':mac' => $mac,
            ':tipo' => $tipo
        ]);

        /* 
          Si la inserción fue un éxito, enviamos al usuario al Dashboard 
          de forma invisible y automática.
        */
        header("Location: ../dashboard.php?mensaje=registrado");
        exit();

    } catch (PDOException $e) {
        
        // Incluimos Bootstrap para que el mensaje de error se vea bien
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '<div class="container mt-5"><div class="row justify-content-center"><div class="col-md-6">';
        
        /* 
          EXPLICACIÓN DE LA CAPTURA DEL ERROR:
          $e->getCode() obtiene el código del error de MySQL.
          El código 23000 significa "Violación de integridad" (dato duplicado).
        */
        if ($e->getCode() == 23000) {
            // Este es tu mensaje personalizado
            echo '<div class="alert alert-warning shadow-sm">';
            echo '<h4 class="alert-heading">⚠️ Dispositivo Duplicado</h4>';
            echo "<p>La dirección IP <strong>$ip</strong> ya se encuentra registrada y en uso por otro dispositivo.</p>";
            echo '<hr>';
            echo '<p class="mb-0">Verifica la información e intenta con una IP diferente.</p>';
            echo '</div>';
        } else {
            // Si el error es otro (ej. se cayó el servidor de base de datos)
            echo '<div class="alert alert-danger">';
            echo '❌ Error crítico en la base de datos: ' . $e->getMessage();
            echo '</div>';
        }
        
        // El botón para regresar al formulario
        echo '<a href="../vista/registro_equipo.php" class="btn btn-secondary w-100">⬅ Volver al formulario</a>';
        echo '</div></div></div>';
    }
} else {
    echo "Acceso no autorizado.";
}
?>