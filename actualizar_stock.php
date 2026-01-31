<?php
session_start();
require_once 'conectar_db.php';
$pdo = conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id_insumo'];
    $nueva_cantidad = (int)$_POST['cantidad'];

    $sql = "UPDATE insumos SET stock_actual = ? WHERE id_insumo = ?";
    $pdo->prepare($sql)->execute([$nueva_cantidad, $id]);
}

header("Location: gestionar_insumos.php?msg=Stock actualizado");
exit;