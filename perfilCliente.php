<?php
session_start();
require_once 'conectar_db.php';

<<<<<<< HEAD
// acces control
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.html");
=======
//acces control
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.php");
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$pdo = conectar();
$error_msg = '';
$success_msg = '';

<<<<<<< HEAD
// update processing
=======
// process update
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');

    try {
        $pdo->beginTransaction();

<<<<<<< HEAD
        //update data in table CLIENTES
=======
        //Update  the CLIENTS table using id_cliente
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
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
<<<<<<< HEAD
        $success_msg = "¡Perfil actualizado correctamente!";

    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_msg = "Error al actualizar el perfil: " . $e->getMessage();
    }
}

// Read data
try {
    // query for obtain data from client and user
    $sqlRead = "SELECT 
                    u.nombre_usuario, u.email AS user_email,
                    c.email AS cliente_email, c.documento_identidad, c.telefono, c.direccion, c.ciudad
=======
        $success_msg = "¡Datos actualizados correctamente!";

    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_msg = "Error: " . $e->getMessage();
    }
}

//readin data
try {
    $sqlRead = "SELECT u.nombre_usuario, u.email, c.documento_identidad, c.telefono, c.direccion, c.ciudad
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
                FROM usuarios u
                JOIN clientes c ON u.id_usuario = c.id_cliente
                WHERE u.id_usuario = :id";
    
    $stmtRead = $pdo->prepare($sqlRead);
    $stmtRead->execute([':id' => $usuario_id]);
    $cliente = $stmtRead->fetch(PDO::FETCH_ASSOC);

<<<<<<< HEAD
    // If for some reason the row does not exist (data error), redirect
    if (!$cliente) {
        throw new Exception("Datos de perfil no encontrados.");
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = "Error al cargar datos: " . $e->getMessage();
    header("Location: cliente.php"); // come back to cliente.php
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
=======
    if (!$cliente) throw new Exception("Perfil no encontrado.");
    
} catch (Exception $e) {
    header("Location: cliente.php");
    exit;
}

//include page heade
require_once "layouts/header.php"; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white p-4">
                    <h3 class="mb-0"><i class="bi bi-person-circle me-2"></i>Mi Perfil</h3>
                </div>
                <div class="card-body p-5">
                    
                    <?php if ($success_msg): ?>
                        <div class="alert alert-success border-0 shadow-sm"><?php echo $success_msg; ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <h5 class="text-primary mb-4 border-bottom pb-2">Información de Cuenta (No editable)</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre Completo</label>
                                <p class="form-control-plaintext border-bottom"><?php echo htmlspecialchars($cliente['nombre_usuario']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext border-bottom"><?php echo htmlspecialchars($cliente['email']); ?></p>
                            </div>
                        </div>

                        <h5 class="text-primary mb-4 border-bottom pb-2">Información de Contacto</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Documento de Identidad</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($cliente['documento_identidad']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" name="ciudad" value="<?php echo htmlspecialchars($cliente['ciudad'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="cliente.php" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold">Guardar Perfil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
//include page footer
require_once "layouts/footer.php"; 
?>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
