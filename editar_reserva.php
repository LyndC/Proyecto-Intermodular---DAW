<?php
session_start();
require_once 'conectar_db.php';

// access control and obtain ID
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente' || !isset($_GET['id'])) {
    $_SESSION['error'] = "ERROR: Acceso denegado o ID de reserva faltante. Revise la URL.";
    header("Location: login.html");
    exit;
}

$pdo = conectar();
$id_reserva = $_GET['id'];
$id_cliente = $_SESSION['usuario_id'];
$error_msg = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
$reserva = null;
$categorias = [];

try {
    // load reservation details
    $sqlReserva = "SELECT id_reserva, id_habitacion, fecha_entrada, fecha_salida, huespedes, comentarios, id_categoria 
                   FROM reservas 
                   JOIN habitaciones ON reservas.id_habitacion = habitaciones.id_habitacion 
                   WHERE id_reserva = :id_reserva AND id_cliente = :id_cliente AND estado IN ('Pendiente', 'Confirmada')";
    
    $stmtReserva = $pdo->prepare($sqlReserva);
    $stmtReserva->execute([':id_reserva' => $id_reserva, ':id_cliente' => $id_cliente]);
    $reserva = $stmtReserva->fetch(PDO::FETCH_ASSOC);

    if (!$reserva) {
        throw new Exception("Reserva no válida para edición o ya ha comenzado.");
    }

    // load all categories for the dropdown
    $stmtCat = $pdo->query("SELECT id_categoria, nombre, capacidad_maxima, precio_base FROM categorias_habitacion ORDER BY id_categoria");
    $categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $_SESSION['error'] = "Error al cargar la reserva: " . $e->getMessage();
    header("Location: misreservas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Reserva #<?php echo $id_reserva; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Reserva #<?php echo htmlspecialchars($reserva['id_reserva']); ?></h2>
    
    <?php if ($error_msg): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <form action="actualizar_reserva.php" method="POST" class="bg-white p-5 rounded-3 shadow-lg mx-auto" style="max-width: 500px;">
        <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($reserva['id_reserva']); ?>">
        
        <div class="mb-4">
            <label for="fecha_entrada" class="form-label">Fecha de Check-in</label>
            <input type="date" class="form-control" name="fecha_entrada" required 
                   value="<?php echo htmlspecialchars($reserva['fecha_entrada']); ?>" 
                   min="<?php echo date('Y-m-d'); ?>">
        </div>
        
        <div class="mb-4">
            <label for="fecha_salida" class="form-label">Fecha de Check-out</label>
            <input type="date" class="form-control" name="fecha_salida" required 
                   value="<?php echo htmlspecialchars($reserva['fecha_salida']); ?>" 
                   min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
        </div>
        
        <div class="mb-4">
            <label for="id_categoria" class="form-label">Tipo de Habitación</label>
            <select class="form-select" name="id_categoria" required>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo $cat['id_categoria']; ?>"
                            <?php if ($cat['id_categoria'] == $reserva['id_categoria']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($cat['nombre']); ?> 
                        (Máx: <?php echo $cat['capacidad_maxima']; ?> pers. | Precio base: €<?php echo number_format($cat['precio_base'], 2); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-4">
            <label for="huespedes" class="form-label">Número de Huéspedes</label>
            <input type="number" class="form-control" name="huespedes" id="huespedes" 
                   value="<?php echo htmlspecialchars($reserva['huespedes']); ?>"
                   min="1" max="10" required>
        </div>

        <div class="mb-4">
            <label for="comentarios" class="form-label">Comentarios (Opcional)</label>
            <textarea class="form-control" name="comentarios" rows="3"><?php echo htmlspecialchars($reserva['comentarios'] ?? ''); ?></textarea>
        </div>
        
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-warning btn-lg">
                <i class="fas fa-save me-2"></i> Guardar Cambios
            </button>
            <a href="reservas.php" class="btn btn-link">Cancelar Edición</a>
        </div>
    </form>
</div>
</body>
</html>