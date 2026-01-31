<?php
session_start();
require_once 'conectar_db.php';

//acces control
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$pdo = conectar();
$error_msg = '';
$success_msg = '';

//process update (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');

    try {
        $pdo->beginTransaction();

        //only update the allowed fields in the customers table
        $sql = "UPDATE clientes 
                SET telefono = :telefono, direccion = :direccion, ciudad = :ciudad 
                WHERE id_cliente = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':ciudad'    => $ciudad,
            ':id'        => $usuario_id
        ]);
        
        $pdo->commit();
        $success_msg = "Perfil actualizado con éxito.";

    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_msg = "Error al guardar: " . $e->getMessage();
    }
}

//load current data
$sqlRead = "SELECT u.nombre_usuario, u.email, c.documento_identidad, c.telefono, c.direccion, c.ciudad
            FROM usuarios u
            JOIN clientes c ON u.id_usuario = c.id_cliente
            WHERE u.id_usuario = :id";

$stmtRead = $pdo->prepare($sqlRead);
$stmtRead->execute([':id' => $usuario_id]);
$perfil = $stmtRead->fetch(PDO::FETCH_ASSOC);
//include header
require_once "layouts/header.php"; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-dark text-white p-4">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Mis Datos</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <?php if ($success_msg): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo $success_msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="editar_perfil.php" method="POST">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">NOMBRE DE USUARIO</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($perfil['nombre_usuario']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">DNI / PASAPORTE</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($perfil['documento_identidad']); ?>" disabled>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono de Contacto</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" id="telefono" class="form-control" 
                                       value="<?php echo htmlspecialchars($perfil['telefono']); ?>">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label for="direccion" class="form-label">Dirección Residencial</label>
                                <input type="text" name="direccion" id="direccion" class="form-control" 
                                       value="<?php echo htmlspecialchars($perfil['direccion']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" name="ciudad" id="ciudad" class="form-control" 
                                       value="<?php echo htmlspecialchars($perfil['ciudad']); ?>">
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-4">
                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-pill">
                                ACTUALIZAR PERFIL
                            </button>
                            <a href="cliente.php" class="btn btn-outline-secondary w-100 py-2 rounded-pill">
                                VOLVER
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
 //include footer
require_once "layouts/footer.php"; 
?>