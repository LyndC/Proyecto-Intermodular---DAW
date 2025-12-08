<?php
session_start();
require_once 'conectar_db.php';

// access control
if ($_SERVER["REQUEST_METHOD"] != "POST" || 
    !isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.html");
    exit;
}

$pdo = conectar();
$id_cliente = $_SESSION['usuario_id'];
$canal_reserva = 'Web'; 

// datas from form
$fecha_entrada = $_POST['fecha_entrada'] ?? null;
$fecha_salida = $_POST['fecha_salida'] ?? null;
$id_categoria = $_POST['id_categoria'] ?? null;
$huespedes = intval($_POST['huespedes'] ?? 0);


try {
    // validation of required fields
    if (empty($fecha_entrada) || empty($fecha_salida) || empty($id_categoria) || $huespedes < 1) {
        throw new Exception("Por favor, complete todos los campos requeridos.");
    }

    // critical date managament, in php 
    // We create datetime objects
    $dt_entrada = new DateTime($fecha_entrada);
    $dt_salida = new DateTime($fecha_salida);
    
    // Validation that the departure date is later than the arrival date
    if ($dt_salida <= $dt_entrada) {
        throw new Exception("La fecha de salida debe ser posterior a la fecha de entrada.");
    }

    // We format the dates to SQL format (YYYY-MM-DD) for use in queries, ensuring that the database understands them correctly.
    $fecha_entrada_sql = $dt_entrada->format('Y-m-d');
    $fecha_salida_sql = $dt_salida->format('Y-m-d');


    // Obtain Maximum Capacity of the Category
    $stmtCat = $pdo->prepare("SELECT capacidad_maxima FROM categorias_habitacion WHERE id_categoria = ?");
    $stmtCat->execute([$id_categoria]);
    $categoria = $stmtCat->fetch();
    
    if (!$categoria) {
        throw new Exception("Categoría de habitación inválida.");
    }
    if ($huespedes > $categoria['capacidad_maxima']) {
        throw new Exception("El número de huéspedes excede la capacidad máxima de esta categoría ({$categoria['capacidad_maxima']}).");
    }

    // Key logic: look for available room. Avoid overlap
    $sqlDisponibilidad = "
        SELECT h.id_habitacion
        FROM habitaciones h
        WHERE h.id_categoria = :id_categoria
        AND h.estado = 'Disponible'
        AND h.id_habitacion NOT IN (
            SELECT r.id_habitacion
            FROM reservas r
            WHERE r.estado IN ('Confirmada', 'Check-in')
            AND (
                -- Solapamiento: [Entrada solicitada, Salida solicitada] se solapa con [Entrada existente, Salida existente]
                (r.fecha_entrada < :fecha_salida AND r.fecha_salida > :fecha_entrada)
            )
        )
        LIMIT 1";

    $stmtDisp = $pdo->prepare($sqlDisponibilidad);
    $stmtDisp->execute([
        ':id_categoria' => $id_categoria,
        ':fecha_entrada' => $fecha_entrada_sql, // we use the formatted variable
        ':fecha_salida' => $fecha_salida_sql   // we use the formatted variable
    ]);
    
    $habitacionDisponible = $stmtDisp->fetch(PDO::FETCH_ASSOC);

    if (!$habitacionDisponible) {
        throw new Exception("Lo sentimos, no hay habitaciones disponibles en esa categoría para las fechas seleccionadas.");
    }

    $id_habitacion_seleccionada = $habitacionDisponible['id_habitacion'];

    // Create reservation, begin the transaction
    $pdo->beginTransaction();

    // Insert in the table RESERVAS
    $sqlReserva = "INSERT INTO reservas (id_cliente, id_habitacion, fecha_reserva, fecha_entrada, fecha_salida, huespedes, estado, canal_reserva)
                   VALUES (:id_cliente, :id_habitacion, NOW(), :fecha_entrada, :fecha_salida, :huespedes, 'Pendiente', :canal_reserva)";
    
    $stmtReserva = $pdo->prepare($sqlReserva);
    $stmtReserva->execute([
        ':id_cliente' => $id_cliente,
        ':id_habitacion' => $id_habitacion_seleccionada,
        ':fecha_entrada' => $fecha_entrada_sql, // we use the formatted variable
        ':fecha_salida' => $fecha_salida_sql,   // we use the formatted variable
        ':huespedes' => $huespedes,
        ':canal_reserva' => $canal_reserva
    ]);
    
    $id_reserva = $pdo->lastInsertId();

    $pdo->commit();

    $_SESSION['success'] = "¡Reserva realizada con éxito! Su ID de reserva es $id_reserva. Está en estado 'Pendiente' de confirmación.";
    header("Location: misreservas.php"); // redirect to misreservas.php
    exit;

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['error'] = "ERROR de validación/conexión: " . $e->getMessage();
    header("Location: nueva_reserva.php");
    exit;
}
?>