<?php
session_start();
require_once 'conectar_db.php';

// 1. Control de Acceso y Datos
if ($_SERVER["REQUEST_METHOD"] != "POST" || 
    !isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'Cliente' || !isset($_POST['id_reserva'])) {
    header("Location: login.html");
    exit;
}

$pdo = conectar();
$id_cliente = $_SESSION['usuario_id'];
$id_reserva = $_POST['id_reserva'];

// Datos del formulario
$fecha_entrada = $_POST['fecha_entrada'] ?? null;
$fecha_salida = $_POST['fecha_salida'] ?? null;
$id_categoria_nueva = $_POST['id_categoria'] ?? null;
$huespedes = intval($_POST['huespedes'] ?? 0);
$comentarios = trim($_POST['comentarios'] ?? '');

try {
    // 2. Validación de Fechas y Capacidad (similar a procesar_reserva.php)
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

    // 3. Obtener Capacidad Máxima
    $stmtCat = $pdo->prepare("SELECT capacidad_maxima FROM categorias_habitacion WHERE id_categoria = ?");
    $stmtCat->execute([$id_categoria_nueva]);
    $categoria = $stmtCat->fetch();
    
    if (!$categoria) {
        throw new Exception("Categoría de habitación inválida.");
    }
    if ($huespedes > $categoria['capacidad_maxima']) {
        throw new Exception("El número de huéspedes excede la capacidad máxima de esta categoría ({$categoria['capacidad_maxima']}).");
    }

    // 4. LÓGICA CLAVE: BUSCAR HABITACIÓN DISPONIBLE (EXCLUYENDO LA RESERVA ACTUAL)
    
    // Primero, verificamos si la habitación actual de la reserva sigue disponible para las nuevas fechas/categoría.
    // Si la categoría ha cambiado, necesitamos encontrar una nueva.
    
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

    if (!$habitacionDisponible) {
        throw new Exception("Lo sentimos, no hay habitaciones disponibles en esa categoría para las fechas solicitadas.");
    }

    $id_habitacion_seleccionada = $habitacionDisponible['id_habitacion'];

    // 5. ACTUALIZACIÓN DE LA RESERVA (TRANSACCIÓN)
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
    
    $pdo->commit();

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