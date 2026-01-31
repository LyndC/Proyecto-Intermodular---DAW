<?php
session_start();
require_once "conectar_db.php";

//Access control: Only Admin
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = conectar();

    //collect form data 
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $capacidad = (int)$_POST['capacidad'];
    $precio = (float)$_POST['precio'];
    $dias = (int)$_POST['dias'];
    $imagen_actual = $_POST['imagen_actual'];

    $nombre_imagen = $imagen_actual; // By default we keep the one that was already there

    // new image managament
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $permitidos)) {
            // We created a unique name to avoid cache conflicts
            $nombre_imagen = "hab_" . $id . "_" . time() . "." . $ext;
            $ruta_destino = "img/" . $nombre_imagen;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                // If the new one was uploaded, we deleted the old one (if it exists and is not the default).
                if ($imagen_actual != 'default.jpg' && file_exists("img/" . $imagen_actual)) {
                    unlink("img/" . $imagen_actual);
                }
            }
        }
    }

    // Update in the database
    try {
        $sql = "UPDATE categorias_habitacion 
                SET nombre = ?, 
                    descripcion = ?, 
                    capacidad_maxima = ?, 
                    precio_base = ?, 
                    disponibilidad_dias = ?, 
                    imagen = ? 
                WHERE id_categoria = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $capacidad, $precio, $dias, $nombre_imagen, $id]);

        $_SESSION['mensaje_exito'] = "Recurso '$nombre' actualizado correctamente.";
    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "Error al actualizar: " . $e->getMessage();
    }

    // redirect to gestionar_recursos.php
    header("Location: gestionar_recursos.php");
    exit;
} else {
    header("Location: gestionar_recursos.php");
    exit;
}