<?php
session_start();
require 'conectar_db.php';//database connection

$email = "";
$password = "";
// access control
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");
}

try {
    $pdo = conectar();

    if ($_SERVER["REQUEST_METHOD"] == "POST") { //methos post
        //query sql
        $sql = "SELECT u.id_usuario, u.nombre_usuario, u.password_hash, r.nombre_rol
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id_rol
        WHERE u.email = :email"; 
        //query execution
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":email" => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);// get the data of user  as an array associative

        
        //It checks if the entered username and password match the stored hash
        if ($usuario && password_verify($password, $usuario['password_hash'])) {

            //If the login is successful, the session variables are initialized to keep the user authenticated and save their data.
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_usuario'];
            $_SESSION['usuario_rol'] = $usuario['nombre_rol'];
            //role-based redirection
            switch ($usuario['nombre_rol']) {
                case 'Administrador':
                    header("Location: administrador.php");
                    break;
                case 'Recepcionista':
                case 'Gerencia':
                case 'Contabilidad':
                case 'Mantenimiento':
                    header("Location: empleado.php");
                    break;
                default:
                    header("Location: cliente.php");
                    break;
            }
            exit;

        } else {
            echo "Email o contraseña incorrectos.";
        }
    }
//error capture
} catch (PDOException $e) { 
    echo "Error en la conexión: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
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

<nav aria-label="breadcrumb" class="bg-white shadow-sm">
  <ol class="breadcrumb container py-2">
    <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
    <li class="breadcrumb-item active">Iniciar sesión</li>
  </ol>
</nav>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%;">
        <h3 class="text-center mb-4">Iniciar sesión</h3>

        <form action="login.php" method="post">
            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" placeholder="ejemplo@mail.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="********" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-secondary">Entrar</button>
            </div>

            <p class="text-center mt-3 mb-0">
                ¿No tienes cuenta?
                <a href="registro.html" class="text-primary">Regístrate aquí</a>
            </p>
        </form>
    </div>
</div>

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