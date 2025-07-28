<?php 
include '../includes/conexion.php'; // Ruta corregida
$con = conexion();

// Consulta todos los productos
$sql = "SELECT * FROM producto";
$resultado = $con->query($sql);
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">Listado de Productos</h2>
    <a href="crear_producto.php" class="btn btn-success mb-3">➕ Agregar nuevo producto</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_producto'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['descripcion'] ?></td>
                <td>$<?= number_format($row['precio'], 2) ?></td>
                <td><?= $row['stock'] ?></td>
                <td><?= $row['categoria'] ?></td>
                <td>
                    <a href="editar_producto.php?id=<?= $row['id_producto'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                    <a href="eliminar_producto.php?id=<?= $row['id_producto'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">🗑️ Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
