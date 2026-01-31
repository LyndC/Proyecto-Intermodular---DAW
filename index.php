<<<<<<< HEAD
<?php
session_start();//database connection
require 'conectar_db.php';
$pdo = conectar();

if (isset($_SESSION['usuario_rol'])) {
    if ($_SESSION['usuario_rol'] == 'admin') {
        header("Location: admin.php");
    } elseif ($_SESSION['usuario_rol'] == 'empleado') {
        header("Location: empleado.php");
    } else {
        header("Location: cliente.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reina Cristina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<!--Navbar-->
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.html">
      <img src="logoRC.svg" alt="Logo" width="120"> Hotel Reina Cristina
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">INICIO</a></li>
        <li class="nav-item"><a class="nav-link" href="habitaciones.html">HABITACIONES</a></li>
        <li class="nav-item"><a class="nav-link" href="instalaciones.html">INSTALACIONES</a></li>
        <li class="nav-item"><a class="nav-link" href="login.html">INICIAR SESIÓN</a></li>
        <li class="nav-item"><a class="nav-link" href="contacto.html">CONTACTO</a></li>
        <li class="nav-item"><a class="nav-link" href="reservas.html">RESERVE AHORA</a></li>
        <!--Botón para seleccionar el idioma-->
         <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bi bi-globe me-1"></i> ES
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
      <li><a class="dropdown-item" href="?lang=es">Español</a></li>
      <li><a class="dropdown-item" href="?lang=en">English</a></li>
      <li><a class="dropdown-item" href="?lang=fr">Français</a></li>
    </ul>
  </li>

      </ul>
    </div>
  </div>
</nav>
<!--landing-->
<section class="bg-dark text-white text-center p-5">
    <div class="container">
      <h1 class="display-4 fw-bold">Bienvenido al Hotel Reina Cristina</h1>
      <p class="lead">Lujo, comodidad y experiencias únicas en la Costa Brava.</p>
      <a href="reservas.html" class="btn btn-warning btn-lg mt-3">Reserve Ahora</a>
    </div>
  </section>
=======
<?php 
//database conection
require_once 'conectar_db.php';
//include page header
require_once 'layouts/header.php'; ?>

<!--Carrousel with 3 pictures-->
<div id="carouselHotel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselHotel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#carouselHotel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#carouselHotel" data-bs-slide-to="2"></button>
    </div>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)

    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/SuiteGold.jpg" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="Suite Gold">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                <h5>Suite Gold</h5>
                <p>Exclusividad absoluta.</p>
            </div>
        </div>

<<<<<<< HEAD
<!--Breadcrumb-->
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.html"><i class="bi bi-house-door"></i>Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Habitaciones</li>
  </ol>
</nav>
<!-- Carousel -->
 <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="HabitaciónDoble.jpg" class="d-block w-100" alt="HabitaciónDoble">
      <div class="carousel-caption d-none d-md-block">
        <h5>Habitación Doble de Lujo</h5>
        <p>Perfecta para parejas o amigos, esta habitación doble de lujo destaca por su amplitud y decoración sofisticada. Disfrute de camas confortables, iluminación cálida y un ambiente pensado para el descanso.</p>
      </div>
</div>
   <div class="carousel-item">
    <img src="HabitaciónFamiliar.jpg" class="d-block w-100" alt="Habitacióndoble">
    <div class="carousel-caption d-none d-md-block">
       <h5>Habitación Familiar</h5>
       <p>Nuestras habitaciones familiares son cómodas, funcionales y con todo lo necesario para disfrutar de una estancia memorable en familia.</p>
    </div>
   </div>
   <div class="carousel-item">
     <img src="HabitaciónIndividual.jpg" class="d-block w-100" alt="HabitaciónIndividual">
     <div class="carousel-caption d-none d-md-block">
       <h5>Habitación Individual Superior</h5>
       <p>Diseñada para quienes viajan solos, la habitación individual ofrece privacidad y funcionalidad sin renunciar al confort. Un espacio acogedor con escritorio y cama cómoda para relajarse tras un día de viaje.</p>
     </div>
   </div>
 </div>
 <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
   <span class="visually-hidden">Previous</span>
 </button>
 <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
   <span class="carousel-control-next-icon" aria-hidden="true"></span>
   <span class="visually-hidden">Next</span>
 </button>
</div>
<!--Cards-->
<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-4">
<div class="card h-100 shadow-sm text-center">
  <img src="SuiteGold.jpg" class="card-img-top" alt="SuiteGold">
  <div class="card-body">
    <h5>Suite Gold</h5>
  <p class="card-text">La Suite Gold es sinónimo de exclusividad y lujo. Su diseño elegante con acabados dorados, 
    cama king size y zona de estar independiente crean un ambiente sofisticado. 
  Ideal para quienes buscan una experiencia única, combina confort moderno con detalles que marcan la diferencia.</p>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card h-100 shadow-sm text-center">
    <img src="SuiteSiver.jpg" class="card-img-top" alt="SuiteSiver">
    <div class="card-body">
        <h5>Suite Silver</h5>
    <p class="card-text">La Suite Silver ofrece un equilibrio perfecto entre estilo y comodidad. 
        Con decoración contemporánea en tonos plateados, cama amplia y espacios luminosos, 
        es la opción ideal para estancias tranquilas con un toque de elegancia moderna.</p>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card h-100 shadow-sm text-center">
    <img src="SuiteSuperior.jpg" class="card-img-top" alt="SuiteSuperior">
    <div class="card-body">
        <h5>Suite Junior</h5>
    <p class="card-text"> Nuestra Suite Junior combina una cuidada decoración moderna con unas magníficas vistas panorámicas al mar Mediterráneo. 
  Ofrece un ambiente íntimo y acogedor, ideal para parejas que buscan una experiencia romántica. 
  Dispone de cama king size, zona de estar independiente y todas las comodidades modernas para garantizar una estancia inolvidable.</p>
</div>
</div>
</div>
</div>
</div>
=======
        <div class="carousel-item">
            <img src="img/SuiteSilver.jpg" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="Habitación Deluxe">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                <h5>Suite Silver</h5>
                <p>Elegancia contemporánea con vistas inmejorables</p>
            </div>
        </div>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)

        <div class="carousel-item">
            <img src="img/SuiteSuperior.jpg" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="Piscina">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                <h5>Suite Superior</h5>
                <p>Espacios amplios diseñados para una estancia inolvidable.</p>
            </div>
        </div>
    </div>

<<<<<<< HEAD
<!--Paginación -->
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item"><a class="page-link border-secondary text-secondary" href="index.html">Previous</a></li>
    <li class="page-item"><a class="page-link border-secondary text-secondary" href="habitaciones.html">1</a></li>
    <li class="page-item"><a class="page-link border-secondary text-secondary" href="reservas.html">2</a></li>
    <li class="page-item"><a class="page-link border-secondary text-secondary" href="contacto.html">3</a></li>
    <li class="page-item"><a class="page-link border-secondary text-secondary" href="login.html">Next</a></li>
  </ul>
</nav>
<!--CTA-->
<section class="bg-light text-center p-5">
  <h2 class="fw-bold">¿Listo para su próxima experiencia?</h2>
  <p>Reserve hoy y viva momentos inolvidables en el Hotel Reina Cristina.</p>
  <a href="reservas.html" class="btn btn-dark btn-lg">Reservar Ahora</a>
</section>
<!--footer-->
<footer class="bg-dark text-white text-center p-4 mt-5">
  <img src="logoRC.svg" width="120"><br>
  © 2025 Hotel Reina Cristina.
  <div class="mt-3 d-flex justify-content-center gap-4">
    <a href="#" class="text-white fs-4">
      <i class="bi bi-twitter"></i>
    </a>
    <a href="#" class="text-white fs-4">
      <i class="bi bi-instagram"></i>
    </a>
    <a href="#" class="text-white fs-4">
      <i class="bi bi-facebook"></i>
    </a>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
=======
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselHotel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselHotel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
<!--CTA (call to action)-->
<section class="bg-dark text-white text-center p-5">
    <div class="container">
      <h1 class="display-4 fw-bold">Bienvenido al Hotel Reina Cristina</h1>
      <p class="lead">Lujo, comodidad y experiencias únicas en la Costa Brava.</p>
      <a href="reservas.php" class="btn btn-warning btn-lg mt-3">Reserve Ahora</a>
    </div>
</section>

<!--Cards-->
<div class="container mt-5 mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Nuestras Suites Exclusivas</h2>
        <p class="text-muted">Seleccione la estancia que mejor se adapte a sus deseos</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="img/SuiteGold.jpg" class="card-img-top" alt="Suite Gold" style="height: 250px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Suite Gold</h5>
                    <p class="card-text text-muted">El máximo exponente del lujo. Incluye jacuzzi privado y vistas frontales al mar.</p>
                    <div class="d-grid">
                        <a href="reservas.php" class="btn btn-outline-dark">Ver Disponibilidad</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="img/SuiteSilver.jpg" class="card-img-top" alt="Suite Silver" style="height: 250px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Suite Silver</h5>
                    <p class="card-text text-muted">Elegancia moderna. Disfrute de una terraza privada y servicio de desayuno en la habitación.</p>
                    <div class="d-grid">
                        <a href="reservas.php" class="btn btn-outline-dark">Ver Disponibilidad</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="img/SuiteSuperior.jpg" class="card-img-top" alt="Suite Platinum" style="height: 250px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Suite Superior</h5>
                    <p class="card-text text-muted">Espacios amplios y minimalistas. Ideal para parejas que buscan tranquilidad absoluta.</p>
                    <div class="d-grid">
                        <a href="reservas.php" class="btn btn-outline-dark">Ver Disponibilidad</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="bg-light text-center p-5 mt-5">
  <h2 class="fw-bold">¿Listo para su próxima experiencia?</h2>
  <a href="reservas.php" class="btn btn-dark btn-lg">Reservar Ahora</a>
</section>

<?php 
//include page footer
require_once 'layouts/footer.php'; ?>
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
