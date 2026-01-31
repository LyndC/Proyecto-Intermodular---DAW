<?php
session_start();
require_once 'conectar_db.php';

// access control
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'Cliente' || !isset($_GET['id'])) {
    header("Location: login.html");
    exit;
}

$pdo = conectar();
$id_reserva = $_GET['id'];
$id_cliente = $_SESSION['usuario_id'];

try {
    $pdo->beginTransaction();

    // Verify that the reservation belongs to the client and has not already been completed/cancelled
    $stmtCheck = $pdo->prepare("SELECT estado FROM reservas WHERE id_reserva = :id_reserva AND id_cliente = :id_cliente AND estado IN ('Pendiente', 'Confirmada')");
    $stmtCheck->execute([':id_reserva' => $id_reserva, ':id_cliente' => $id_cliente]);
    $reserva = $stmtCheck->fetch();

    if (!$reserva) {
        throw new Exception("Reserva no encontrada, ya está cancelada o no le pertenece.");
    }
    
    //update status
    $sql = "UPDATE reservas SET estado = 'Cancelada' WHERE id_reserva = :id_reserva AND id_cliente = :id_cliente";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_reserva' => $id_reserva, ':id_cliente' => $id_cliente]);

    $pdo->commit();//finalize a transaction and make all changes permanent in the database.

    $_SESSION['success'] = "La reserva con ID {$id_reserva} ha sido cancelada correctamente.";
    header("Location: misreservas.php");
    exit;

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['error'] = "ERROR al cancelar: " . $e->getMessage();
    header("Location: misreservas.php");
    exit;
}
?>