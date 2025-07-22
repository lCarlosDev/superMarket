<?php
include '../conexion.php'; // Conectamos con la base de datos
$conn = conexion();

// Verificamos si hay un ID en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obtener los datos del producto
    $sql = "SELECT * FROM producto WHERE id_producto = $id";
    $resultado = $conn->query($sql);

    // Validar que el producto existe
    if ($resultado->num_rows == 1) {
        $producto = $resultado->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "ID de producto no proporcionado.";
    exit();
}

// Si se envió el formulario (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];

    // Actualizamos el producto
    $sql = "UPDATE producto 
            SET nombre = '$nombre', descripcion = '$descripcion', precio = $precio, stock = $stock, categoria = '$categoria'
            WHERE id_producto = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index_productos.php"); // Redirige si fue exitoso
        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h2>Editar Producto</h2>
    <form method="POST" action="">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion" required><?= $producto['descripcion'] ?></textarea><br><br>

        <label>Precio:</label><br>
        <input type="number" name="precio" step="0.01" value="<?= $producto['precio'] ?>" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" value="<?= $producto['stock'] ?>" required><br><br>

        <label>Categoría:</label><br>
        <input type="text" name="categoria" value="<?= $producto['categoria'] ?>" required><br><br>

        <input type="submit" value="Actualizar">
        <a href="index_productos.php">Cancelar</a>
    </form>
</body>
</html>
