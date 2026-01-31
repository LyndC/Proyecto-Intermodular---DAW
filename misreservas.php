<?php
session_start();
require_once 'conectar_db.php';

// Access control
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.html");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$pdo = conectar();
$reservas = [];
$error_msg = '';

// Read RESERVAS
try {
    // JOIN: get reservations of client, including room number and category
    $sql = "SELECT 
                r.id_reserva, r.fecha_entrada, r.fecha_salida, r.estado, 
                h.numero AS numero_habitacion, 
                c.nombre AS nombre_categoria, c.precio_base
            FROM reservas r
            JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
            JOIN categorias_habitacion c ON h.id_categoria = c.id_categoria
            WHERE r.id_cliente = :id_cliente
<<<<<<< HEAD
            ORDER BY r.fecha_entrada DESC";
=======
            ORDER BY r.id_reserva DESC";
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_cliente' => $usuario_id]);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {//error capture
    $error_msg = "Error al cargar reservas: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<<<<<<< HEAD
=======
    <link href="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Mis Reservas Realizadas</h2>
    
    <?php if ($error_msg): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="cliente.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel
        </a>
        <a href="nueva_reserva.php" class="btn btn-success btn-lg">
            <i class="fas fa-calendar-plus me-2"></i> Reservar Ahora
        </a>
    </div>

    <?php if (empty($reservas)): ?>
        <div class="alert alert-info text-center py-4">
            <h4>No tienes reservas registradas.</h4>
            <p>Utiliza el botón **Reservar Ahora** para empezar.</p>
            <a href="nueva_reserva.php" class="btn btn-info btn-lg mt-2">
                ¡Hacer mi primera reserva!
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID Reserva</th>
                        <th>Habitación</th>
                        <th>Categoría</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reserva['id_reserva']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['numero_habitacion']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['nombre_categoria']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['fecha_entrada']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['fecha_salida']); ?></td>
                        <td><span class="badge bg-<?php 
                            // assign colors according to their status
                            if ($reserva['estado'] == 'Confirmada') echo 'success';
                            else if ($reserva['estado'] == 'Pendiente') echo 'warning';
                            else if ($reserva['estado'] == 'Check-in') echo 'primary';
                            else if ($reserva['estado'] == 'Check-out') echo 'info';
                            else echo 'danger'; // Cancel
                        ?>"><?php echo htmlspecialchars($reserva['estado']); ?></span></td>
                     <td>
    <a href="ver_detalles.php?id=<?php echo $reserva['id_reserva']; ?>" class="btn btn-sm btn-info" title="Ver Detalles">
        <i class="fas fa-search"></i> Ver Detalles
    </a>
    
    <?php 
    // Logic for EDIT and CANCEL: Only if the reservation is active
    if ($reserva['estado'] == 'Pendiente' || $reserva['estado'] == 'Confirmada'): 
    ?>
<<<<<<< HEAD
        <a href="editar_reserva.php?id=<?php echo $reserva['id_reserva']; ?>" class="btn btn-sm btn-warning" title="Editar">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="cancelar_reserva.php?id=<?php echo $reserva['id_reserva']; ?>" 
           class="btn btn-sm btn-danger" 
           title="Cancelar" 
           onclick="return confirm('¿Seguro que desea cancelar esta reserva?');">
=======
    <a href="editar_reserva.php?id=<?php echo $reserva['id_reserva']; ?>" class="btn btn-sm btn-warning" title="Editar">
        <i class="fas fa-edit"></i> Editar
    </a>
    <a href="cancelar_reserva.php?id=<?php echo $reserva['id_reserva']; ?>" 
        class="btn btn-sm btn-danger" title="Cancelar" onclick="return confirm('¿Seguro que desea cancelar esta reserva?');">
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
            <i class="fas fa-times"></i> Cancelar
        </a>
    <?php endif; ?>
</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>