<?php
session_start();
<<<<<<< HEAD
// database connection
require_once 'conectar_db.php'; 

// Redirect 
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: registro.html");
    exit;
}

// Data collection
$nombre_usuario  = trim($_POST['nombre_usuario'] ?? '');
$email           = trim($_POST['email'] ?? '');
$password        = trim($_POST['password'] ?? '');
$documento       = trim($_POST['documento_identidad'] ?? '');
$telefono        = trim($_POST['telefono'] ?? '');
$direccion       = trim($_POST['direccion'] ?? '');
$ciudad          = trim($_POST['ciudad'] ?? '');

//basic validations name, email, password
if (empty($nombre_usuario) || empty($email) || empty($password)) {
    $_SESSION['error'] = "Todos los campos obligatorios deben ser llenados (Nombre, Email, Contraseña).";
    header("Location: registro.html"); 
    exit;
}

// secure data preparation
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
// number 6 = client, We make all records customer-level; employee and admin roles are managed from the admin panel.
$rolCliente = 6; 

try {
    $pdo = conectar();
    $pdo->beginTransaction(); // start transaction

    //Check if the email already exists in the USERS table (unique)
    $stmtCheck = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
    $stmtCheck->execute(['email' => $email]);
    if ($stmtCheck->fetch()) {
        $_SESSION['error'] = "El correo ya está registrado. Por favor, inicia sesión.";
        header("Location: registro.html");
        $pdo->rollBack(); 
        exit;
    }

    //When a new client registers, the code is executed
    $sqlUser = "INSERT INTO usuarios (id_rol, nombre_usuario, email, password_hash, estado)
                VALUES (:id_rol, :nombre_usuario, :email, :password_hash, 'Activo')";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute([
        ':id_rol'         => $rolCliente,
        ':nombre_usuario' => $nombre_usuario,
        ':email'          => $email,
        ':password_hash'  => $passwordHash
    ]);
    
    // Get the ID generated for mysql in the clients table 
    $id_usuario = $pdo->lastInsertId();

    // Insert clients
    // We use $id_usuario as id_cliente, forcing synchronization.
    $sqlCliente = "INSERT INTO clientes (id_cliente, nombre, email, documento_identidad, telefono, direccion, ciudad)
                   VALUES (:id_cliente, :nombre, :email, :documento, :telefono, :direccion, :ciudad)";
    $stmtCliente = $pdo->prepare($sqlCliente);
    $stmtCliente->execute([
        ':id_cliente'  => $id_usuario, 
        ':nombre'      => $nombre_usuario,
        ':email'       => $email,
        ':documento'   => $documento,
        ':telefono'    => $telefono,
        ':direccion'   => $direccion,
        ':ciudad'      => $ciudad
    ]);

    $pdo->commit(); // confirm the inserions

    // We establish the session and redirect to the client panel
    $_SESSION['usuario_id']     = $id_usuario;
    $_SESSION['usuario_nombre'] = $nombre_usuario;
    $_SESSION['usuario_rol']    = 'Cliente';
    $_SESSION['mensaje']        = "Registro exitoso. ¡Bienvenido/a a tu panel!";

    header("Location: cliente.php");
    exit;

} catch (PDOException $e) {
    // If something goes wrong, undo both operations
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack(); 
    }
    
    // handling error
    $_SESSION['error'] = "Error en el registro de base de datos: " . $e->getMessage();
    header("Location: registro.html"); // redirects to the form
    exit;
}
?>
=======
require_once 'conectar_db.php'; 

