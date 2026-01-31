<?php
session_start();
require_once 'conectar_db.php';

//Acces control
if (!isset($_SESSION['usuario_rol']) || strtolower($_SESSION['usuario_rol']) != 'administrador') {
    header("Location: login.php");
    exit;
}

$user_rol = $_SESSION['usuario_rol'];

$pdo = conectar();

// logic of quick statistics
try {
    //total active reservations
    $countReservas = $pdo->query("SELECT COUNT(*) FROM reservas WHERE estado != 'Cancelada'")->fetchColumn();
    //total number of registered users
    $countUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    //available rooms
    $countHabitaciones = $pdo->query("SELECT COUNT(*) FROM habitaciones WHERE estado = 'Disponible'")->fetchColumn();
} catch (PDOException $e) {
    $error_stats = "No se pudieron cargar las estadísticas.";
}
//include header page
require_once 'layouts/header.php'; 
?>
    <div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold">Panel de Administración</h1>
            <p class="lead text-muted">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong>.</p>
            <hr class="w-25 mx-auto border-success border-2">
        </div>
    </div>
    <div class="row mb-5 g-3">
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-uppercase mb-1" style="font-size: 0.8rem;">Reservas Activas</h6>
                        <h2 class="mb-0"><?php echo $countReservas ?? '0'; ?></h2>
                    </div>
                    <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-uppercase mb-1" style="font-size: 0.8rem;">Total Usuarios</h6>
                        <h2 class="mb-0"><?php echo $countUsuarios ?? '0'; ?></h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-uppercase mb-1" style="font-size: 0.8rem;">Hab. Disponibles</h6>
                        <h2 class="mb-0"><?php echo $countHabitaciones ?? '0'; ?></h2>
                    </div>
                    <i class="fas fa-bed fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition text-center p-4">
                <div class="icon-box bg-light-success rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-concierge-bell text-success fa-2x"></i>
                </div>
                <h5 class="fw-bold">Gestión de Reservas</h5>
                <p class="text-muted small">Ver todas las reservas y estados de Check-in/Out.</p>
                <a href="recepcion.php" class="btn btn-success rounded-pill w-100 mt-auto">Entrar</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition text-center p-4">
                <div class="icon-box bg-light-primary rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-shield text-primary fa-2x"></i>
                </div>
                <h5 class="fw-bold">Gestión de Usuarios</h5>
                <p class="text-muted small">Crear administradores y gestionar permisos.</p>
                <a href="gestionar_usuarios.php" class="btn btn-primary rounded-pill w-100 mt-auto">Entrar</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition text-center p-4">
                <div class="icon-box bg-light-warning rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-hotel text-warning fa-2x"></i>
                </div>
                <h5 class="fw-bold">Gestión de Recursos</h5>
                <p class="text-muted small">Configurar tipos de habitación y precios.</p>
                <a href="gestionar_recursos.php" class="btn btn-warning rounded-pill w-100 mt-auto text-dark fw-bold">Entrar</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition text-center p-4">
                <div class="icon-box bg-light-info rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-address-card text-info fa-2x"></i>
                </div>
                <h5 class="fw-bold">Fichas de Clientes</h5>
                <p class="text-muted small">Consultar datos de contacto de los huéspedes.</p>
                <a href="gestionar_clientes.php" class="btn btn-info text-white rounded-pill w-100 mt-auto">Ver Directorio</a>
            </div>
        </div>

        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] !== 'Cliente'): ?>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition text-center p-4" style="background-color: #fff9e6;">
                <div class="icon-box bg-warning rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-kanban text-dark fs-2"></i>
                </div>
                <h5 class="fw-bold">Gestión de Tareas</h5>
                <p class="text-muted small">Organiza el trabajo del staff y revisa el progreso.</p>
                <a href="scrum_board.php" class="btn btn-warning rounded-pill w-100 mt-auto fw-bold">Abrir Tablero</a>
            </div>
        </div>

        <div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 h-100 text-center">
        <div class="card-body">
            <div class="display-4 text-success mb-3">
                <i class="bi bi-box-seam"></i>
            </div>
            <h5 class="fw-bold">Gestión de Insumos</h5>
            <p class="text-muted small">Control de stock, amenities y materiales de limpieza.</p>
            <a href="mantenimiento.php" class="btn btn-success rounded-pill px-4">Administrar</a>
        </div>
    </div>
</div>
<?php if(in_array($user_rol, ['Administrador', 'Gerencia', 'Contabilidad'])): ?>
<div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
            <div class="display-4 text-success mb-3">
                <i class="bi bi-calculator"></i>
            </div>
            <h5 class="fw-bold">Panel Contable</h5>
            <p class="text-muted small">Visualización de facturas, impuestos y balances.</p>
            <a href="reporte_reservas.php" class="btn btn-success rounded-pill px-4">Ver Reportes</a>
        </div>
    </div>
</div>
<?php endif; ?>
        <?php endif; ?>

    </div> <div class="text-center mt-5">
        <a href="logout.php" class="btn btn-outline-danger btn-sm border-0">
            <i class="fas fa-sign-out-alt me-1"></i> Desconectar de la sesión
        </a>
    </div>
</div>
<?php
//include page footer
require_once 'layouts/footer.php'; ?>