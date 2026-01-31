<?php
session_start();
require_once 'conectar_db.php';
$pdo = conectar();
//create
if ($_POST) {
    $sql = "INSERT INTO insumos (nombre, categoria, stock_actual, stock_minimo) VALUES (?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([
        $_POST['nombre'], 
        $_POST['categoria'], 
        $_POST['stock_actual'], 
        $_POST['stock_minimo']
    ]);
}
header("Location: gestionar_insumos.php");