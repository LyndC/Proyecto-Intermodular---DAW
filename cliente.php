<?php
<<<<<<< HEAD
session_start();
//If the user role is not defined or is different from cliente, they do not have permission and are redirected to the login page.
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.html");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.html">
      <img src="logoRC.png" alt="Logo" width="120"> Hotel Reina Cristina</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.html">INICIO</a></li>
        <li class="nav-item"><a class="nav-link" href="habitaciones.html">HABITACIONES</a></li>
        <li class="nav-item"><a class="nav-link" href="instalaciones.html">INSTALACIONES</a></li>
        <li class="nav-item"><a class="nav-link" href="login.html">INICIAR SESIÓN</a></li>
        <li class="nav-item"><a class="nav-link" href="contacto.html">CONTACTO</a></li>
        <li class="nav-item"><a class="nav-link" href="reservas.html">RESERVE AHORA</a></li>
        
        <!--Botón para seleccionar el idioma-->
         <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bi bi-globe me-1"></i> ES
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
      <li><a class="dropdown-item" href="?lang=es">Español</a></li>
      <li><a class="dropdown-item" href="?lang=en">English</a></li>
      <li><a class="dropdown-item" href="?lang=fr">Français</a></li>
    </ul>
  </li>

      </ul>
    </div>
  </div>
</nav>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-white shadow-sm">
  <ol class="breadcrumb container py-2">
    <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
    <li class="breadcrumb-item active">Panel Cliente</li>
  </ol>
</nav>

<body class="bg-light">
<div class="container mt-5">
  <h1 class="text-center">Bienvenido Cliente, <?php echo $_SESSION['usuario_nombre']; ?></h1>
  <div class="row mt-4 justify-content-center">
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Reservas</h5>
          <p class="card-text">Haz nuevas reservas o consulta las existentes.</p>
          <a href="misreservas.php" class="btn btn-secondary">Ir</a>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Perfil</h5>
          <p class="card-text">Edita tus datos personales y preferencias.</p>
          <a href="perfilCliente.php" class="btn btn-secondary">Ir</a>
        </div>
      </div>
    </div>
  </div>
  <div class="text-center">
  <a href="logout.php" class="btn btn-danger mt-4">Cerrar Sesión</a>
</div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center p-4 mt-5">
  <img src="logoRC.png" width="120"><br>
  © 2025 Hotel Reina Cristina
  <div class="mt-3 d-flex justify-content-center gap-4">
    <a href="#" class="text-white fs-4">
      <i class="bi bi-twitter"></i>
    </a>
    <a href="#" class="text-white fs-4">
      <i class="bi bi-instagram"></i>
    </a>
    <a href="#" class="text-white fs-4">
      <i class="bi bi-facebook"></i>
    </a>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
=======
 // Client Dashboard Page
session_start();
require_once 'conectar_db.php';

// Access Control Logic 
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.php");
    exit;
}
$pdo = conectar();
// Fetch current client details from the database
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$clientData = $stmt->fetch();
// Include the reusable header layout
require_once 'layouts/header.php';
?>

<nav aria-label="breadcrumb" class="bg-white shadow-sm">
    <ol class="breadcrumb container py-2 mb-0">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item active">Panel Cliente</li>
    </ol>
</nav>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h1>
        <p class="text-muted">Gestiona tus estancias y datos personales desde tu panel privado.</p>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-5">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-calendar-check text-primary fs-1"></i>
                    </div>
                    <h5 class="card-title fw-bold">Reservas</h5>
                    <p class="card-text text-muted">Consulta la disponibilidad de nuestras habitaciones y reserva tu próxima estancia.</p>
                    <a href="reservas.php" class="btn btn-primary rounded-pill px-4">Reservar Ahora</a>
                    <a href="misreservas.php" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Mis Reservas</a>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-person-gear text-secondary fs-1"></i>
                    </div>
                    <h5 class="card-title fw-bold">Mi Perfil</h5>
                    <p class="card-text text-muted">Mantén tus datos actualizados para agilizar tus procesos de check-in.</p>
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#profileModal">
                        Editar Perfil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="logout.php" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
        </a>
    </div>
</main>

<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar Datos Personales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="profileForm">
    <div class="mb-3 text-start">
        <label class="form-label small fw-bold">Nombre Completo</label>
        <input type="text" class="form-control" name="clientName" id="clientName" value="<?php echo htmlspecialchars($clientData['nombre']); ?>">
    </div>

    <div class="mb-3 text-start">
        <label class="form-label small fw-bold">Documento Identidad</label>
        <input type="text" class="form-control" name="docId" id="docId" value="<?php echo htmlspecialchars($clientData['documento_identidad']); ?>">
    </div>

    <div class="row">
        <div class="col-md-6 mb-3 text-start">
            <label class="form-label small fw-bold">Teléfono</label>
            <input type="tel" class="form-control" name="phone" id="phone" value="<?php echo htmlspecialchars($clientData['telefono']); ?>">
        </div>
        <div class="col-md-6 mb-3 text-start">
            <label class="form-label small fw-bold">Ciudad</label>
            <input type="text" class="form-control" name="city" id="city" value="<?php echo htmlspecialchars($clientData['ciudad']); ?>">
        </div>
    </div>

    <div class="mb-3 text-start">
        <label class="form-label small fw-bold">Dirección</label>
        <input type="text" class="form-control" name="address" id="address" value="<?php echo htmlspecialchars($clientData['direccion']); ?>">
    </div>

    <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
</form>
            </div>
        </div>
    </div>
</div>

<script>
// DWEC: Client-side Form Validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    let isValid = true;
    const nameInput = document.getElementById('clientName');
    const nameError = document.getElementById('nameError');

    // Reset errors
    nameError.textContent = "";

    // Validation logic
    if (nameInput.value.trim().length < 3) {
        isValid = false;
        nameError.textContent = "El nombre debe tener al menos 3 caracteres.";
        nameInput.classList.add('is-invalid');
    } else {
        nameInput.classList.remove('is-invalid');
        nameInput.classList.add('is-valid');
    }

    if (!isValid) {
        e.preventDefault(); // Prevent form submission if validation fails
    } else {
        alert("Perfil validado correctamente (Simulación de actualización)");
    }
});
</script>

<?php 
// Include the reusable footer layout
require_once 'layouts/footer.php'; 
?>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
