<?php
session_start();
require_once 'conectar_db.php';

<<<<<<< HEAD
// access control
if ($_SERVER["REQUEST_METHOD"] != "POST" || 
    !isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'Cliente' || !isset($_POST['id_reserva'])) {
    header("Location: login.html");
    exit;
}

$pdo = conectar();
$id_cliente = $_SESSION['usuario_id'];
$id_reserva = $_POST['id_reserva'];

// dorm data
$fecha_entrada = $_POST['fecha_entrada'] ?? null;
$fecha_salida = $_POST['fecha_salida'] ?? null;
$id_categoria_nueva = $_POST['id_categoria'] ?? null;
$huespedes = intval($_POST['huespedes'] ?? 0);
$comentarios = trim($_POST['comentarios'] ?? '');

try {
    // validate dates and capacity 
    if (empty($fecha_entrada) || empty($fecha_salida) || empty($id_categoria_nueva) || $huespedes < 1) {
        throw new Exception("Por favor, complete todos los campos requeridos.");
    }

    $dt_entrada = new DateTime($fecha_entrada);
    $dt_salida = new DateTime($fecha_salida);
    
    if ($dt_salida <= $dt_entrada) {
        throw new Exception("La fecha de salida debe ser posterior a la fecha de entrada.");
    }

    $fecha_entrada_sql = $dt_entrada->format('Y-m-d');
    $fecha_salida_sql = $dt_salida->format('Y-m-d');

    // Get Capacity Max
    $stmtCat = $pdo->prepare("SELECT capacidad_maxima FROM categorias_habitacion WHERE id_categoria = ?");
    $stmtCat->execute([$id_categoria_nueva]);
    $categoria = $stmtCat->fetch();
    
    if (!$categoria) {
        throw new Exception("Categoría de habitación inválida.");
    }
    if ($huespedes > $categoria['capacidad_maxima']) {
        throw new Exception("El número de huéspedes excede la capacidad máxima de esta categoría ({$categoria['capacidad_maxima']}).");
    }

    //  KEY LOGIC: SEARCH FOR AVAILABLE ROOM (EXCLUDING CURRENT RESERVATION)
    
    // First, we check if the current room reservation is still available for the new dates/category.If the category has changed, we need to find a new one.
    
    $sqlDisponibilidad = "
        SELECT h.id_habitacion
        FROM habitaciones h
        WHERE h.id_categoria = :id_categoria
        AND h.estado = 'Disponible'
        AND h.id_habitacion NOT IN (
            SELECT r.id_habitacion
            FROM reservas r
            WHERE r.estado IN ('Confirmada', 'Check-in')
            AND r.id_reserva != :id_reserva_actual -- <--- ¡IMPORTANTE! EXCLUIR LA RESERVA QUE ESTAMOS EDITANDO
            AND (
                (r.fecha_entrada < :fecha_salida AND r.fecha_salida > :fecha_entrada)
            )
        )
        LIMIT 1";

    $stmtDisp = $pdo->prepare($sqlDisponibilidad);
    $stmtDisp->execute([
        ':id_categoria' => $id_categoria_nueva,
        ':id_reserva_actual' => $id_reserva,
        ':fecha_entrada' => $fecha_entrada_sql,
        ':fecha_salida' => $fecha_salida_sql
    ]);
    
    $habitacionDisponible = $stmtDisp->fetch(PDO::FETCH_ASSOC);

    if (!$habitacionDisponible) { // error capture
        throw new Exception("Lo sentimos, no hay habitaciones disponibles en esa categoría para las fechas solicitadas.");
    }

    $id_habitacion_seleccionada = $habitacionDisponible['id_habitacion'];

    // update transaction
    $pdo->beginTransaction();

    $sqlActualizar = "
        UPDATE reservas 
        SET 
            id_habitacion = :id_habitacion, 
            fecha_entrada = :fecha_entrada, 
            fecha_salida = :fecha_salida, 
            huespedes = :huespedes,
            comentarios = :comentarios
        WHERE id_reserva = :id_reserva AND id_cliente = :id_cliente";
    
    $stmtActualizar = $pdo->prepare($sqlActualizar);
    $stmtActualizar->execute([
        ':id_habitacion' => $id_habitacion_seleccionada,
        ':fecha_entrada' => $fecha_entrada_sql,
        ':fecha_salida' => $fecha_salida_sql,
        ':huespedes' => $huespedes,
        ':comentarios' => $comentarios,
        ':id_reserva' => $id_reserva,
        ':id_cliente' => $id_cliente
    ]);
    
    $pdo->commit();// confirmation 

    $_SESSION['success'] = "¡Reserva #{$id_reserva} actualizada con éxito!";
    header("Location: misreservas.php");
    exit;

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['error'] = "ERROR al actualizar: " . $e->getMessage();
    header("Location: editar_reserva.php?id={$id_reserva}"); // Volver al formulario de edición
    exit;
}
?>
=======
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = conectar();
    
    $id_reserva = $_POST['id_reserva'];
    $fecha_in   = $_POST['fecha_entrada'];
    $fecha_out  = $_POST['fecha_salida'];
    $id_cat     = $_POST['id_categoria'];
    $huespedes  = $_POST['huespedes'];
    
    // Roles that can change the STATE
    $roles_staff = ['Administrador', 'Recepcionista', 'Gerencia', 'mantenimiento'];
    $es_staff = in_array($_SESSION['usuario_rol'], $roles_staff);

    try {
        //If you are a CUSTOMER, we first verify that the reservation belongs to you.
        if (!$es_staff) {
            $check = $pdo->prepare("SELECT id_reserva FROM reservas WHERE id_reserva = ? AND id_cliente = ?");
            $check->execute([$id_reserva, $_SESSION['usuario_id']]);
            if (!$check->fetch()) {
                die("No tienes permiso para editar esta reserva.");
            }
            
            //If it's a client, we ignore the POST 'status' for security reasons.
            $sql = "UPDATE reservas SET fecha_entrada = ?, fecha_salida = ?, huespedes = ? WHERE id_reserva = ?";
            $params = [$fecha_in, $fecha_out, $huespedes, $id_reserva];
        } else {
            // If it's STAFF, we allow changing the status and category (which involves reassigning a room)
            $estado = $_POST['estado'];
            $sql = "UPDATE reservas SET fecha_entrada = ?, fecha_salida = ?, huespedes = ?, estado = ? WHERE id_reserva = ?";
            $params = [$fecha_in, $fecha_out, $huespedes, $estado, $id_reserva];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header("Location: gestionar_reservas.php?msg=Reserva actualizada correctamente");
        exit;

    } catch (PDOException $e) {
        die("Error al actualizar: " . $e->getMessage());
    }
}
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
