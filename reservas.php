<?php
session_start();
require_once "conectar_db.php";
$pdo = conectar();

// The functions are defined before they are used
function meses_espanol($n) {
    $m = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    return isset($m[$n]) ? $m[$n] : "Mes desconocido";
}

// control variables (globally)
$id_categoria = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$mes_vista = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$anio_vista = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

// logic of ocupation
$stmt = $pdo->prepare("SELECT fecha_entrada, fecha_salida FROM reservas 
                       WHERE id_habitacion IN (SELECT id_habitacion FROM habitaciones WHERE id_categoria = ?)
                       AND (YEAR(fecha_entrada) = ? OR YEAR(fecha_salida) = ?)");
$stmt->execute([$id_categoria, $anio_vista, $anio_vista]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fechas_bloqueadas = [];
foreach ($reservas as $res) {
    $periodo = new DatePeriod(
        new DateTime($res['fecha_entrada']), 
        new DateInterval('P1D'), 
        (new DateTime($res['fecha_salida']))->modify('+1 day')
    );
    foreach ($periodo as $f) { $fechas_bloqueadas[] = $f->format('Y-m-d'); }
}

// navigation calculation
$prev_mes = $mes_vista - 1; $prev_anio = $anio_vista;
if ($prev_mes < 1) { $prev_mes = 12; $prev_anio--; }
$next_mes = $mes_vista + 1; $next_anio = $anio_vista;
if ($next_mes > 12) { $next_mes = 1; $next_anio++; }

require_once 'layouts/header.php'; 
?>

<main class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 p-4 mb-4">
                <h2 class="fw-bold mb-3"><i class="bi bi-calendar3 me-2 text-primary"></i>Consultar disponibilidad</h2>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="?id=<?= $id_categoria ?>&mes=<?= $prev_mes ?>&anio=<?= $prev_anio ?>" class="btn btn-sm btn-outline-primary">&laquo; Anterior</a>
                    <span class="fw-bold text-uppercase">
                        <?= meses_espanol($mes_vista) ?> <?= $anio_vista ?>
                    </span>
                    <a href="?id=<?= $id_categoria ?>&mes=<?= $next_mes ?>&anio=<?= $next_anio ?>" class="btn btn-sm btn-outline-primary">Siguiente &raquo;</a>
                </div>

                <div id="calendar-wrapper" class="table-responsive bg-light rounded-3 border p-3">
                    <table id="calendario" class="table table-bordered bg-white mb-0 text-center">
                        </table>
                </div>
                <div class="mt-3 d-flex flex-wrap gap-3 justify-content-center border-top pt-3">
    <div class="d-flex align-items-center">
        <span class="badge rounded-circle p-2 me-2" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">&nbsp;</span>
        <span class="small text-muted">Disponible</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="badge bg-danger rounded-circle p-2 me-2">&nbsp;</span>
        <span class="small text-muted">Ocupado</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="badge bg-primary rounded-circle p-2 me-2">&nbsp;</span>
        <span class="small text-muted">Tu selección</span>
    </div>
</div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 p-4 sticky-top" style="top: 20px;">
                <h4 class="fw-bold">Tu selección</h4>
                <hr>
                <div id="selection-details">
                    <p class="small text-muted" id="status-text">Selecciona un día libre</p>
                </div>
                <form action="nueva_reserva.php" method="GET">
                    <input type="hidden" id="checkin_date" name="checkin_date">
                    <input type="hidden" name="id_categoria" value="<?= $id_categoria ?>">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill" id="btnContinue" disabled>Continuar</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
// synchronization of variables PHP -> JS
const anio = <?= $anio_vista ?>;
const mes = <?= $mes_vista - 1 ?>; 
const fechasOcupadas = <?= json_encode($fechas_bloqueadas) ?>;
const mesesNombres = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

function crearCalendario(elemento, anio, mes) {
    let primerDia = new Date(anio, mes, 1).getDay();
    let diasEnMes = new Date(anio, mes + 1, 0).getDate();
    primerDia = (primerDia === 0) ? 6 : primerDia - 1;

    let html = "<thead><tr><th>L</th><th>M</th><th>X</th><th>J</th><th>V</th><th>S</th><th>D</th></tr></thead><tbody><tr>";

    for (let i = 0; i < primerDia; i++) {
        html += "<td class='empty bg-light'></td>";
    }

    for (let dia = 1; dia <= diasEnMes; dia++) {
        if ((primerDia + dia - 1) % 7 === 0 && dia !== 1) {
            html += "</tr><tr>";
        }

        let mStr = (mes + 1).toString().padStart(2, "0");
        let dStr = dia.toString().padStart(2, "0");
        let fechaHoy = `${anio}-${mStr}-${dStr}`;

        if (fechasOcupadas.includes(fechaHoy)) {
            html += `<td class="p-3 bg-danger text-white" style="cursor:not-allowed; opacity: 0.6;">${dia}</td>`;
        } else {
            html += `<td class="p-3" style="cursor:pointer" onclick="seleccionarDia(this, '${fechaHoy}')">${dia}</td>`;
        }
    }
    html += "</tr></tbody>";
    elemento.innerHTML = html;
}

function seleccionarDia(el, fecha) {
    document.querySelectorAll('#calendario td').forEach(td => td.classList.remove('bg-primary', 'text-white'));
    el.classList.add('bg-primary', 'text-white');
    document.getElementById("checkin_date").value = fecha;
    document.getElementById("status-text").innerHTML = `<div class="alert alert-info py-1 small">Fecha: ${fecha}</div>`;
    document.getElementById("btnContinue").disabled = false;
}

window.onload = () => crearCalendario(document.getElementById("calendario"), anio, mes);
</script>

<?php require_once 'layouts/footer.php'; ?>