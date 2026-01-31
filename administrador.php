<?php
session_start();
//If the user role is not defined or is different from administrator, they do not have permission and are redirected to the login page.
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Administrador') {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h1 class="text-center">Bienvenido Administrador, <?php echo $_SESSION['usuario_nombre']; ?></h1>
  <div class="row mt-4">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Gestión de Usuarios</h5>
          <p class="card-text">Crear, editar y eliminar cuentas.</p>
          <a href="gestionar_usuarios.php" class="btn btn-primary">Ir</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Reportes</h5>
          <p class="card-text">Ver estadísticas y reportes del hotel.</p>
          <a href="reportes.php" class="btn btn-primary">Ir</a>
        </div>
      </div>
    </div>
  </div>
  <a href="logout.php" class="btn btn-danger mt-4">Cerrar Sesión</a>
</div>
</body>
</html>