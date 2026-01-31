<?php
session_start();
require_once 'conectar_db.php';
$pdo = conectar();
//acces control
if (!isset($_SESSION['usuario_rol']) || !in_array(strtolower($_SESSION['usuario_rol']), ['administrador', 'contabilidad', 'gerencia'])) {
    header("Location: login.php"); exit;
}
require_once 'layouts/header.php';
?>
<!--View HTML-->
<div class="container mt-5">
    <h2 class="fw-bold mb-4 text-success text-center">Panel Contable Financiero</h2>
    
    <div class="row g-4 justify-content-center">
        
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-4 text-center h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="bi bi-calculator display-4 text-success mb-3"></i>
                    <h3 class="fw-bold">Reportes de Facturación</h3>
                    <p class="text-muted">Genera el balance de ingresos e impuestos para Gerencia.</p>
                    <div class="mt-auto">
                        <a href="reporte_reservas.php" class="btn btn-success btn-lg w-100 shadow">Generar PDF Mensual</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-warning p-4 text-center h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="bi bi-kanban display-4 text-warning mb-3"></i>
                    <h5 class="fw-bold">Tablero de Tareas (Scrum)</h5>
                    <p class="text-muted">Supervisión operativa de limpieza y mantenimiento.</p>
                    <div class="mt-auto">
                        <a href="scrum_board.php" class="btn btn-warning btn-lg w-100 fw-bold">Ver Tareas</a>
                    </div>
                </div>
            </div>
        </div>

    </div> <div class="text-center mt-5">
        <a href="gerencia.php" class="btn btn-link text-muted">Volver al Panel</a>
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