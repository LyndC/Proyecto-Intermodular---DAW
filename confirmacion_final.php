<?php
session_start();
require_once 'conectar_db.php';

//Verify that we received the necessary data
$id_reserva = $_GET['id_reserva'] ?? null;
$status = $_GET['payment_intent_client_secret'] ?? null; 

if ($id_reserva && $status) {
    $pdo = conectar();
    
    try {
        $pdo->beginTransaction();

        //Change the Reservation status to 'Confirmed'
        $stmtRes = $pdo->prepare("UPDATE reservas SET estado = 'Confirmada' WHERE id_reserva = ?");
        $stmtRes->execute([$id_reserva]);

        //Obtain the invoice ID and total amount to register the payment
        $stmtFact = $pdo->prepare("SELECT id_factura, total FROM facturas WHERE id_reserva = ?");
        $stmtFact->execute([$id_reserva]);
        $factura = $stmtFact->fetch();

        if ($factura) {
            $id_factura = $factura['id_factura'];
            $monto_total = $factura['total'];

            //change the invoice status to 'Paid'
$stmtUpdFact = $pdo->prepare("UPDATE facturas SET estado = 'Pagada' WHERE id_factura = ?");
$stmtUpdFact->execute([$id_factura]);

//Simplified payment registration
$sqlPago = "INSERT INTO pagos (id_factura, monto, tipo_pago, fecha_pago) VALUES (?, ?, ?, NOW())";
$stmtPago = $pdo->prepare($sqlPago);
$stmtPago->execute([$id_factura, $monto_total, 'Tarjeta/Stripe']);

        }

$pdo->commit();
        $_SESSION['success'] = "¡Pago procesado con éxito! Su reserva #$id_reserva ha sido confirmada.";
        header("Location: misreservas.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error interno al confirmar la reserva: " . $e->getMessage();
        header("Location: misreservas.php");
        exit;
    }
} else {
    $_SESSION['error'] = "El pago no pudo completarse o la sesión expiró.";
    header("Location: nueva_reserva.php");
    exit;
}