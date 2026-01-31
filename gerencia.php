<?php
session_start();
require_once 'conectar_db.php';
$pdo = conectar();

//Acces control
if (!isset($_SESSION['usuario_rol']) || !in_array(strtolower($_SESSION['usuario_rol']), ['administrador', 'gerencia'])) {
    header("Location: login.php"); 
    exit;
}
require_once 'layouts/header.php';
?>
<!--View HTML-->
<div class="container mt-5 text-center">
    <h1 class="fw-bold mb-5">Dashboard de Gerencia</h1>
    
    <div class="row g-4 justify-content-center mb-4">
        <div class="col-md-4">
            <a href="contabilidad.php" class="btn btn-dark btn-lg w-100 py-5 shadow">
                <i class="bi bi-graph-up d-block mb-2"></i> Finanzas
            </a>
        </div>
        <div class="col-md-4">
            <a href="gestionar_usuarios.php" class="btn btn-primary btn-lg w-100 py-5 shadow">
                <i class="bi bi-people d-block mb-2"></i> Personal
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-warning text-center">
                <i class="bi bi-kanban display-4 text-warning mb-3"></i>
                <h5 class="fw-bold">Tablero de Tareas (Scrum)</h5>
                <p class="text-muted small">Supervisión operativa de limpieza y mantenimiento.</p>
                <a href="scrum_board.php" class="btn btn-warning mt-2 fw-bold px-5 rounded-pill">Ver Tareas</a>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <a href="admin.php" class="btn btn-link text-muted text-decoration-none">
            <i class="bi bi-arrow-left"></i> Volver al Panel Principal
        </a>
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