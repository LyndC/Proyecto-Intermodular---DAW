<?php
session_start();
require_once 'conectar_db.php';

// 1. Control de Acceso y Obtener ID
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente' || !isset($_GET['id'])) {
    $_SESSION['error'] = "ERROR: Acceso denegado o ID de reserva faltante. Revise la URL.";
    header("Location: misreservas.php");
    exit;
}

$pdo = conectar();
$id_reserva = $_GET['id'];
$id_cliente = $_SESSION['usuario_id'];
$detalle = null;

try {
    // 2. Consulta principal para obtener TODOS los detalles de la reserva.
    // Usamos JOIN para traer datos de Habitaciones y Categorías.
    $sqlDetalle = "
        SELECT 
            r.id_reserva, r.fecha_reserva, r.fecha_entrada, r.fecha_salida, r.huespedes, r.estado, r.comentarios,
            h.numero AS num_habitacion, 
            c.nombre AS nombre_categoria, c.precio_base, c.capacidad_maxima
        FROM reservas r
        JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
        JOIN categorias_habitacion c ON h.id_categoria = c.id_categoria
        WHERE r.id_reserva = :id_reserva AND r.id_cliente = :id_cliente"; // Seguridad: solo muestra la reserva del cliente logueado

    $stmtDetalle = $pdo->prepare($sqlDetalle);
    $stmtDetalle->execute([':id_reserva' => $id_reserva, ':id_cliente' => $id_cliente]);
    $detalle = $stmtDetalle->fetch(PDO::FETCH_ASSOC);

    if (!$detalle) {
        throw new Exception("Reserva no encontrada o no le pertenece.");
    }

} catch (Exception $e) {
    $_SESSION['error'] = "Error al cargar los detalles: " . $e->getMessage();
    header("Location: misreservas.php");
    exit;
}

// Cálculo simple de noches y precio
$dt_entrada = new DateTime($detalle['fecha_entrada']);
$dt_salida = new DateTime($detalle['fecha_salida']);
$noches = $dt_entrada->diff($dt_salida)->days;
$precio_total = $noches * $detalle['precio_base'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Reserva #<?php echo $id_reserva; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Detalles de Reserva #<?php echo htmlspecialchars($id_reserva); ?></h2>
    
    <div class="bg-white p-5 rounded-3 shadow-lg mx-auto" style="max-width: 600px;">
        
        <h4 class="border-bottom pb-2 mb-3"><i class="fas fa-calendar-alt me-2"></i> Información de Fechas y Estado</h4>
        <table class="table table-bordered">
            <tr><th>Estado Actual</th><td class="fw-bold text-success"><?php echo htmlspecialchars($detalle['estado']); ?></td></tr>
            <tr><th>Fecha de Reserva</th><td><?php echo date('d/m/Y H:i', strtotime($detalle['fecha_reserva'])); ?></td></tr>
            <tr><th>Check-in</th><td><?php echo date('d/m/Y', strtotime($detalle['fecha_entrada'])); ?></td></tr>
            <tr><th>Check-out</th><td><?php echo date('d/m/Y', strtotime($detalle['fecha_salida'])); ?></td></tr>
            <tr><th>Noches</th><td><?php echo $noches; ?></td></tr>
        </table>

        <h4 class="border-bottom pb-2 mb-3 mt-4"><i class="fas fa-bed me-2"></i> Detalles de Habitación</h4>
        <table class="table table-bordered">
            <tr><th>Habitación Asignada</th><td><?php echo htmlspecialchars($detalle['num_habitacion']); ?></td></tr>
            <tr><th>Categoría</th><td><?php echo htmlspecialchars($detalle['nombre_categoria']); ?></td></tr>
            <tr><th>Capacidad Máxima</th><td><?php echo htmlspecialchars($detalle['capacidad_maxima']); ?> Huéspedes</td></tr>
            <tr><th>Huéspedes Reservados</th><td><?php echo htmlspecialchars($detalle['huespedes']); ?></td></tr>
        </table>
        
        <h4 class="border-bottom pb-2 mb-3 mt-4"><i class="fas fa-coins me-2"></i> Resumen de Costos</h4>
        <table class="table table-bordered">
            <tr><th>Precio Base / Noche</th><td>€<?php echo number_format($detalle['precio_base'], 2); ?></td></tr>
            <tr><th>Precio Total Estimado</th><td class="fw-bold">€<?php echo number_format($precio_total, 2); ?></td></tr>
        </table>

        <?php if (!empty($detalle['comentarios'])): ?>
            <h4 class="border-bottom pb-2 mb-3 mt-4"><i class="fas fa-comment-dots me-2"></i> Comentarios</h4>
            <div class="alert alert-info"><?php echo nl2br(htmlspecialchars($detalle['comentarios'])); ?></div>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="misreservas.php" class="btn btn-secondary">Volver al Listado</a>
            <?php if ($detalle['estado'] == 'Pendiente' || $detalle['estado'] == 'Confirmada'): ?>
                <a href="editar_reserva.php?id=<?php echo $id_reserva; ?>" class="btn btn-warning">Editar Reserva</a>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>