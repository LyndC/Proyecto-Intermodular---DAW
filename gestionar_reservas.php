<?php
session_start();
require_once "conectar_db.php";

// debug block
ini_set('display_errors', 1);
error_reporting(E_ALL);

//access control
$roles_internos = ['Administrador', 'Recepcionista', 'Gerencia'];
if (!isset($_SESSION['usuario_rol']) || !in_array($_SESSION['usuario_rol'], $roles_internos)) {
    header("Location: login.php");
    exit;
}

$pdo = conectar();
$reservas = [];

try {
    //sql query
    $sql = "SELECT r.id_reserva, r.fecha_entrada, r.fecha_salida, r.estado, r.huespedes,
                   c.nombre AS cliente_nombre, c.documento_identidad,
                   h.numero
            FROM reservas r
            LEFT JOIN clientes c ON r.id_cliente = c.id_cliente
            LEFT JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
            ORDER BY r.id_reserva DESC";
            
    $stmt = $pdo->query($sql);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error en la base de datos: " . $e->getMessage() . "</div>";
}

require_once 'layouts/header.php'; 
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="bi bi-calendar-check-fill me-2"></i>Control de Reservas</h2>
        <a href="admin.php" class="btn btn-outline-secondary bg-white text-dark rounded-pill px-4 shadow-sm">
    Volver
</a>
    </div>

    <div class="card shadow border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Cliente</th>
                        <th>Habitación</th>
                        <th>Estancia</th>
                        <th>Estado</th>
                        <th class="text-center pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservas)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                                No se encontraron reservas en la base de datos.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reservas as $res): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?php echo $res['id_reserva']; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($res['cliente_nombre'] ?? 'Sin Cliente'); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($res['documento_identidad'] ?? 'S/D'); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    Hab. <?php echo htmlspecialchars($res['numero'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td>
                                <div class="small"><strong>In:</strong> <?php echo date('d/m/Y', strtotime($res['fecha_entrada'])); ?></div>
                                <div class="small"><strong>Out:</strong> <?php echo date('d/m/Y', strtotime($res['fecha_salida'])); ?></div>
                            </td>
                            <td>
                                <?php 
                                $color = 'secondary';
                                if($res['estado'] == 'Confirmada') $color = 'success';
                                if($res['estado'] == 'Pendiente') $color = 'warning text-dark';
                                if($res['estado'] == 'Cancelada') $color = 'danger';
                                ?>
                                <span class="badge bg-<?php echo $color; ?>"><?php echo $res['estado']; ?></span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group">
                                    <a href="editar_reserva.php?id=<?php echo $res['id_reserva']; ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="ver_factura.php?id=<?php echo $res['id_reserva']; ?>" class="btn btn-sm btn-outline-info" title="Factura">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>