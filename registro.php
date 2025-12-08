<?php
session_start();
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