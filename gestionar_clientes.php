<?php
session_start();
require_once "conectar_db.php";

// Access control
$roles_internos = ['Administrador', 'Recepcionista', 'Gerencia'];
if (!isset($_SESSION['usuario_rol']) || !in_array($_SESSION['usuario_rol'], $roles_internos)) {
    header("Location: login.php");
    exit;
}

$pdo = conectar();
$clientes = [];
$busqueda = $_GET['search'] ?? ''; // We capture the search if it exists

try {
    if (!empty($busqueda)) {
        //SQL query (search for DNI or name) whit LIKE 
        $sql = "SELECT id_cliente, nombre, email, documento_identidad, telefono, direccion, ciudad 
                FROM clientes 
                WHERE documento_identidad LIKE ? OR nombre LIKE ?
                ORDER BY nombre ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$busqueda%", "%$busqueda%"]);
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        //normal query
        $sql = "SELECT id_cliente, nombre, email, documento_identidad, telefono, direccion, ciudad 
                FROM clientes 
                ORDER BY nombre ASC";
        $clientes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = "Error al cargar clientes: " . $e->getMessage();
}

require_once 'layouts/header.php'; 
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success"><i class="bi bi-people-fill me-2"></i>Gestión de Clientes</h2>
        <a href="admin.php" class="btn btn-outline-secondary rounded-pill shadow-sm">Volver al Panel</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 rounded-end-pill" 
                           placeholder="Buscar por DNI o Nombre..." 
                           value="<?php echo htmlspecialchars($busqueda); ?>">
                </div>
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Buscar</button>
                <?php if(!empty($busqueda)): ?>
                    <a href="gestionar_clientes.php" class="btn btn-light rounded-pill border">Limpiar</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nombre</th>
                        <th>DNI / Documento</th>
                        <th>Email / Contacto</th>
                        <th class="text-center pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No se encontraron clientes.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?php echo $c['id_cliente']; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($c['nombre']); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($c['documento_identidad']); ?></span></td>
                            <td><?php echo htmlspecialchars($c['email']); ?></td>
                            <td class="text-center pe-4">
                                <a href="editar_cliente.php?id=<?php echo $c['id_cliente']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
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

<script>
function confirmarBaja(id) {
    if (confirm('¿Estás seguro de que deseas eliminar permanentemente a este cliente de la base de datos?')) {
        window.location.href = 'eliminar_cliente.php?id=' + id;
    }
}
</script>

<?php require_once 'layouts/footer.php'; ?>