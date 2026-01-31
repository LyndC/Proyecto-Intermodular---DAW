<?php
session_start();
require_once "conectar_db.php";
require_once "layouts/header.php"; 

$pdo = conectar();
$sql = "SELECT * FROM categorias_habitacion ORDER BY id_categoria ASC"; 
$stmt = $pdo->query($sql);
?>

<main class="container my-5">
    <h2 class="text-center fw-bold mb-5">Nuestras Estancias</h2>

    <?php while ($h = $stmt->fetch(PDO::FETCH_ASSOC)): 
        //map the database ID to the .jpg files
        $foto = "";
        $desc = "";

        switch($h['id_categoria']) {
            case 1: $foto = "SuiteGold.jpg"; $desc = "La cómoda y amplia Suite Gold, de 52 m2, diseñada para ofrecer un servicio de categoría superior con jacuzzi y vistas panorámicas."; break;
            case 2: $foto = "SuiteSiver.jpg"; $desc = "Un oasis de tranquilidad de 38m2 en las plantas superiores, con cama King Size y ducha de hidromasaje."; break;
            case 3: $foto = "SuiteSuperior.jpg"; $desc = "La estancia más romántica en la sexta planta. Incluye jacuzzi para dos y gran terraza privada."; break;
            case 4: $foto = "HabitaciónDoble.jpg"; $desc = "Combina confort y diseño moderno con camas queen size y ducha efecto lluvia."; break;
            case 5: $foto = "HabitaciónDobleEstandar.jpg"; $desc = "Funcionales y cómodas, con terraza y baño privado. Ideales para parejas o negocios."; break;
            case 6: $foto = "HabitaciónDobleEconómica.jpg"; $desc = "Sencillas y acogedoras con bonitas vistas al exterior y dos camas individuales."; break;
            case 7: $foto = "HabitaciónIndividual.jpg"; $desc = "Espacio funcional para quienes viajan solos, con Smart TV y ducha efecto lluvia."; break;
            case 8: $foto = "HabitaciónIndividualEstandar.jpg"; $desc = "Pequeña pero muy acogedora, ideal para clientes business y estancias prácticas."; break;
            case 9: $foto = "HabitaciónAdaptada.jpg"; $desc = "Espacio de 20 m² diseñado para movilidad reducida con baño totalmente adaptado."; break;
        }
    ?>
    <div class="card mb-4 w-100 shadow-sm border-0 overflow-hidden">
      <div class="row g-0">
        <div class="col-md-6">
          <img src="<?php echo $foto; ?>" class="img-fluid h-100" alt="<?php echo $h['nombre']; ?>" style="object-fit: cover; min-height: 300px; width: 100%;">
        </div>
        <div class="col-md-6 d-flex align-items-center">
          <div class="card-body p-4">
            <h4 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($h['nombre']); ?></h4>
            <p class="card-text text-muted"><?php echo $desc; ?></p>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span class="badge bg-light text-dark border">Capacidad: <?php echo $h['capacidad_maxima']; ?> pers.</span>
                    <h4 class="text-success mb-0 mt-2"><?php echo $h['precio_base']; ?>€</h4>
                </div>
                <a href="reservas.php?id=<?php echo $h['id_categoria']; ?>" class="btn btn-outline-warning rounded-pill px-4">Reservar</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
</main>

<?php 
//include page footer
require_once "layouts/footer.php"; 
?>