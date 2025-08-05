<?php
// Inicia sesión y verifica que el usuario sea un ADMIN
include('../includes/auth.php');
verificarAdmin();  // Solo administradores pueden entrar aquí

// Conexión a la base de datos
include('../includes/conexion.php');
$con = conexion();

// Verifica que la conexión se haya establecido correctamente
if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta para obtener todos los usuarios registrados
$sql = "SELECT * FROM usuario";
$consulta = mysqli_query($con, $sql);

// Incluye el encabezado con el menú y sesión iniciada
include('../includes/header.php');
?>

<!-- Formulario para registrar un nuevo usuario -->
<div class="form_registro">
    <h2 style="text-align:center;">Registro de Usuarios</h2>
    <form action="crear_usuario.php" method="POST" class="p-4 bg-light border rounded shadow-sm w-50 mx-auto">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" name="apellido" id="apellido" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" name="correo" id="correo" required>
        </div>
        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="contrasena" id="contrasena" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>

<!-- Tabla con lista de usuarios existentes -->
<h2 style="text-align:center;">Lista de Usuarios</h2>
<div>
    <table class="table table-striped table-bordered table-hover mt-4 w-75 mx-auto">
        <thead class="table-dark">
            <tr>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
                <th>Clave</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($consulta && mysqli_num_rows($consulta) > 0): ?>
                <?php while ($datos = mysqli_fetch_array($consulta)): ?>
                    <tr>
                        <td><?= $datos['id_usuario']; ?></td>
                        <td><?= $datos['nombre']; ?></td>
                        <td><?= $datos['apellido']; ?></td>
                        <td><?= $datos['correo']; ?></td>
                        <td><?= $datos['contrasena']; ?></td>
                        <td>
                            <a href="eliminar_usuario.php?id=<?= $datos['id_usuario']; ?>"
                               onclick="return confirm('¿Estás segura/o que deseas eliminar este usuario?');">Eliminar</a> |
                            <a href="editar_usuario.php?id=<?= $datos['id_usuario']; ?>">Editar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay usuarios registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pie de página -->
<?php include('../includes/footer.php'); ?>
