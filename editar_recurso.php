<?php
session_start();
require_once "conectar_db.php";
//Access control: Only admin
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

$pdo = conectar();
//obtain the id 
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: gestionar_recursos.php");
    exit;
}

// Obtain current data for the resource
$stmt = $pdo->prepare("SELECT * FROM categorias_habitacion WHERE id_categoria = ?");
$stmt->execute([$id]);
$recurso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recurso) {
    die("Recurso no encontrado.");
}

require_once 'layouts/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Estancia: <?= htmlspecialchars($recurso['nombre']) ?></h5>
                </div>
                <div class="card-body p-4">
                    <form action="procesar_edicion_recurso.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $recurso['id_categoria'] ?>">
                        <input type="hidden" name="imagen_actual" value="<?= $recurso['imagen'] ?>">

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Nombre de la Estancia</label>
                                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($recurso['nombre']) ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Descripción (¿Sincronizada?)</label>
                                <textarea name="descripcion" class="form-control" rows="4"><?= htmlspecialchars($recurso['descripcion'] ?? '') ?></textarea>
                                <small class="text-muted">Si estaba vacía en la BD, puedes escribirla ahora aquí.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Capacidad Máxima</label>
                                <input type="number" name="capacidad" class="form-control" value="<?= $recurso['capacidad_maxima'] ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Precio Base (€)</label>
                                <input type="number" step="0.01" name="precio" class="form-control" value="<?= $recurso['precio_base'] ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Días Disponibles</label>
                                <input type="number" name="dias" class="form-control" value="<?= $recurso['disponibilidad_dias'] ?? 365 ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Cambiar Imagen</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                                <div class="mt-2">
                                    <small>Imagen actual: <strong><?= $recurso['imagen'] ?></strong></small>
                                </div>
                            </div>

                            <div class="col-12 mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-warning px-5 rounded-pill shadow-sm">Guardar Cambios</button>
                                <a href="gestionar_recursos.php" class="btn btn-outline-secondary px-5 rounded-pill">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>