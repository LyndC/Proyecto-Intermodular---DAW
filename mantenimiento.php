<?php
session_start();
require_once 'conectar_db.php';
$pdo = conectar();

// Access control
if (!isset($_SESSION['usuario_rol']) || in_array(strtolower($_SESSION['usuario_rol']), ['cliente'])) {
    header("Location: login.php");
    exit;
}

require_once 'layouts/header.php';
?>
<!--view HTML-->
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-tools text-warning me-2"></i>Módulo de Mantenimiento</h2>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="display-4 text-warning mb-3">
                    <i class="bi bi-kanban"></i>
                </div>
                <h5 class="fw-bold">Tablero de Tareas</h5>
                <p class="text-muted small">Gestiona el flujo de limpieza y reparaciones del hotel.</p>
                <a href="scrum_board.php" class="btn btn-warning rounded-pill w-100 mt-auto fw-bold">Ver Scrum</a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="display-4 text-primary mb-3">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5 class="fw-bold">Control de Insumos</h5>
                <p class="text-muted small">Inventario de amenities, toallas y productos químicos.</p>
                <a href="gestionar_insumos.php" class="btn btn-primary rounded-pill w-100 mt-auto">Administrar Stock</a>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-5">
        <hr class="w-25 mx-auto mb-4">
        <a href="logout.php" class="btn btn-outline-danger btn-sm border-0">
            <i class="bi bi-box-arrow-right me-2"></i>Finalizar Jornada (Cerrar Sesión)
        </a>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>