<?php
session_start();
require_once 'conectar_db.php';

// 1. Control de Acceso (Similar al panel)
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.html");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$pdo = conectar();
$error_msg = '';
$success_msg = '';

// --- FASE 1: PROCESAMIENTO DE LA ACTUALIZACIÓN (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');

    try {
        $pdo->beginTransaction();

        // 1. Actualizar datos en la tabla CLIENTES
        $sqlClientes = "UPDATE clientes 
                        SET telefono = :telefono, direccion = :direccion, ciudad = :ciudad 
                        WHERE id_cliente = :id_cliente";
        $stmtClientes = $pdo->prepare($sqlClientes);
        $stmtClientes->execute([
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':ciudad' => $ciudad,
            ':id_cliente' => $usuario_id
        ]);
        
        $pdo->commit();
        $success_msg = "¡Perfil actualizado correctamente!";

    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_msg = "Error al actualizar el perfil: " . $e->getMessage();
    }
}

// --- FASE 2: LECTURA DE DATOS (READ) ---
try {
    // Consulta JOIN para obtener todos los datos del usuario y cliente
    $sqlRead = "SELECT 
                    u.nombre_usuario, u.email AS user_email,
                    c.email AS cliente_email, c.documento_identidad, c.telefono, c.direccion, c.ciudad
                FROM usuarios u
                JOIN clientes c ON u.id_usuario = c.id_cliente
                WHERE u.id_usuario = :id";
    
    $stmtRead = $pdo->prepare($sqlRead);
    $stmtRead->execute([':id' => $usuario_id]);
    $cliente = $stmtRead->fetch(PDO::FETCH_ASSOC);

    // Si por alguna razón la fila no existe (error de datos), redirigir
    if (!$cliente) {
        throw new Exception("Datos de perfil no encontrados.");
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = "Error al cargar datos: " . $e->getMessage();
    header("Location: cliente_panel.php"); // Volver al panel si hay error
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Mi Perfil (Datos Personales)</h2>
    
    <?php if ($error_msg): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>
    <?php if ($success_msg): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>

    <form method="post" action="perfil.php" class="bg-white p-5 rounded-3 shadow-lg mx-auto" style="max-width: 600px;">
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label text-muted">Nombre</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($cliente['nombre_usuario']); ?>" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted">Email (Login)</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($cliente['user_email']); ?>" disabled>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label text-muted">Documento de Identidad</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($cliente['documento_identidad']); ?>" disabled>
        </div>

        <hr class="my-4">
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="telefono" class="form-label text-muted">Teléfono</label>
                <input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?>" placeholder="Teléfono de contacto">
            </div>
            <div class="col-md-6">
                <label for="ciudad" class="form-label text-muted">Ciudad</label>
                <input type="text" class="form-control" name="ciudad" value="<?php echo htmlspecialchars($cliente['ciudad'] ?? ''); ?>" placeholder="Tu ciudad actual">
            </div>
        </div>

        <div class="mb-5">
            <label for="direccion" class="form-label text-muted">Dirección</label>
            <input type="text" class="form-control" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion'] ?? ''); ?>" placeholder="Dirección completa">
        </div>
        
        <div class="d-grid">
            <button type="submit" class="btn btn-secondary btn-lg rounded-pill">Guardar Cambios</button>
        </div>
        
        <div class="text-center mt-3">
            <a href="cliente.php" class="btn btn-link">Volver al Panel</a>
        </div>
    </form>
</div>
</body>
</html>