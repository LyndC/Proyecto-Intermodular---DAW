<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reina Cristina - Sistema de Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white shadow-sm py-0 fixed-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="logoRC.svg" alt="Logo"> Hotel Reina Cristina
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">INICIO</a></li>
        <li class="nav-item"><a class="nav-link" href="habitaciones.php">HABITACIONES</a></li>
        <li class="nav-item"><a class="nav-link" href="instalaciones.php">INSTALACIONES</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">INICIAR SESIÓN</a></li>
        <li class="nav-item"><a class="nav-link" href="reservas.php">RESERVE AHORA</a></li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-globe me-1"></i> ES
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="?lang=es">Español</a></li>
              <li><a class="dropdown-item" href="?lang=en">English</a></li>
            </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>