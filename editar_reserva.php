<?php
session_start();
require_once 'conectar_db.php';

<<<<<<< HEAD
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
=======
// debug block
ini_set('display_errors', 1);
error_reporting(E_ALL);



$roles_staff = ['Administrador', 'Recepcionista', 'Gerencia', 'administrador', 'mantenimiento'];
$user_rol = $_SESSION['usuario_rol'] ?? '';

//acces control
if (!isset($_SESSION['usuario_rol'])) {
    die("Error: No hay sesión iniciada. <a href='login.php'>Ir al login</a>");
}

$pdo = conectar();
$id_reserva = $_GET['id'] ?? null;

if (!$id_reserva) {
    header("Location: gestionar_reservas.php");
    exit;
}

try {
    //sql query
    $sqlReserva = "SELECT r.*, h.id_categoria 
                   FROM reservas r
                   JOIN habitaciones h ON r.id_habitacion = h.id_habitacion 
                   WHERE r.id_reserva = :id_reserva";
    
    // If you are NOT staff, the owner restriction applies.
    //In other words, if you are a customer you will only be able to edit your reservation
    if (!in_array($user_rol, $roles_staff)) {
        $sqlReserva .= " AND r.id_cliente = :id_cliente";
        $stmt = $pdo->prepare($sqlReserva);
        $stmt->execute([':id_reserva' => $id_reserva, ':id_cliente' => $_SESSION['usuario_id']]);
    } else {
        $stmt = $pdo->prepare($sqlReserva);
        $stmt->execute([':id_reserva' => $id_reserva]);
    }

    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

    $es_staff = in_array($user_rol, $roles_staff);
    //the customer can`t change the reservation "confirmada"
$puedo_editar = true;
if (!in_array($user_rol, $roles_staff) && $reserva['estado'] !== 'Pendiente') {
    $puedo_editar = false;
}

$bloquear_para_cliente = (!$es_staff && $reserva['estado'] !== 'Pendiente');

    if (!$reserva) {
        throw new Exception("La reserva #$id_reserva no existe o no tienes permiso para verla.");
    }

    // Loading categories for the select
    $categorias = $pdo->query("SELECT id_categoria, nombre, capacidad_maxima FROM categorias_habitacion")->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    //If it fails, we display the error before the header to find out what's happening.
    die("<div class='alert alert-danger'><h3>Error Detectado:</h3>" . $e->getMessage() . "</div>");
}

require_once 'layouts/header.php'; 
?>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Modificar Reserva #<?= htmlspecialchars($reserva['id_reserva']) ?></h4>
        </div>
        <div class="card-body">
            <form action="actualizar_reserva.php" method="POST">
                <input type="hidden" name="id_reserva" value="<?= $reserva['id_reserva'] ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Fecha Entrada</label>
                        <input type="date" class="form-control" name="fecha_entrada" 
                               value="<?= $reserva['fecha_entrada'] ?>" 
                               <?= !$puedo_editar ? 'readonly' : 'required' ?>>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Fecha Salida</label>
                        <input type="date" class="form-control" name="fecha_salida" 
                               value="<?= $reserva['fecha_salida'] ?>" 
                               <?= !$puedo_editar ? 'readonly' : 'required' ?>>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Categoría de Habitación</label>
                        <select class="form-select" name="id_categoria" <?= !$puedo_editar ? 'disabled' : '' ?>>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>" 
                                    <?= ($cat['id_categoria'] == $reserva['id_categoria']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre']) ?> (Máx: <?= $cat['capacidad_maxima'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!$puedo_editar): ?>
                            <input type="hidden" name="id_categoria" value="<?= $reserva['id_categoria'] ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Huéspedes</label>
                        <input type="number" class="form-control" name="huespedes" 
                               value="<?= $reserva['huespedes'] ?>" min="1" 
                               <?= !$puedo_editar ? 'readonly' : 'required' ?>>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Estado de la Reserva</label>
                    <?php if ($es_staff): ?>
                        <select class="form-select border-primary" name="estado" required>
                            <?php 
                            $opciones = ['Pendiente', 'Confirmada', 'Check-in', 'Check-out', 'Cancelada'];
                            foreach($opciones as $opcion): 
                                $selected = ($reserva['estado'] == $opcion) ? 'selected' : '';
                            ?>
                                <option value="<?= $opcion ?>" <?= $selected ?>><?= $opcion ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-info-circle"></i></span>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($reserva['estado']) ?>" readonly>
                        </div>
                        <input type="hidden" name="estado" value="<?= htmlspecialchars($reserva['estado']) ?>">
                        <small class="text-muted">El estado solo puede ser cambiado por el personal del hotel.</small>
                    <?php endif; ?>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        <?php if ($puedo_editar): ?>
                            <button type="submit" class="btn btn-success px-5">Guardar Cambios</button>
                        <?php else: ?>
                            <div class="alert alert-warning d-inline-block py-1 px-3 mb-0">
                                <i class="bi bi-lock-fill"></i> Solo lectura: Reserva <?= htmlspecialchars($reserva['estado']) ?>
                            </div>
                        <?php endif; ?>
                        <a href="gestionar_reservas.php" class="btn btn-outline-secondary ms-2">Volver</a>
                    </div>

                    <?php if ($reserva['estado'] !== 'Cancelada' && $reserva['estado'] !== 'Check-out'): ?>
                        <a href="cancelar_reserva.php?id=<?= $reserva['id_reserva'] ?>" 
                           class="btn btn-outline-danger" 
                           onclick="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');">
                            <i class="bi bi-x-octagon"></i> Cancelar Reserva
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
