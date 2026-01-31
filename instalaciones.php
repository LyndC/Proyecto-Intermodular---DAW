<?php
session_start();
require_once "conectar_db.php";
require_once "layouts/header.php"; 

$pdo = conectar();
// Traemos las 6 instalaciones de la tabla que creamos
$sql = "SELECT * FROM instalaciones ORDER BY id_instalacion ASC"; 
$stmt = $pdo->query($sql);
?>

<main class="container my-5">
    <h2 class="text-center fw-bold mb-5">Nuestras Instalaciones</h2>

    <?php while ($inst = $stmt->fetch(PDO::FETCH_ASSOC)): 
        // Mapeamos el ID de la tabla instalaciones a los archivos .jpg
        $foto = "";
        
        switch($inst['id_instalacion']) {
            case 1: $foto = "img/piscina.jpg"; $dec = "Disfrute de nuestra piscina exterior con zona de tumbonas. Abierta todo el año."; break;
            case 2: $foto = "img/gimnasio.jpg"; $dec = "Equipado con máquinas modernas, abierto 24 horas para todos los huéspedes."; break;
            case 3: $foto = "img/restaurante.jpg"; $dec = "Cocina mediterránea y desayunos buffet con productos frescos."; break;
            case 4: $foto = "img/spa.jpg"; $dec = "Relájese con nuestros circuitos termales, sauna y masajes profesionales."; break;
            case 5: $foto = "img/salareuniones.jpg"; $dec = "Salones equipados para eventos, conferencias y celebraciones."; break;
            case 6: $foto = "img/parking.jpg"; $dec = "Parking vigilado 24/7 con acceso directo al hotel."; break;
            
        }
    ?>
    <div class="card mb-4 w-100 shadow-sm border-0 overflow-hidden">
      <div class="row g-0">
        <div class="col-md-6">
          <img src="<?php echo $foto;?>?v=<?php echo time(); ?>" class="img-fluid h-100" alt="<?php echo $inst['nombre']; ?>" style="object-fit: cover; min-height: 250px; width: 100%;">
        </div>
        <div class="col-md-6 d-flex align-items-center">
          <div class="card-body p-4 text-center text-md-start">
            <div class="d-flex align-items-center mb-3 justify-content-center justify-content-md-start">
                <i class="bi <?php echo $inst['icono']; ?> fs-1 text-warning me-3"></i>
                <h4 class="card-title fw-bold text-dark mb-0"><?php echo htmlspecialchars($inst['nombre']); ?></h4>
            </div>
            <p class="card-text text-muted"><?php echo htmlspecialchars($inst['descripcion']); ?></p>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
</main>

<?php require_once "layouts/footer.php"; ?>