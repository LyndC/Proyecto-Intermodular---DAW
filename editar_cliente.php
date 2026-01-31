<?php
session_start();
require_once "conectar_db.php";

//Access control (only staff)
$roles_internos = ['Administrador', 'Recepcionista', 'Gerencia'];
if (!isset($_SESSION['usuario_rol']) || !in_array($_SESSION['usuario_rol'], $roles_internos)) {
    header("Location: login.php");
    exit;
}

$pdo = conectar();
$id_cliente = $_GET['id'] ?? null;
$message = "";

if (!$id_cliente) {
    header("Location: gestionar_clientes.php");
    exit;
}

// processing the update to send the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $dni = $_POST['documento_identidad'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $ciudad = $_POST['ciudad'];

    try {
        $sql = "UPDATE clientes SET nombre = ?, documento_identidad = ?, email = ?, telefono = ?, ciudad = ? WHERE id_cliente = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $dni, $email, $telefono, $ciudad, $id_cliente]);
        $message = "<div class='alert alert-success shadow-sm'>Datos del cliente actualizados con éxito.</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger shadow-sm'>Error al actualizar: " . $e->getMessage() . "</div>";
    }
}

//Obtain current customer data
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
$stmt->execute([$id_cliente]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("Cliente no encontrado.");
}

require_once 'layouts/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <a href="gestionar_clientes.php" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left-circle me-1"></i> Volver al listado
                </a>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white p-3">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Editar Ficha de Cliente: <?php echo htmlspecialchars($cliente['nombre']); ?></h5>
                </div>
                <div class="card-body p-4">
                    <?php echo $message; ?>

                    <form method="POST" class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">DNI / Documento de Identidad</label>
                            <input type="text" name="documento_identidad" class="form-control" value="<?php echo htmlspecialchars($cliente['documento_identidad']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono de Contacto</label>
                            <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ciudad / Origen</label>
                            <input type="text" name="ciudad" class="form-control" value="<?php echo htmlspecialchars($cliente['ciudad']); ?>">
                        </div>

                        <div class="col-12 mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-success px-5 rounded-pill shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                            </button>
                            <a href="gestionar_clientes.php" class="btn btn-outline-secondary px-5 rounded-pill">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-3 p-3 bg-light rounded border">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i> 
                    <strong>Nota:</strong> Los cambios realizados aquí afectan a la ficha personal del cliente, pero no a sus credenciales de acceso.
                </small>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>