<?php
include('../../includes/conexion.php');       // Conectamos con la BD
include('../../includes/header.php');         // Cargamos el encabezado común (menú, etc.)
$con = conexion();                         // Ejecutamos la función para obtener la conexión
?>

<?php
$sql = "SELECT admin.id_admin, usuario.nombre, usuario.apellido, admin.cargo 
        FROM admin 
        INNER JOIN usuario ON admin.id_usuario = usuario.id_usuario";
$resultado = mysqli_query($con, $sql);
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Registro de Administradores</h2>
    <form action="crear_admin.php" method="POST" class="p-4 bg-light border rounded shadow-sm w-50 mx-auto">
        <div class="mb-3">
            <label for="id_usuario" class="form-label">Seleccionar Usuario</label>
            <select name="id_usuario" id="id_usuario" class="form-select" required>
                <?php
                $usuarios = mysqli_query($con, "SELECT id_usuario, nombre, apellido FROM usuario");
                while ($user = mysqli_fetch_assoc($usuarios)) {
                    echo "<option value='{$user['id_usuario']}'>{$user['nombre']} {$user['apellido']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="cargo" class="form-label">Cargo</label>
            <input type="text" name="cargo" id="cargo" class="form-control" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Registrar Administrador</button>
        </div>
    </form>
</div>

<div class="container mt-5">
    <h2 class="text-center mb-4">Lista de Administradores</h2>
    <table class="table table-bordered table-hover table-striped w-75 mx-auto">
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
            <?php while ($admin = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?= $admin['id_admin'] ?></td>
                    <td><?= $admin['nombre'] ?></td>
                    <td><?= $admin['apellido'] ?></td>
                    <td><?= $admin['cargo'] ?></td>
                    <td>
                        <a href="editar_admin.php?id=<?= $admin['id_admin'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                        <a href="eliminar_admin.php?id=<?= $admin['id_admin'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este administrador?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../../includes/footer.php'); ?>
