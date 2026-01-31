<?php
session_start();
require_once 'conectar_db.php';
$pdo = conectar();

//acces control
if (!isset($_SESSION['usuario_rol']) || strtolower($_SESSION['usuario_rol']) == 'cliente') {
    header("Location: admin.php?error=unauthorized");
    exit();
}

//logic

// create task
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $titulo = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $id_rol_asignado = (!empty($_POST['id_assigned_to'])) ? (int)$_POST['id_assigned_to'] : null;

    try {
        $sql = "INSERT INTO scrum_tasks (title, description, id_assigned_to, status) VALUES (?, ?, ?, 'To Do')";
        $pdo->prepare($sql)->execute([$titulo, $desc, $id_rol_asignado]);
        header("Location: scrum_board.php?msg=Tarea creada");
        exit();
    } catch (PDOException $e) {
        die("Error al insertar: " . $e->getMessage());
    }
}

// update task
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $sql = "UPDATE scrum_tasks SET status = ? WHERE id_task = ?";
    $pdo->prepare($sql)->execute([$_GET['status'], $_GET['id']]);
    header("Location: scrum_board.php?msg=Estado actualizado");
    exit();
}

// edit task
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id_task'];
    $titulo = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $id_rol = (!empty($_POST['id_assigned_to'])) ? (int)$_POST['id_assigned_to'] : null;

    $sql = "UPDATE scrum_tasks SET title = ?, description = ?, id_assigned_to = ? WHERE id_task = ?";
    $pdo->prepare($sql)->execute([$titulo, $desc, $id_rol, $id]);
    header("Location: scrum_board.php?msg=Tarea actualizada");
    exit();
}

// delete task
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $pdo->prepare("DELETE FROM scrum_tasks WHERE id_task = ?")->execute([$_GET['id']]);
    header("Location: scrum_board.php?msg=Tarea eliminada");
    exit();
}

// reading data
$tasks = $pdo->query("SELECT t.*, r.nombre_rol FROM scrum_tasks t LEFT JOIN roles r ON t.id_assigned_to = r.id_rol ORDER BY t.id_task DESC")->fetchAll(PDO::FETCH_ASSOC);
$staff = $pdo->query("SELECT id_rol, nombre_rol FROM roles WHERE nombre_rol != 'Cliente'")->fetchAll(PDO::FETCH_ASSOC);

require_once 'layouts/header.php';
?>

<div class="container mt-5">
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i> <?= htmlspecialchars($_GET['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="admin.php" class="btn btn-outline-secondary rounded-circle shadow-sm" title="Volver al Panel">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold m-0"><i class="bi bi-kanban me-2 text-warning"></i>Tablero Scrum</h2>
    </div>
    
    <button class="btn btn-warning rounded-pill fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTarea">
        <i class="bi bi-plus-lg me-1"></i> Nueva Tarea
    </button>
</div>

    <div class="row g-4">
        <?php $cols = ['To Do', 'In Progress', 'Done']; foreach($cols as $col): ?>
        <div class="col-md-4">
            <div class="bg-light p-3 rounded shadow-sm border-top border-4 <?= ($col=='Done')?'border-success':'border-warning' ?>" style="min-height: 60vh;">
                <h6 class="fw-bold text-muted mb-3"><?= strtoupper($col) ?></h6>
                
                <?php foreach($tasks as $t): if($t['status'] == $col): ?>
                <div class="card mb-2 border-0 shadow-sm">
                    <div class="card-body p-2">
                        <div class="fw-bold small"><?= htmlspecialchars($t['title']) ?></div>
                        <div class="text-muted mb-2" style="font-size: 0.75rem;">
                            <i class="bi bi-briefcase me-1"></i><?= htmlspecialchars($t['nombre_rol'] ?? 'Sin asignar') ?>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-1">
                            <button type="button" class="btn btn-sm btn-outline-primary p-0 px-1" onclick='abrirEditar(<?= json_encode($t) ?>)'>
                                <i class="bi bi-pencil"></i>
                            </button>

                            <?php foreach($cols as $next): if($next != $col): ?>
                                <a href="?action=update&id=<?= $t['id_task'] ?>&status=<?= $next ?>" class="btn btn-sm btn-outline-secondary p-0 px-1" title="Mover a <?= $next ?>">
                                    <i class="bi bi-arrow-right-short"></i>
                                </a>
                            <?php endif; endforeach; ?>

                            <a href="?action=delete&id=<?= $t['id_task'] ?>" class="btn btn-sm btn-outline-danger p-0 px-1" onclick="return confirm('¿Borrar?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="modalTarea" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="create">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title fw-bold">Nueva Tarea</h5>
            </div>
            <div class="modal-body">
                <input type="text" name="title" class="form-control mb-2" placeholder="Título" required>
                <textarea name="description" class="form-control mb-2" placeholder="Descripción"></textarea>
                <select name="id_assigned_to" class="form-select">
                    <option value="">Asignar a Departamento...</option>
                    <?php foreach($staff as $s): ?>
                        <option value="<?= $s['id_rol'] ?>"><?= $s['nombre_rol'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditarTarea" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_task" id="edit_id_task">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">Editar / Responder</h5>
            </div>
            <div class="modal-body">
                <label class="small fw-bold">Título</label>
                <input type="text" name="title" id="edit_title" class="form-control mb-2" required>
                
                <label class="small fw-bold">Descripción / Respuesta Técnica</label>
                <textarea name="description" id="edit_description" class="form-control mb-2" rows="4"></textarea>
                
                <label class="small fw-bold">Reasignar Departamento</label>
                <select name="id_assigned_to" id="edit_id_assigned" class="form-select">
                    <option value="">Cualquier departamento...</option>
                    <?php foreach($staff as $s): ?>
                        <option value="<?= $s['id_rol'] ?>"><?= $s['nombre_rol'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
// Function to pass task data to the edit modal
function abrirEditar(tarea) {
    document.getElementById('edit_id_task').value = tarea.id_task;
    document.getElementById('edit_title').value = tarea.title;
    document.getElementById('edit_description').value = tarea.description;
    document.getElementById('edit_id_assigned').value = tarea.id_assigned_to || "";
    
    var myModal = new bootstrap.Modal(document.getElementById('modalEditarTarea'));
    myModal.show();
}
</script>

<?php require_once 'layouts/footer.php'; ?>