<?php
session_start();
require_once 'conectar_db.php';
$pdo = conectar();
//We configure the return route according to the role
$url_retorno = 'admin.php'; //default
if (isset($_SESSION['usuario_rol'])) {
    $rol = strtolower($_SESSION['usuario_rol']);
    if ($rol === 'gerencia') {
        $url_retorno = 'gerencia.php';
    } elseif ($rol === 'contabilidad') {
        $url_retorno = 'contabilidad.php'; 
    }
}

//QUERY INVOICES
// We link invoices with reservations to filter by reservation status
try {
    $sql = "SELECT f.*, r.fecha_entrada, r.fecha_salida, r.estado AS estado_reserva 
            FROM facturas f
            JOIN reservas r ON f.id_reserva = r.id_reserva
            WHERE r.estado != 'Cancelada' 
            ORDER BY f.fecha_emision DESC";
            
    $stmt = $pdo->query($sql);
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Accounting calculations based on your 'total' and 'subtotal' columns
    $ingresos_totales = 0;
    $iva_total = 0;
    foreach ($facturas as $f) {
        $ingresos_totales += $f['total'];
        $iva_total += $f['impuestos'];
    }
} catch (PDOException $e) {
    die("Error contable: " . $e->getMessage());
}

require_once 'layouts/header.php';
?>

<!--View HTML-->
<div class="container mt-4">
    <div class="text-center mb-4 no-print">
        <button onclick="window.print()" class="btn btn-danger btn-lg shadow">
            <i class="bi bi-file-pdf"></i> Exportar Informe Contable
        </button>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-5">
            <div class="mb-3 no-print">
        <a href="<?= $url_retorno ?>" class="btn btn-outline-secondary rounded-pill shadow-sm">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>
            <h2 class="text-center fw-bold mb-0">ESTADO FINANCIERO</h2>
          
            <p class="text-center text-muted mb-5">Hotel Management System - Módulo de Gerencia</p>

            <div class="row mb-5 text-center">
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <small class="text-uppercase fw-bold text-muted">Ventas Brutas</small>
                        <h2 class="fw-bold text-dark"><?= number_format($ingresos_totales, 2) ?>€</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <small class="text-uppercase fw-bold text-muted">Impuestos (IVA)</small>
                        <h2 class="fw-bold text-primary"><?= number_format($iva_total, 2) ?>€</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <small class="text-uppercase fw-bold text-muted">Base Imponible</small>
                        <h2 class="fw-bold text-success"><?= number_format($ingresos_totales - $iva_total, 2) ?>€</h2>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nº Factura</th>
                        <th>ID Reserva</th>
                        <th>Fecha Emisión</th>
                        <th>Subtotal</th>
                        <th>Impuestos</th>
                        <th>Total</th>
                        <th>Estado Pago</th>
                    </tr>
                </thead>
                <tbody>
                    
<!--This code block is a dynamic view. Its function is to map the relational
 data from the database and apply format filters (currency and date) and conditional
  logic to make the financial information easy for management to audit.-->
                    <?php foreach ($facturas as $f): ?>
                    <tr>
                        <td><strong>#<?= $f['id_factura'] ?></strong></td>
                        <td><?= $f['id_reserva'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($f['fecha_emision'])) ?></td>
                        <td><?= number_format($f['subtotal'], 2) ?>€</td>
                        <td><?= number_format($f['impuestos'], 2) ?>€</td>
                        <td class="fw-bold"><?= number_format($f['total'], 2) ?>€</td>
                        <td>
                            <span class="badge rounded-pill <?= ($f['estado'] == 'Pagada') ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= $f['estado'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print, .navbar, .btn, footer { display: none !important; }
    .card { border: none !important; }
    body { background: white; }
}
</style>

<?php require_once 'layouts/footer.php'; ?>