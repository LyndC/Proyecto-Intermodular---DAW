<?php
require_once "conectar_db.php";
$pdo = conectar();

// Mapeo manual según categorías
//ya que la columna imagenes se creo despues de hacer el index y habitaciones.php
$imagenes = [
    1 => 'SuiteGold.jpg',
    2 => 'SuiteSilver.jpg',
    3 => 'SuiteSuperior.jpg',
    4 => 'HabitaciónDoble.jpg',
    5 => 'HabitaciónDobleEstandar.jpg',
    6 => 'HabitaciónDobleEconómica.jpg',
    7 => 'HabitaciónIndividual.jpg',
    8 => 'HabitaciónIndividualEstandar.jpg',
    9 => 'HabitaciónAdaptada.jpg'
];

foreach ($imagenes as $id => $nombre_img) {
    $sql = "UPDATE categorias_habitacion SET imagen = ? WHERE id_categoria = ?";
    $pdo->prepare($sql)->execute([$nombre_img, $id]);
}

echo "Base de datos sincronizada con las imágenes de la carpeta.";
?>