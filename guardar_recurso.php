<?php
session_start();
require_once "conectar_db.php";

// Access control: Only Admin
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = conectar();

    // collect and clean data
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $capacidad = (int)$_POST['capacidad'];
    $precio = (float)$_POST['precio'];
    $dias = (int)($_POST['dias'] ?? 365);
    $nombre_imagen = 'default.jpg'; // default image

    //Process the image upload
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $directorio = "img/";
        
        //Create folder if it doesn't exist
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $extensiones_permitidas)) {
            //unique name: resource_20200522_12345.jpg
            $nombre_imagen = "recurso_" . date("Ymd_His") . "." . $extension;
            $ruta_completa = $directorio . $nombre_imagen;

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
                $nombre_imagen = 'default.jpg'; //If the upload fails, we revert to default.
            }
        }
    }

    //Insert in the database
    try {
        $sql = "INSERT INTO categorias_habitacion (nombre, descripcion, capacidad_maxima, precio_base, disponibilidad_dias, imagen) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $capacidad, $precio, $dias, $nombre_imagen]);

        $_SESSION['success'] = "¡Nueva estancia '$nombre' creada con éxito!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al guardar en la base de datos: " . $e->getMessage();
    }

    //redirect to gestionar_recursos.php
    header("Location: gestionar_recursos.php");
    exit;
} else {
    header("Location: gestionar_recursos.php");
    exit;
}