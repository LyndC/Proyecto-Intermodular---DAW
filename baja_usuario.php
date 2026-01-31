<?php
session_start();
require_once "conectar_db.php";

//Only the Administrator can activate/deactivate staff.
//the Receptionist can only manage 'Clients', but the Admin manages 'Users'.
if (!isset($_SESSION['usuario_rol']) || strtolower($_SESSION['usuario_rol']) != 'administrador') {
    $_SESSION['error'] = "No tienes permisos de administrador para realizar esta acción.";
    header("Location: empleado.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_a_modificar = $_GET['id'];
    $id_admin_sesion = $_SESSION['usuario_id']; //session id
    $pdo = conectar();

    //prevents the admin from deactivating themselves
    if ($id_a_modificar == $id_admin_sesion) {
        $_SESSION['error'] = "No puedes desactivar tu propia cuenta de administrador.";
        header("Location: gestionar_usuarios.php");
        exit;
    }

    try {
        // toggle: switch logic to turn on and off
        $sql = "UPDATE usuarios SET activo = CASE WHEN activo = 1 THEN 0 ELSE 1 END 
                WHERE id_usuario = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id_a_modificar]);

        $_SESSION['success'] = "Estado del usuario actualizado correctamente.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al cambiar el estado: " . $e->getMessage();
    }
}

//redirect to user/employee management
header("Location: gestionar_usuarios.php");
exit;