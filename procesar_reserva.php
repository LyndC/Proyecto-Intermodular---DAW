<?php
session_start();
require_once 'conectar_db.php';
<<<<<<< HEAD

// access control
if ($_SERVER["REQUEST_METHOD"] != "POST" || 
    !isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.html");
=======
//Check if the request is POST and the user is logged in as a 'Cliente'
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'Cliente') {
    header("Location: login.php");
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    exit;
}

$pdo = conectar();
$id_cliente = $_SESSION['usuario_id'];
$canal_reserva = 'Web'; 
<<<<<<< HEAD

// datas from form
=======
//Retrieve data from POST request
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
$fecha_entrada = $_POST['fecha_entrada'] ?? null;
$fecha_salida = $_POST['fecha_salida'] ?? null;
$id_categoria = $_POST['id_categoria'] ?? null;
$huespedes = intval($_POST['huespedes'] ?? 0);

<<<<<<< HEAD

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
=======
try {
    //Validate that all required fields are filled
    if (empty($fecha_entrada) || empty($fecha_salida) || empty($id_categoria) || $huespedes < 1) {
        throw new Exception("Por favor, complete todos los campos requeridos.");
    }
//Initialize DateTime objects for date manipulation
    $dt_entrada = new DateTime($fecha_entrada);
    $dt_salida = new DateTime($fecha_salida);
    //Logic check: departure date must be strictly after the arrival date
    if ($dt_salida <= $dt_entrada) {
        throw new Exception("La fecha de salida debe ser posterior a la fecha de entrada.");
    }
//Format dates to SQL standard (YYYY-MM-DD) for database insertion
    $fecha_entrada_sql = $dt_entrada->format('Y-m-d');
    $fecha_salida_sql = $dt_salida->format('Y-m-d');

    //availability
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    $sqlDisponibilidad = "
        SELECT h.id_habitacion
        FROM habitaciones h
        WHERE h.id_categoria = :id_categoria
        AND h.estado = 'Disponible'
        AND h.id_habitacion NOT IN (
            SELECT r.id_habitacion
            FROM reservas r
            WHERE r.estado IN ('Confirmada', 'Check-in')
<<<<<<< HEAD
            AND (
                -- Solapamiento: [Entrada solicitada, Salida solicitada] se solapa con [Entrada existente, Salida existente]
                (r.fecha_entrada < :fecha_salida AND r.fecha_salida > :fecha_entrada)
            )
=======
            AND ((r.fecha_entrada < :fecha_salida AND r.fecha_salida > :fecha_entrada))
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
        )
        LIMIT 1";

    $stmtDisp = $pdo->prepare($sqlDisponibilidad);
    $stmtDisp->execute([
        ':id_categoria' => $id_categoria,
<<<<<<< HEAD
        ':fecha_entrada' => $fecha_entrada_sql, // we use the formatted variable
        ':fecha_salida' => $fecha_salida_sql   // we use the formatted variable
=======
        ':fecha_entrada' => $fecha_entrada_sql,
        ':fecha_salida' => $fecha_salida_sql
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    ]);
    
    $habitacionDisponible = $stmtDisp->fetch(PDO::FETCH_ASSOC);

    if (!$habitacionDisponible) {
<<<<<<< HEAD
        throw new Exception("Lo sentimos, no hay habitaciones disponibles en esa categoría para las fechas seleccionadas.");
=======
        throw new Exception("Lo sentimos, no hay habitaciones disponibles.");
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    }

    $id_habitacion_seleccionada = $habitacionDisponible['id_habitacion'];

<<<<<<< HEAD
    // Create reservation, begin the transaction
    $pdo->beginTransaction();

    // Insert in the table RESERVAS
=======
    $pdo->beginTransaction();

    //Insert reservation
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    $sqlReserva = "INSERT INTO reservas (id_cliente, id_habitacion, fecha_reserva, fecha_entrada, fecha_salida, huespedes, estado, canal_reserva)
                   VALUES (:id_cliente, :id_habitacion, NOW(), :fecha_entrada, :fecha_salida, :huespedes, 'Pendiente', :canal_reserva)";
    
    $stmtReserva = $pdo->prepare($sqlReserva);
    $stmtReserva->execute([
        ':id_cliente' => $id_cliente,
        ':id_habitacion' => $id_habitacion_seleccionada,
<<<<<<< HEAD
        ':fecha_entrada' => $fecha_entrada_sql, // we use the formatted variable
        ':fecha_salida' => $fecha_salida_sql,   // we use the formatted variable
=======
        ':fecha_entrada' => $fecha_entrada_sql,
        ':fecha_salida' => $fecha_salida_sql,
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
        ':huespedes' => $huespedes,
        ':canal_reserva' => $canal_reserva
    ]);
    
    $id_reserva = $pdo->lastInsertId();

<<<<<<< HEAD
    $pdo->commit();

    $_SESSION['success'] = "¡Reserva realizada con éxito! Su ID de reserva es $id_reserva. Está en estado 'Pendiente' de confirmación.";
    header("Location: misreservas.php"); // redirect to misreservas.php
=======
    //stay calculation
    $stmtPrecio = $pdo->prepare("SELECT precio_base FROM categorias_habitacion WHERE id_categoria = ?");
    $stmtPrecio->execute([$id_categoria]);
    $precio_noche = $stmtPrecio->fetchColumn();

    $noches = $dt_entrada->diff($dt_salida)->days;
    if ($noches <= 0) $noches = 1; 
    $total_estancia = $precio_noche * $noches;

    //Process Services (Ensuring the actual sum)
    $total_servicios = 0;
    if (isset($_POST['servicios']) && is_array($_POST['servicios'])) {
        foreach ($_POST['servicios'] as $id_servicio) {
            $stmtS = $pdo->prepare("SELECT precio_unitario FROM servicios WHERE id_servicio = ?");
            $stmtS->execute([$id_servicio]);
            $precio_s = $stmtS->fetchColumn();

            if ($precio_s !== false) {
                // force it to be a floating-point number so that it adds up correctly.
                $precio_float = (float)$precio_s; 
                
                $sqlSR = "INSERT INTO servicios_reserva (id_reserva, id_servicio, cantidad, subtotal) VALUES (?, ?, 1, ?)";
                $pdo->prepare($sqlSR)->execute([$id_reserva, $id_servicio, $precio_float]);
                
                //sum where services are added
                $total_servicios += $precio_float;
            }
        }
    }

    //sum of both (room + services)
    $total_final = (float)$total_estancia + (float)$total_servicios;
    
    $impuestos = $total_final * 0.21;
    $subtotal = $total_final - $impuestos;

    //insert invoice with the total
    $sqlFactura = "INSERT INTO facturas (id_reserva, fecha_emision, subtotal, impuestos, total, estado) 
                   VALUES (:id_reserva, NOW(), :subtotal, :impuestos, :total, 'Pendiente')";
    
    $stmtFact = $pdo->prepare($sqlFactura);
    $stmtFact->execute([
        ':id_reserva' => $id_reserva,
        ':subtotal' => $subtotal,
        ':impuestos' => $impuestos,
        ':total' => $total_final //services variable
    ]);
    
    $id_factura = $pdo->lastInsertId();
    $pdo->commit();

    //Redirect by sending the actual total to the gateway.
    header("Location: pasarela_pago.php?id_reserva=$id_reserva&id_factura=$id_factura");
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    exit;

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
<<<<<<< HEAD
    $_SESSION['error'] = "ERROR de validación/conexión: " . $e->getMessage();
    header("Location: nueva_reserva.php");
    exit;
}
?>
=======
    $_SESSION['error'] = "ERROR: " . $e->getMessage();
    header("Location: nueva_reserva.php");
    exit;
}
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
