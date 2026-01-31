<?php
session_start();
require_once 'conectar_db.php';
$pdo = conectar();

$url_retorno = 'admin.php'; // Por defecto
if (isset($_SESSION['usuario_rol'])) {
    $rol = strtolower($_SESSION['usuario_rol']);
    switch ($rol) {
        case 'gerencia':
            $url_retorno = 'gerencia.php';
            break;
        case 'mantenimiento':
            $url_retorno = 'mantenimiento.php';
            break;
        case 'administrador':
            $url_retorno = 'admin.php';
            break;
    }
}

// delete
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM insumos WHERE id_insumo = ?")->execute([$_GET['delete']]);
    header("Location: gestionar_insumos.php?msg=Insumo eliminado");
}
//read
$insumos = $pdo->query("SELECT * FROM insumos ORDER BY categoria ASC")->fetchAll();
require_once 'layouts/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= $url_retorno ?>" class="btn btn-outline-secondary btn-sm me-3">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        <h2><i class="bi bi-box-seam me-2 text-primary"></i>Gestión de Insumos</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInsumo"> + Nuevo Insumo </button>
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Stock Actual</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($insumos as $i): 
                    $alerta = ($i['stock_actual'] <= $i['stock_minimo']) ? 'table-danger' : '';
                ?>
                <tr class="<?= $alerta ?>">
                    <td><?= htmlspecialchars($i['nombre']) ?></td>
                    <td><?= $i['categoria'] ?></td>
                    <td><?= $i['stock_actual'] ?></td>
                    <td>
                        <?php if($i['stock_actual'] <= $i['stock_minimo']): ?>
                            <span class="badge bg-danger">Reponer</span>
                        <?php else: ?>
                            <span class="badge bg-success">OK</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?delete=<?= $i['id_insumo'] ?>" class="text-danger" onclick="return confirm('¿Eliminar?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalInsumo" tabindex="-1">
    <div class="modal-dialog">
        <form action="procesar_insumo.php" method="POST" class="modal-content">
            <div class="modal-header"><h5>Añadir Recurso</h5></div>
            <div class="modal-body">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre (Ej: Toallas)" required>
                <select name="categoria" class="form-select mb-2">
                    <option value="Limpieza">Limpieza</option>
                    <option value="Amenities">Amenities</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                </select>
                <input type="number" name="stock_actual" class="form-control mb-2" placeholder="Cantidad Inicial" required>
                <input type="number" name="stock_minimo" class="form-control mb-2" placeholder="Stock Mínimo Alerta">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Guardar</button>
            </div>
            <td>
    <form action="actualizar_stock.php" method="POST" class="d-flex gap-1">
        <input type="hidden" name="id_insumo" value="<?= $i['id_insumo'] ?>">
        <input type="number" name="cantidad" value="<?= $i['stock_actual'] ?>" class="form-control form-control-sm" style="width: 70px;">
        <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-save"></i></button>
    </form>
</td>
        </form>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>