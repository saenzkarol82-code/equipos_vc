<?php
// Incluimos la conexión para extraer los datos
require 'conexion.php';

try {
    // Consulta SQL avanzada: Obtenemos cada equipo con su ÚLTIMO reporte de estado
    $sql = "SELECT e.nombre, e.ip, e.mac_address, e.tipo, 
                   h.estado, h.version_os, h.aplicaciones, h.fecha_escaneo
            FROM equipos e
            LEFT JOIN historial_escaneos h ON e.id = h.equipo_id
            WHERE h.id = (
                SELECT MAX(id) FROM historial_escaneos WHERE equipo_id = e.id
            ) OR h.id IS NULL
            ORDER BY e.id ASC";
            
    $stmt = $pdo->query($sql);
    $equipos_estado = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error al consultar la base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Monitoreo AV</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    
    <!-- Menú de navegación superior rápido -->
    <div class="mb-3">
        <a href="index.html" class="btn btn-outline-secondary btn-sm">⬅ Volver al Menú Principal</a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Panel de Control - Dispositivos</h2>
        <!-- El botón que dispara el controlador general -->
        <a href="controlador_tv.php" class="btn btn-primary">
            Actualizar Todos los Estados
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <!-- Hacemos la tabla responsive para que no se desborde con las nuevas columnas -->
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Dispositivo</th>
                            <th>IP / MAC</th>
                            <th>Estado</th>
                            <th>Sistema / Apps</th>
                            <th>Última Lectura</th>
                            <!-- NUEVA COLUMNA PARA PRUEBAS FÍSICAS -->
                            <th class="text-center">Pruebas Hardware</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($equipos_estado) > 0): ?>
                            <?php foreach ($equipos_estado as $row): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($row['nombre']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($row['tipo']) ?></small>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['ip']) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($row['mac_address']) ?></small>
                                    </td>
                                    <td>
                                        <?php 
                                            // Lógica visual para el estado
                                            $estado = $row['estado'] ?? 'Sin datos';
                                            $badge_class = ($estado === 'Encendido') ? 'bg-success' : (($estado === 'Sin datos') ? 'bg-secondary' : 'bg-danger');
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($estado) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($row['version_os']): ?>
                                            <span class="badge bg-info text-dark mb-1"><?= htmlspecialchars($row['version_os']) ?></span><br>
                                            <small class="text-muted text-break" style="max-width: 200px; display: inline-block;">
                                                <?= htmlspecialchars(substr($row['aplicaciones'], 0, 80)) ?>...
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $row['fecha_escaneo'] ? date('d M Y, H:i', strtotime($row['fecha_escaneo'])) : 'Nunca' ?>
                                    </td>
                                    
                                    <!-- NUEVA SECCIÓN DE BOTONES DE ACCIÓN -->
                                    <td class="text-center">
                                        <!-- 
                                          Estos botones enviarán la IP y la acción específica por URL (Método GET) 
                                          a un nuevo controlador que crearemos para enviar comandos individuales a Python
                                        -->
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="controlador_equipos.php?ip=<?= urlencode($row['ip']) ?>&accion=camara" 
                                               class="btn btn-outline-dark" title="Probar Cámara">
                                                📷 Cámara
                                            </a>
                                            <a href="controlador_equipos.php?ip=<?= urlencode($row['ip']) ?>&accion=microfono" 
                                               class="btn btn-outline-dark" title="Probar Micrófono">
                                                🎤 Audio
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No hay equipos registrados en la base de datos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>