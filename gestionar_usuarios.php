<?php
session_start();
require_once "conectar_db.php";

// 1. Lógica de retorno dinámica
$url_retorno = 'admin.php'; // Por defecto
if (isset($_SESSION['usuario_rol'])) {
    $rol = strtolower($_SESSION['usuario_rol']);
    if ($rol === 'gerencia') {
        $url_retorno = 'gerencia.php';
    }
}

// Access Control: Only specific roles can manage users
$roles_permitidos = ['Administrador', 'Gerencia'];
if (!isset($_SESSION['usuario_rol']) || !in_array($_SESSION['usuario_rol'], $roles_permitidos)) {
    header("Location: login.php");
    exit;
}

$pdo = conectar();
$usuarios = [];

try {
    // Query con INNER JOIN
    $sql = "SELECT u.id_usuario, u.nombre_usuario, u.email, u.estado, r.nombre_rol 
            FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.id_rol 
            ORDER BY r.nombre_rol ASC, u.nombre_usuario ASC";
            
    $usuarios = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database Error: " . $e->getMessage();
}

require_once 'layouts/header.php'; 
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-person-gear me-2"></i>Lista de Usuarios</h2>
        <a href="<?php echo $url_retorno; ?>" class="btn btn-outline-secondary rounded-pill shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver al Panel
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card shadow border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-center pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr><td colspan="5" class="text-center py-4">No hay usuarios registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($u['nombre_usuario']); ?></div>
                                <small class="text-muted">ID: #<?php echo $u['id_usuario']; ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($u['email'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge rounded-pill bg-info text-dark">
                                    <?php echo htmlspecialchars($u['nombre_rol']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo ($u['estado'] == 'Activo' || $u['estado'] == '1') ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo htmlspecialchars($u['estado']); ?>
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="editar_usuario.php?id=<?php echo $u['id_usuario']; ?>" class="btn btn-sm btn-outline-primary shadow-sm">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
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