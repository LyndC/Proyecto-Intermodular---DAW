<?php
session_start();
require_once "conectar_db.php"; 

// definition role
$roles_permitidos = ['Recepcionista', 'Gerencia', 'Contabilidad', 'Mantenimiento'];

// acces control
if (!isset($_SESSION['usuario_rol']) || !in_array($_SESSION['usuario_rol'], $roles_permitidos)) {
    header("Location: login.php");
    exit;
}

$rol_actual = $_SESSION['usuario_rol'];
$nombre_usuario = $_SESSION['usuario_nombre'];
$datos_dinamicos = []; // Array for save datas of the BD


try {
    $pdo = conectar();
    
    //Recepcionista
    if ($rol_actual == 'Recepcionista') {
        $sql_reservas = "SELECT COUNT(*) FROM reservas WHERE estado = 'Pendiente'";
        $stmt = $pdo->query($sql_reservas);
        $datos_dinamicos['reservas_pendientes'] = $stmt->fetchColumn();
    }
    
    // Mantenimiento
    if ($rol_actual == 'Mantenimiento') {
        $sql_tareas = "SELECT COUNT(*) FROM tareas WHERE estado = 'Asignada'";
        $stmt = $pdo->query($sql_tareas);
        $datos_dinamicos['tareas_asignadas'] = $stmt->fetchColumn();
    }

} catch (PDOException $e) {
    // Manejar el error de BD de forma silenciosa para no detener la página
    // Solo se registra el error o se muestra un mensaje genérico.
    error_log("Error de BD en empleado.php: " . $e->getMessage());
    $datos_dinamicos['error'] = "No se pudieron cargar los datos dinámicos.";
}

$paneles = [];

// Reservations
if (in_array($rol_actual, ['Recepcionista', 'Gerencia', 'Contabilidad'])) {
    $conteo = $datos_dinamicos['reservas_pendientes'] ?? 0;
    $descripcion = "Consulta y administra las reservas. Tienes **$conteo** pendientes.";
    $paneles['Gestión de Reservas'] = [
        'descripcion' => $descripcion,
        'url' => 'gestionar_reservas.php',
        'color' => 'primary'
    ];
}

// rooms
if (in_array($rol_actual, ['Recepcionista', 'Mantenimiento'])) {
    $paneles['Habitaciones'] = [
        'descripcion' => 'Ver disponibilidad y actualizar estado de habitaciones.',
        'url' => 'habitaciones.php',
        'color' => 'info'
    ];
}

//  Mantenimiento
if ($rol_actual == 'Mantenimiento') {
    $conteo = $datos_dinamicos['tareas_asignadas'] ?? 0;
    $descripcion = "Revisa y marca como completadas las tareas. Hay **$conteo** asignadas.";
    $paneles['Tareas Pendientes'] = [
        'descripcion' => $descripcion,
        'url' => 'tareas_mantenimiento.php',
        'color' => 'warning'
    ];
}

// Gerencia/Contabilidad
if (in_array($rol_actual, ['Gerencia', 'Contabilidad'])) {
    $paneles['Informes y Finanzas'] = [
        'descripcion' => 'Acceso a balances, facturación e informes de rendimiento.',
        'url' => 'informes_finanzas.php',
        'color' => 'success'
    ];
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel <?php echo $rol_actual; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-center mb-4">Bienvenido al Panel de <?php echo $rol_actual; ?>, <?php echo $nombre_usuario; ?></h1>
    
    <?php if (isset($datos_dinamicos['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $datos_dinamicos['error']; ?>
        </div>
    <?php endif; ?>

    <div class="row mt-4">
        <?php foreach ($paneles as $titulo => $panel): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-<?php echo $panel['color']; ?>">
                    <div class="card-body">
                        <h5 class="card-title text-<?php echo $panel['color']; ?>"><?php echo $titulo; ?></h5>
                        <p class="card-text"><?php echo $panel['descripcion']; ?></p>
                        <a href="<?php echo $panel['url']; ?>" class="btn btn-<?php echo $panel['color']; ?>">Ir</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <a href="logout.php" class="btn btn-danger mt-5">Cerrar Sesión</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>