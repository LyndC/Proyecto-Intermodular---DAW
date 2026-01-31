<?php
session_start();
require_once 'conectar_db.php';
require_once 'layouts/header.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = conectar();
    $email = $_POST['email'] ?? '';
    $dni = $_POST['dni'] ?? '';
    $pass1 = $_POST['new_pass'] ?? '';
    $pass2 = $_POST['confirm_pass'] ?? '';

    try {
        //Check if passwords match
        if ($pass1 !== $pass2) {
            throw new Exception("Las contraseñas no coinciden.");
        }

        //Verify if the Client exists with that Email and DNI
        //Ensure the DNI belongs to the Email in 'clientes' 
        // AND get the 'id_usuario' from the 'usuarios' table
       $sql = "SELECT u.id_usuario 
            FROM usuarios u
            INNER JOIN clientes c ON u.email = c.email
            WHERE u.email = ? AND c.documento_identidad = ?";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $dni]);
        $user = $stmt->fetch();
        if ($user) {

            //Hash the new password for security
            $hashedPassword = password_hash($pass1, PASSWORD_DEFAULT);

            //Update the password in the database
            $update = $pdo->prepare("UPDATE usuarios SET password_hash = ? WHERE id_usuario = ?");
            $update->execute([$hashedPassword, $user['id_usuario']]);

            $message = "Tu contraseña ha sido actualizada con éxito.";
            $messageType = "success";
        } else {
            throw new Exception("Los datos proporcionados no coinciden con nuestros registros.");
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = "danger";
    }
}
?>
<!--View HTML-->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh; padding-top: 100px;">
    <div class="card shadow-lg p-4 border-0" style="max-width: 500px; width: 100%; border-radius: 15px;">
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock-fill fs-1 text-warning"></i>
            <h3 class="fw-bold mt-2">Recuperar contraseña</h3>
            <p class="text-muted small">Verifica tu identidad para generar una nueva contraseña.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> small text-center">
                <?php echo $message; ?>
            </div>
            <?php if ($messageType == 'success'): ?>
                <div class="d-grid mt-3">
                    <a href="login.php" class="btn btn-dark rounded-pill">Ir al Login</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($messageType != 'success'): ?>
            <form action="recuperar.php" method="post" id="resetForm">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email </label>
                    <input type="email" class="form-control bg-light" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">DNI / Passport</label>
                    <input type="text" class="form-control bg-light" name="dni" required>
                </div>

                <hr class="my-4">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Nueva Contraseña</label>
                    <input type="password" class="form-control bg-light" name="new_pass" id="new_pass" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold">Confirma tu nueva contraseña</label>
                    <input type="password" class="form-control bg-light" name="confirm_pass" id="confirm_pass" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning rounded-pill fw-bold">Actualizar contraseña</button>
                    <a href="login.php" class="btn btn-light rounded-pill small text-muted">Volver al Login</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
// Client-side validation for password matching
document.getElementById('resetForm')?.addEventListener('submit', function(e) {
    const p1 = document.getElementById('new_pass').value;
    const p2 = document.getElementById('confirm_pass').value;

    if (p1 !== p2) {
        e.preventDefault();
        alert("Las contraseñas no coinciden, por favor vuelva a intentarlo.");
    }
});
</script>

<?php require_once 'layouts/footer.php'; ?>