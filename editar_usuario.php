<?php
session_start();
require_once "conectar_db.php";

//Access Control
if (!isset($_SESSION['usuario_rol']) || !in_array($_SESSION['usuario_rol'], ['Administrador', 'Gerencia'])) {
    header("Location: login.php");
    exit;
}

$pdo = conectar();
$id_usuario = $_GET['id'] ?? null;
$message = "";

if (!$id_usuario) {
    header("Location: gestion_usuarios.php");
    exit;
}

//Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $id_rol = $_POST['id_rol'];
    $estado = $_POST['estado'];

    try {
        $sql = "UPDATE usuarios SET nombre_usuario = ?, email = ?, id_rol = ?, estado = ? WHERE id_usuario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $email, $id_rol, $estado, $id_usuario]);
        $message = "<div class='alert alert-success'>User updated successfully!</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

//Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$es_propio_perfil = ($id_usuario == $_SESSION['usuario_id']);

//Fetch all roles for the dropdown
$roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);

require_once 'layouts/header.php';
?>

<div class="container mt-5">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="bi bi-person-gear me-2"></i>Editar Usuario</h2>
    <a href="gestionar_usuarios.php" class="btn btn-outline-secondary rounded-pill shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver a Gestión
        </a>
    </div>

    <div class="row justify-content-center">
                    <?php echo $message; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nombre</label>
                            <input type="text" name="nombre_usuario" class="form-control" value="<?php echo htmlspecialchars($user['nombre_usuario']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="mb-3">
    <label class="form-label fw-bold small">Rol</label>
    <select name="id_rol" class="form-select" <?php echo $es_propio_perfil ? 'disabled' : ''; ?>>
        <?php foreach ($roles as $r): ?>
            <option value="<?php echo $r['id_rol']; ?>" <?php echo ($r['id_rol'] == $user['id_rol']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($r['nombre_rol']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if ($es_propio_perfil): ?>
        <input type="hidden" name="id_rol" value="<?php echo $user['id_rol']; ?>">
        <div class="form-text text-danger">No puedes cambiar tu propio rol.</div>
    <?php endif; ?>
</div>

<div class="mb-4">
    <label class="form-label fw-bold small">Estado de la cuenta</label>
    <select name="estado" class="form-select" <?php echo $es_propio_perfil ? 'disabled' : ''; ?>>
        <option value="Activo" <?php echo ($user['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
        <option value="Inactivo" <?php echo ($user['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
    </select>
    <?php if ($es_propio_perfil): ?>
        <input type="hidden" name="estado" value="<?php echo $user['estado']; ?>">
        <div class="form-text text-danger">No puedes desactivar tu propia cuenta.</div>
    <?php endif; ?>
</div>

<div class="d-grid gap-2">
    <button type="submit" class="btn btn-primary rounded-pill">Guardar cambios</button>
        <a href="gestionar_usuarios.php" class="btn btn-light rounded-pill border">Cancel</a>
</div>
</form>
</div>
</div>
</div>
</div>
</div>

<?php require_once 'layouts/footer.php'; ?>