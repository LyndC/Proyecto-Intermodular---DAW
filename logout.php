<?php
session_start();

try {
    //We removed all session variables
    $_SESSION = [];

    //Destroy the session
    session_destroy();

    //We redirect to the login
    header("Location: index.php");
    exit;
//error  capture
} catch (Exception $e) {
    die("Error al cerrar sesión.");
}
?>
