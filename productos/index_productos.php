<?php
include '../conexion.php';
$con = conexion();

// Consulta todos los productos
$sql = "SELECT * FROM producto";
$resultado = $con->query($sql);
?>

<?php include '../header.php'; ?>

    <h2>Listado de Productos</h2>
    <a href="crear_producto.php">➕ Agregar nuevo producto</a>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Categoría</th>
            <th>Acciones</th>
        </tr>
        <?php while($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_producto'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['descripcion'] ?></td>
            <td>$<?= $row['precio'] ?></td>
            <td><?= $row['stock'] ?></td>
            <td><?= $row['categoria'] ?></td>
            <td>
                <a href="editar_producto.php?id=<?= $row['id_producto'] ?>">✏️ Editar</a> |
                <a href="eliminar_producto.php?id=<?= $row['id_producto'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php include '../footer.php'; ?>

</body>
</html>