//Registration processing logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //clean and collect input date
    $nombre_usuario  = trim($_POST['nombre_usuario'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = trim($_POST['password'] ?? '');
    $documento       = trim($_POST['documento_identidad'] ?? '');
    $telefono        = trim($_POST['telefono'] ?? '');
    $direccion       = trim($_POST['direccion'] ?? '');
    $ciudad          = trim($_POST['ciudad'] ?? '');
//server-side  mandatory field validation
    if (empty($nombre_usuario) || empty($email) || empty($password)) {
        $error = "Nombre, Email y Contraseña son obligatorios.";
    } else {
        //secure password hashing
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $rolCliente = 6; 

        try {
            $pdo = conectar();
            //start sql transaction to ensure data integrity
            $pdo->beginTransaction(); 

            $stmtCheck = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
            $stmtCheck->execute(['email' => $email]);
            if ($stmtCheck->fetch()) {
                $error = "El correo ya está registrado.";
                $pdo->rollBack();
            } else {
                //insert into "usuarios" table
                $sqlUser = "INSERT INTO usuarios (id_rol, nombre_usuario, email, password_hash, estado)
                            VALUES (:id_rol, :nombre_usuario, :email, :password_hash, 'Activo')";
                $stmtUser = $pdo->prepare($sqlUser);
                $stmtUser->execute([
                    ':id_rol' => $rolCliente,
                    ':nombre_usuario' => $nombre_usuario,
                    ':email' => $email,
                    ':password_hash' => $passwordHash
                ]);
                //Retrieve the generated ID for the linked profile
                $id_usuario = $pdo->lastInsertId();
                //insert into tabla "clientes"
                $sqlCliente = "INSERT INTO clientes (id_cliente, nombre, email, documento_identidad, telefono, direccion, ciudad)
                               VALUES (:id_cliente, :nombre, :email, :documento, :telefono, :direccion, :ciudad)";
                $stmtCliente = $pdo->prepare($sqlCliente);
                $stmtCliente->execute([
                    ':id_cliente'  => $id_usuario, 
                    ':nombre'      => $nombre_usuario,
                    ':email'       => $email,
                    ':documento'   => $documento,
                    ':telefono'    => $telefono,
                    ':direccion'   => $direccion,
                    ':ciudad'      => $ciudad
                ]);
            //commit transaction: save all changes to database
                $pdo->commit();
            //Inicialize session and redirect to customer dashboard    
                $_SESSION['usuario_id'] = $id_usuario;
                $_SESSION['usuario_nombre'] = $nombre_usuario;
                header("Location: cliente.php");
                exit;
            }
        } catch (PDOException $e) {
            //rollback changes if any database error occurs
            if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
            $error = "Error: " . $e->getMessage();
        }
    }
}

//View HTML (UI - RENDERING)
//include to page header
require_once 'layouts/header.php'; 
?>

<main class="container my-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Crear Cuenta</h2>
        <p class="text-muted">Únase a la experiencia Reina Cristina</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger mx-auto" style="max-width: 450px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="registro.php" method="post" class="bg-white p-4 rounded-3 shadow-sm mx-auto border" style="max-width: 500px;">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label small fw-bold">Nombre Completo</label>
                <input type="text" class="form-control form-control-sm" name="nombre_usuario" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">Email</label>
                <input type="email" class="form-control form-control-sm" name="email" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">Contraseña</label>
                <input type="password" class="form-control form-control-sm" name="password" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">DNI / Pasaporte</label>
                <input type="text" class="form-control form-control-sm" name="documento_identidad" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">Teléfono</label>
                <input type="text" class="form-control form-control-sm" name="telefono">
            </div>
            <div class="col-md-12 mb-4">
                <label class="form-label small fw-bold">Ciudad</label>
                <input type="text" class="form-control form-control-sm" name="ciudad">
            </div>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-dark btn-sm rounded-pill py-2">Finalizar Registro</button>
        </div>
    </form>
</main>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    let nombre = document.getElementsByName('nombre_usuario')[0].value.trim();
    let email = document.getElementsByName('email')[0].value.trim();
    let password = document.getElementsByName('password')[0].value;
    let documento = document.getElementsByName('documento_identidad')[0].value.trim();
    
    let errores = [];

    //name validation (min  3 characters)
    if (nombre.length < 3) {
        errores.push("El nombre debe tener al menos 3 caracteres.");
    }

    //email format validation using REGEX
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        errores.push("Por favor, introduce un correo electrónico válido.");
    }

    //password strength validation (Min 8 characters)
    if (password.length < 8) {
        errores.push("La contraseña debe tener al menos 8 caracteres para su seguridad.");
    }

    //mandatory document check
    if (documento === "") {
        errores.push("El documento de identidad es obligatorio para el registro legal.");
    }

    //if validation fails, prevent submission and display alerts
    if (errores.length > 0) {
        e.preventDefault(); //prevent the form from being sent to PHP
        alert("Atención:\n\n" + errores.join("\n"));
    }
});
</script>

<?php 
//include page footer
require_once 'layouts/footer.php'; ?>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
