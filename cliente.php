<?php
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