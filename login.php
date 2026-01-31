<<<<<<< HEAD
<<<<<<< HEAD
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
=======
<?php
session_start();
require 'conectar_db.php';//database connection

$email = "";
$password = "";
// acces control
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
>>>>>>> 09eaa4351270956f2055f2fcd8bf0990a861eeb1
</html>
=======
<?php
session_start();
require_once 'conectar_db.php';

$error_login = "";

// Access control
// If a session is already active, redirect user to their dashboard based on role.
if (isset($_SESSION['usuario_rol'])) {
    redirigirSegunRol($_SESSION['usuario_rol']);
}

// Authentication Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");

    try {
        $pdo = conectar();
        // SQL query with JOIN to fetch user data and role name simultaneously
        $sql = "SELECT u.id_usuario, u.nombre_usuario, u.password_hash, r.nombre_rol
                FROM usuarios u
                LEFT JOIN roles r ON u.id_rol = r.id_rol
                WHERE u.email = :email"; 
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":email" => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Secure password verification
        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            // Initialize session variables for authentication persistence
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_usuario'];
            $_SESSION['usuario_rol'] = $usuario['nombre_rol'];

            redirigirSegunRol($usuario['nombre_rol']);
        } else {
            $error_login = "Invalid email or password.";
        }
    } catch (PDOException $e) { 
        $error_login = "System error: " . $e->getMessage();
    }
}

// Helper function to manage role-based redirection
 
function redirigirSegunRol($rol) {
    switch ($rol) {
        case 'Administrador':
            header("Location: admin.php");
            break;
        case 'Recepcionista':
            header("Location: recepcion.php");
            break;
        case 'Gerencia':
            header("Location: gerencia.php");
            break;
        case 'Contabilidad':
            header("Location: contabilidad.php");
            break;
        case 'Mantenimiento':
            header("Location: mantenimiento.php");
            break;
        default:
            header("Location: cliente.php");
            break;
    }
    exit;
}

require_once 'layouts/header.php'; 
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-lg p-4 border-0" style="max-width: 400px; width: 100%; border-radius: 15px;">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Inicia Sesión</h3>
            <p class="text-muted small">Acceso a tu cuenta Reina Cristina</p>
        </div>

        <?php if (!empty($error_login)): ?>
            <div class="alert alert-danger py-2 small text-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error_login; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post" id="formLogin">
            <div class="mb-3">
                <label class="form-label small fw-bold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" class="form-control border-start-0 bg-light" name="email" placeholder="name@example.com" required>
                </div>
            </div>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label small fw-bold">Password</label>
                    <a href="recuperar.php" class="small text-decoration-none">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="input-group position-relative">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 bg-light" name="password" id="password" placeholder="********" required>
                    <span class="password-toggle-icon" id="togglePassword">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-dark btn-lg rounded-pill fs-6 py-2 shadow-sm">Entrar</button>
            </div>

            <div class="text-center mt-4">
                <p class="small mb-0 text-muted">
                    ¿Aún no tienes cuenta? 
                    <a href="registro.php" class="text-primary fw-bold text-decoration-none">Registrate Aquí</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
//DWEC
// Basic Form Validation 
document.getElementById('formLogin').addEventListener('submit', function(e) {
    const email = this.email.value.trim();
    if (email === "") {
        e.preventDefault();
        alert("Please enter your email.");
    }
});

//password toggle logic
const togglePassword = document.querySelector('#togglePassword');
const passwordInput = document.querySelector('#password');
const eyeIcon = document.querySelector('#eyeIcon');

if (togglePassword) {
    togglePassword.addEventListener('click', function () {
        // Switch attribute type
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon classes for visual feedback
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
}
</script>

<?php 
// Include page footer component
require_once 'layouts/footer.php'; 
?>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
