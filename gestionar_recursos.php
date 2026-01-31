<?php
session_start();
require_once "conectar_db.php";
//Access control: Only Admin
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

$pdo = conectar();
$recursos = $pdo->query("SELECT * FROM categorias_habitacion ORDER BY id_categoria DESC")->fetchAll(PDO::FETCH_ASSOC);

require_once 'layouts/header.php';
?>

<div class="container mt-5">
    <div class="mb-3">
        <a href="admin.php" class="btn btn-link text-decoration-none p-0 text-secondary">
            <i class="bi bi-arrow-left-circle-fill fs-4 text-warning"></i> Volver al panel
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-box-seam me-2 text-warning"></i>Gestión de Recursos</h2>
        <button class="btn btn-warning rounded-pill shadow-sm fw-bold px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoRecurso">
            <i class="bi bi-plus-lg"></i> Nueva Estancia
        </button>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success shadow-sm"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach ($recursos as $r): ?>
            <?php
            //check real time
            $hoy = date('Y-m-d');
            //SQL query to count available rooms not currently booked
            $sql_disp = "SELECT COUNT(*) FROM habitaciones h
                         WHERE h.id_categoria = ? 
                         AND h.id_habitacion NOT IN (
                             SELECT id_habitacion FROM reservas 
                             WHERE ? BETWEEN fecha_entrada AND fecha_salida
                         )";
            $stmt_disp = $pdo->prepare($sql_disp);
            $stmt_disp->execute([$r['id_categoria'], $hoy]);
            $habitaciones_libres = $stmt_disp->fetchColumn();
            // Set status badge style based on availability
            $badge_clase = ($habitaciones_libres > 0) ? 'bg-success' : 'bg-danger';
            $texto_disp = ($habitaciones_libres > 0) ? "Disponible ($habitaciones_libres)" : 'Completo';
            ?>
            
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="position-relative">
                        <?php 
                        // Handle resource image display or fallback if file is missing
                        $ruta_fichero = "img/" . $r['imagen'];
                        if(!empty($r['imagen']) && file_exists($ruta_fichero)): 
                        ?>
                            <img src="<?= $ruta_fichero ?>?v=<?= time() ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light text-center py-5 d-flex flex-column justify-content-center" style="height: 200px;">
                                <i class="bi bi-image text-muted fs-1"></i>
                                <small class="text-muted">Sin imagen</small>
                            </div>
                        <?php endif; ?>

                        <span class="position-absolute top-0 end-0 m-2 badge rounded-pill <?= $badge_clase ?> shadow-sm">
                            <?= $texto_disp ?>
                        </span>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title fw-bold text-uppercase"><?= htmlspecialchars($r['nombre']) ?></h5>
                        <p class="card-text text-muted small" style="height: 3em; overflow: hidden;">
                            <?= substr(htmlspecialchars($r['descripcion'] ?? ''), 0, 80) ?>...
                        </p>
                        <ul class="list-unstyled mb-0 small">
                            <li><i class="bi bi-people me-2"></i>Capacidad: <?= $r['capacidad_maxima'] ?></li>
                            <li><i class="bi bi-currency-euro me-2"></i>Precio: <strong><?= number_format($r['precio_base'], 2) ?>€</strong></li>
                        </ul>
                    </div>
                    
                    <div class="card-footer bg-white border-0 d-flex gap-2 pb-3">
                        <a href="editar_recurso.php?id=<?= $r['id_categoria'] ?>" class="btn btn-sm btn-warning w-100 rounded-pill fw-bold">Editar</a>
                        <a href="eliminar_recurso.php?id=<?= $r['id_categoria'] ?>" class="btn btn-sm btn-outline-danger w-100 rounded-pill" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="modalNuevoRecurso" tabindex="-1">
    <div class="modal-dialog">
        <form action="guardar_recurso.php" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold">Crear Nueva Estancia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2" required></textarea>
                </div>
                <div class="row">
                    <div class="col-6 mb-3"><label class="fw-bold">Capacidad</label><input type="number" name="capacidad" class="form-control" required></div>
                    <div class="col-6 mb-3"><label class="fw-bold">Precio</label><input type="number" step="0.01" name="precio" class="form-control" required></div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Imagen</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Guardar</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>