<?php
session_start();
<<<<<<< HEAD
require_once 'conectar_db.php';

// access control
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.html");
    exit;
}
// Establish the PDO database connection.
$pdo = conectar();
$categorias = []; //// Initialize an array to hold room categories data.
$error_msg = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

// READ  categoríes for  form selector
try {
    //query sql
    $sql = "SELECT id_categoria, nombre, capacidad_maxima, precio_base FROM categorias_habitacion ORDER BY precio_base DESC";
    $stmt = $pdo->query($sql);
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC); //execute the query
} catch (PDOException $e) { //error capture
    $error_msg = "Error al cargar categorías: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Realizar una Nueva Reserva</h2>
    
    <?php if ($error_msg): ?>
        <div class="alert alert-danger mx-auto" style="max-width: 500px;"><?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>

    <form action="procesar_reserva.php" method="post" 
          class="bg-white p-5 rounded-3 shadow-lg mx-auto" 
          style="max-width: 500px;">
        
        <div class="mb-4">
            <label for="fecha_entrada" class="form-label">Fecha de Check-in</label>
            <input type="date" class="form-control" name="fecha_entrada" required min="<?php echo date('Y-m-d'); ?>">
        </div>
        
        <div class="mb-4">
            <label for="fecha_salida" class="form-label">Fecha de Check-out</label>
            <input type="date" class="form-control" name="fecha_salida" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
        </div>
        
        <div class="mb-4">
            <label for="id_categoria" class="form-label">Tipo de Habitación</label>
            <select class="form-select" name="id_categoria" required>
                <option value="">-- Seleccione una Categoría --</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo $cat['id_categoria']; ?>">
                        <?php echo htmlspecialchars($cat['nombre']); ?> 
                        (Máx: <?php echo $cat['capacidad_maxima']; ?> pers. | Precio base: €<?php echo number_format($cat['precio_base'], 2); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label for="huespedes" class="form-label">Número de Huéspedes</label>
            <input type="number" 
                   class="form-control" 
                   name="huespedes" 
                   id="huespedes" 
                   min="1" 
                   max="10" 
                   placeholder="Mínimo 1"
                   required>
        </div>
        
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success btn-lg">Buscar Disponibilidad y Reservar</button>
            <a href="cliente.php" class="btn btn-link">Volver</a>
        </div>
    </form>
</div>
</body>
</html>
=======
require_once "conectar_db.php";

// Access control: Only clients  (to maintain Stripe's flow)
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Cliente') {
    $_SESSION['error'] = "Debes iniciar sesión como cliente para realizar una reserva.";
    header("Location: login.php");
    exit;
}

$pdo = conectar();
$id_cliente = $_SESSION['usuario_id'];

//Obtain available room categories for the form
try {
    $stmt = $pdo->query("SELECT * FROM categorias_habitacion ORDER BY precio_base ASC");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar categorías: " . $e->getMessage());
}

require_once 'layouts/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Nueva Reserva</h4>
                </div>
                <div class="card-body p-4">
                    <form action="procesar_reserva.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha de Entrada</label>
                                <input type="date" name="fecha_entrada" class="form-control" 
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha de Salida</label>
                                <input type="date" name="fecha_salida" class="form-control" 
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Tipo de Habitación</label>
                                <select name="id_categoria" class="form-select" required>
                                    <option value="" selected disabled>Selecciona una categoría...</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo $cat['id_categoria']; ?>">
                                            <?php echo htmlspecialchars($cat['nombre']); ?> 
                                            — <?php echo number_format($cat['precio_base'], 2); ?>€ / noche
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Número de Huéspedes</label>
                                <input type="number" name="huespedes" class="form-control" 
                                       min="1" max="6" value="1" required>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Al continuar, serás redirigido a nuestra pasarela de pago segura para confirmar la reserva.
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow">
                                    <i class="bi bi-credit-card me-2"></i> Continuar al Pago
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
