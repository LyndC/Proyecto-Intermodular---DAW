<?php
session_start();
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