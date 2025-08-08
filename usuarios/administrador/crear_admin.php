<?php
// --- Guards y conexión ---
require __DIR__ . "/../../includes/auth.php";
require_login();
require_role('admin');

require __DIR__ . "/../../includes/conexion.php";
$con = conexion();

// --- Procesar POST (crear admin) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Solo recibimos lo que el formulario realmente envía:
    $id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
    $cargo      = trim($_POST['cargo'] ?? '');

    // Validaciones simples
    if ($id_usuario <= 0 || $cargo === '') {
        $_SESSION['flash_error'] = "Selecciona un usuario y escribe el cargo.";
        header("Location: crear_admin.php");
        exit();
    }

    // Evitar duplicados: ¿ya es admin?
    $stmt = $con->prepare("SELECT 1 FROM admin WHERE id_usuario = ? LIMIT 1");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 1) {
        $_SESSION['flash_error'] = "Ese usuario ya es administrador.";
        header("Location: crear_admin.php");
        exit();
    }

    // Insertar en la tabla admin (NO en usuario)
    $stmt = $con->prepare("INSERT INTO admin (id_usuario, cargo) VALUES (?, ?)");
    $stmt->bind_param("is", $id_usuario, $cargo);

    if ($stmt->execute()) {
        $_SESSION['flash_success'] = "Administrador creado correctamente.";
        header("Location: index_admin.php");
        exit();
    } else {
        $_SESSION['flash_error'] = "Error al crear administrador: " . $stmt->error;
        header("Location: crear_admin.php");
        exit();
    }
}

// --- GET: pintar formulario + lista ---
?>
<?php include __DIR__ . "/../../includes/header.php"; ?>

<div class="container mt-4">
    <h2 class="mb-4">Registro de Administradores</h2>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>

    <div class="card p-4 mb-4">
        <form method="POST" action="crear_admin.php">
            <div class="mb-3">
                <label class="form-label">Seleccionar Usuario</label>
                <select class="form-select" name="id_usuario" required>
                    <option value="">-- Selecciona --</option>
                    <?php
                    // Cargamos usuarios existentes
                    $rs = $con->query("SELECT id_usuario, nombre, apellido FROM usuario ORDER BY nombre, apellido");
                    while ($u = $rs->fetch_assoc()):
                    ?>
                        <option value="<?= (int)$u['id_usuario'] ?>">
                            <?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Cargo</label>
                <input class="form-control" type="text" name="cargo" required>
            </div>

            <button class="btn btn-primary" type="submit">Registrar Administrador</button>
        </form>
    </div>

    <h3 class="mt-5 mb-3">Lista de Administradores</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Admin</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cargo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT a.id_admin, u.nombre, u.apellido, a.cargo
                    FROM admin a
                    INNER JOIN usuario u ON u.id_usuario = a.id_usuario
                    ORDER BY a.id_admin DESC";
            $admins = $con->query($sql);
            while ($row = $admins->fetch_assoc()):
            ?>
                <tr>
                    <td><?= (int)$row['id_admin'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['apellido']) ?></td>
                    <td><?= htmlspecialchars($row['cargo']) ?></td>
                    <td>
                        <a class="btn btn-warning btn-sm" href="editar_admin.php?id=<?= (int)$row['id_admin'] ?>">Editar</a>
                        <a class="btn btn-danger btn-sm" href="eliminar_admin.php?id=<?= (int)$row['id_admin'] ?>"
                           onclick="return confirm('¿Eliminar este administrador?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
