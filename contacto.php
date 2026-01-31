<?php 
require_once 'layouts/header.php'; 
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h4 class="mb-0 text-uppercase fw-bold">Escríbenos</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-center text-muted mb-4">¿Tienes alguna duda sobre un producto o reparación? Déjanos tu mensaje.</p>
                    
                    <form action="https://formspree.io/f/aquiTuEndPoint" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="nombre" class="form-control" placeholder="Ej: Juan Pérez" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="juan@ejemplo.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">¿En qué podemos ayudarte?</label>
                            <textarea name="mensaje" class="form-control" rows="4" placeholder="Cuéntanos..." required></textarea>
                        </div>

                        <input type="hidden" name="_next" value="https://tu-sitio-web/gracias.php">

                        <button type="submit" class="btn btn-warning w-100 fw-bold text-uppercase">
                            Enviar Mensaje <i class="bi bi-send ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="mb-1 text-secondary">O si lo prefieres, llámanos:</p>
                <h5 class="fw-bold"><i class="bi bi-telephone-fill text-warning me-2"></i> 611 04 55 19</h5>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>