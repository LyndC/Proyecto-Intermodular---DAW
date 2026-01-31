<?php
session_start();
require_once 'conectar_db.php';
$pdo = conectar();

// Acces control
if (!isset($_SESSION['usuario_rol']) || !in_array(strtolower($_SESSION['usuario_rol']), ['administrador', 'recepcionista', 'gerencia'])) {
    header("Location: login.php"); 
    exit;
}
require_once 'layouts/header.php';
?>
<!--view HTML-->
<div class="container mt-5">
    <h2 class="fw-bold mb-4">Módulo de Recepción</h2>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-warning text-center">
                <i class="bi bi-kanban fa-3x text-warning mb-3"></i>
                <h5>Tablero de Tareas (Scrum)</h5>
                <a href="scrum_board.php" class="btn btn-warning mt-3 fw-bold">Ver Tareas</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-success text-center">
                <i class="fas fa-concierge-bell fa-3x text-success mb-3"></i>
                <h5>Gestión de Reservas</h5>
                <a href="gestionar_reservas.php" class="btn btn-success mt-3">Ir a Reservas</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-info text-center">
                <i class="fas fa-address-card fa-3x text-info mb-3"></i>
                <h5>Directorio de Clientes</h5>
                <a href="gestionar_clientes.php" class="btn btn-info text-white mt-3">Ver Clientes</a>
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
<?php include 'layouts/footer.php'; ?>