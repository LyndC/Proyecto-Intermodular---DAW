<?php
session_start();
require_once "conectar_db.php";

$id_reserva = $_GET['id'] ?? null;
if (!$id_reserva) die("Reserva no especificada.");

$pdo = conectar();

try {
    // We combined 3 tables: Invoices for the money, Reservations for the dates, and Clients for the name
    $sql = "SELECT f.*, r.fecha_entrada, r.fecha_salida, c.nombre, c.documento_identidad, c.email
            FROM facturas f
            INNER JOIN reservas r ON f.id_reserva = r.id_reserva
            INNER JOIN clientes c ON r.id_cliente = c.id_cliente
            WHERE f.id_reserva = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_reserva]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) die("No hay una factura generada para esta reserva aún.");

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura_<?= $factura['id_factura'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none; } }
        .invoice-box { border: 1px solid #eee; padding: 30px; }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5 shadow-sm bg-white p-5 invoice-box">
        <div class="row mb-4">
            <div class="col-6">
                <h2 class="text-success">HOTEL REINA CRISTINA</h2>
                <p class="text-muted small">CIF: B12345678<br>Passeig de la platja 123, Barcelona</p>
            </div>
            <div class="col-6 text-end">
                <h4>FACTURA Nº: <?= $factura['id_factura'] ?></h4>
                <p>Fecha: <?= date('d/m/Y H:i', strtotime($factura['fecha_emision'])) ?></p>
            </div>
        </div>

        <hr>

        <div class="row my-4">
            <div class="col-6">
                <h6>CLIENTE:</h6>
                <strong><?= htmlspecialchars($factura['nombre']) ?></strong><br>
                DNI: <?= htmlspecialchars($factura['documento_identidad']) ?><br>
                Email: <?= htmlspecialchars($factura['email']) ?>
            </div>
            <div class="col-6 text-end">
                <h6>DETALLES ESTANCIA:</h6>
                Entrada: <?= date('d/m/Y', strtotime($factura['fecha_entrada'])) ?><br>
                Salida: <?= date('d/m/Y', strtotime($factura['fecha_salida'])) ?>
            </div>
        </div>

        <table class="table table-bordered mt-4">
            <thead class="table-light">
                <tr>
                    <th>Concepto</th>
                    <th class="text-end">Importe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Alojamiento y servicios hoteleros (Reserva #<?= $id_reserva ?>)</td>
                    <td class="text-end"><?= number_format($factura['subtotal'], 2) ?>€</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-end">Subtotal</th>
                    <td class="text-end"><?= number_format($factura['subtotal'], 2) ?>€</td>
                </tr>
                <tr>
                    <th class="text-end">IVA / Impuestos</th>
                    <td class="text-end"><?= number_format($factura['impuestos'], 2) ?>€</td>
                </tr>
                <tr class="table-success">
                    <th class="text-end fs-5">TOTAL</th>
                    <td class="text-end fs-5"><strong><?= number_format($factura['total'], 2) ?>€</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-5 text-center no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-printer"></i> Imprimir Factura
            </button>
            <a href="gestionar_reservas.php" class="btn btn-link text-muted">Volver</a>
        </div>
    </div>
</body>
</html>