<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Equipo</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Registrar Nuevo Dispositivo</h4>
                </div>
                <div class="card-body">
                    <!-- El formulario envía los datos a procesar_equipo.php -->
                    <form action="registro.php" method="POST">
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Equipo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   placeholder="Ej: Sala Land Rover" required>
                        </div>

                        <div class="mb-3">
                            <label for="mac" class="form-label">Dirección MAC </label>
                            <input type="text" class="form-control" id="mac" name="mac" 
                                   placeholder="Ej: AA:BB:CC:DD:EE:FF">
                            <small class="text-muted">Si no la conoces, Python intentará extraerla después.</small>
                        </div>

                        <div class="mb-3">
                            <label for="ip" class="form-label">Dirección IP </label>
                            <input type="text" class="form-control" id="ip" name="ip" 
                                   placeholder="Ej: 192.168.1.15" required>
                        </div>                        

                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Dispositivo</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccione una opción...</option>
                                <option value="Celular Android">Tablet Inteligente</option>
                                <option value="Tablet Kiosco">Tablet portatil</option>
                                <option value="Smart TV">Celular</option>
                                <option value="Smart TV">Smart TV</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Guardar Equipo en Inventario</button>
                            <a href="dashboard.php" class="btn btn-secondary">Cancelar y Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
