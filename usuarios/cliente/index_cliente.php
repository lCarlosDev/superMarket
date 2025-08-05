<?php
include('../../includes/conexion.php'); // Subimos 2 niveles para encontrar el archivo
include('../../includes/header.php');   // Encabezado con menú

$con = conexion(); // Ejecutamos la función que conecta con la BD
?>

<?php
$sql = "SELECT cliente.id_cliente, usuario.nombre, usuario.apellido, cliente.direccion, cliente.telefono 
        FROM cliente 
        INNER JOIN usuario ON cliente.id_usuario = usuario.id_usuario";

$resultado = mysqli_query($con, $sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Listado de Clientes</h2>
    <a href="crear_cliente.php" class="btn btn-success mb-3">➕ Agregar nuevo cliente</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cliente = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?= $cliente['id_cliente'] ?></td>
                <td><?= $cliente['nombre'] ?></td>
                <td><?= $cliente['apellido'] ?></td>
                <td><?= $cliente['direccion'] ?></td>
                <td><?= $cliente['telefono'] ?></td>
                <td>
                    <a href="editar_cliente.php?id=<?= $cliente['id_cliente'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                    <a href="eliminar_cliente.php?id=<?= $cliente['id_cliente'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Deseas eliminar este cliente?')">🗑️ Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../../includes/footer.php'); ?>
