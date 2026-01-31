<?php
session_start();
require_once 'conectar_db.php';

<<<<<<< HEAD
//Acces control
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente' || !isset($_GET['id'])) {
    $_SESSION['error'] = "ERROR: Acceso denegado o ID de reserva faltante. Revise la URL.";
=======
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente' || !isset($_GET['id'])) {
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    header("Location: misreservas.php");
    exit;
}

$pdo = conectar();
$id_reserva = $_GET['id'];
$id_cliente = $_SESSION['usuario_id'];
<<<<<<< HEAD
$detalle = null;

try {
    // Main query to obtain ALL reservation details.
    // We use JOIN to retrieve data for Rooms and Categories.
    $sqlDetalle = "
        SELECT 
            r.id_reserva, r.fecha_reserva, r.fecha_entrada, r.fecha_salida, r.huespedes, r.estado, r.comentarios,
            h.numero AS num_habitacion, 
            c.nombre AS nombre_categoria, c.precio_base, c.capacidad_maxima
        FROM reservas r
        JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
        JOIN categorias_habitacion c ON h.id_categoria = c.id_categoria
        WHERE r.id_reserva = :id_reserva AND r.id_cliente = :id_cliente"; // Seguridad: solo muestra la reserva del cliente logueado
=======

try {
    //Bring the reservation details and the actual total of the invoice.
    $sqlDetalle = "
        SELECT 
            r.*, h.numero AS num_habitacion, 
            c.nombre AS nombre_categoria, c.precio_base,
            f.total AS total_factura, f.subtotal, f.impuestos
        FROM reservas r
        JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
        JOIN categorias_habitacion c ON h.id_categoria = c.id_categoria
        LEFT JOIN facturas f ON r.id_reserva = f.id_reserva
        WHERE r.id_reserva = :id_reserva AND r.id_cliente = :id_cliente";
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)

    $stmtDetalle = $pdo->prepare($sqlDetalle);
    $stmtDetalle->execute([':id_reserva' => $id_reserva, ':id_cliente' => $id_cliente]);
    $detalle = $stmtDetalle->fetch(PDO::FETCH_ASSOC);

<<<<<<< HEAD
    if (!$detalle) {
        throw new Exception("Reserva no encontrada o no le pertenece.");
    }

} catch (Exception $e) {//error capture
    $_SESSION['error'] = "Error al cargar los detalles: " . $e->getMessage();
=======
    if (!$detalle) throw new Exception("Reserva no encontrada.");

    //Bring the additional services you have contracted.
    $sqlServicios = "
        SELECT s.nombre, sr.subtotal 
        FROM servicios_reserva sr
        JOIN servicios s ON sr.id_servicio = s.id_servicio
        WHERE sr.id_reserva = ?";
    $stmtS = $pdo->prepare($sqlServicios);
    $stmtS->execute([$id_reserva]);
    $servicios_contratados = $stmtS->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    header("Location: misreservas.php");
    exit;
}

<<<<<<< HEAD
// Simple calculation of nights and price
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
=======
$dt_entrada = new DateTime($detalle['fecha_entrada']);
$dt_salida = new DateTime($detalle['fecha_salida']);
$noches = $dt_entrada->diff($dt_salida)->days ?: 1;

require_once 'layouts/header.php'; // include layout
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Resumen de Reserva #<?php echo $id_reserva; ?></h2>
        <button onclick="window.print();" class="btn btn-outline-dark d-print-none">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">Detalles del Alojamiento</div>
                <div class="card-body">
                    <p><strong>Habitación:</strong> <?php echo $detalle['num_habitacion']; ?> (<?php echo $detalle['nombre_categoria']; ?>)</p>
                    <p><strong>Estancia:</strong> <?php echo date('d/m/Y', strtotime($detalle['fecha_entrada'])); ?> al <?php echo date('d/m/Y', strtotime($detalle['fecha_salida'])); ?> (<?php echo $noches; ?> noches)</p>
                    <p><strong>Huéspedes:</strong> <?php echo $detalle['huespedes']; ?></p>
                    <p><strong>Estado:</strong> <span class="badge bg-success"><?php echo $detalle['estado']; ?></span></p>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">Resumen de Costos</div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Alojamiento (<?php echo $noches; ?> noches)</td>
                            <td class="text-end">€<?php echo number_format($noches * $detalle['precio_base'], 2); ?></td>
                        </tr>
                        
                        <?php foreach ($servicios_contratados as $s): ?>
                        <tr>
                            <td><i class="fas fa-plus-circle text-success me-1"></i> <?php echo $s['nombre']; ?></td>
                            <td class="text-end">€<?php echo number_format($s['subtotal'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <tr class="border-top">
                            <td class="fw-bold">TOTAL FINAL</td>
                            <td class="text-end fw-bold text-primary" style="font-size: 1.4rem;">
                                €<?php echo number_format($detalle['total_factura'], 2); ?>
                            </td>
                        </tr>
                    </table>
                    <small class="text-muted">* IVA del 21% incluido (€<?php echo number_format($detalle['impuestos'], 2); ?>)</small>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-4">
                <a href="misreservas.php" class="btn btn-secondary">Volver a mis reservas</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; // include layout ?>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
