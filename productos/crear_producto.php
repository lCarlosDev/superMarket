<?php
include '../conexion.php'; // Subimos un nivel para encontrar conexion.php

// Verificamos si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conexion(); // Conectamos a la base de datos

    // Obtenemos los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];

    // Insertamos el producto en la base de datos
    $sql = "INSERT INTO producto (nombre, descripcion, precio, stock, categoria)
            VALUES ('$nombre', '$descripcion', $precio, $stock, '$categoria')";

    if ($conn->query($sql) === TRUE) {
        // Redireccionamos al index si fue exitoso
        header("Location: index_productos.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
</head>
<body>
    <h2>Agregar Nuevo Producto</h2>
    <form action="" method="POST">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion" required></textarea><br><br>

        <label>Precio:</label><br>
        <input type="number" name="precio" step="0.01" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" required><br><br>

        <label>Categoría:</label><br>
        <input type="text" name="categoria" required><br><br>

        <input type="submit" value="Guardar">
        <a href="index_productos.php">Cancelar</a>
    </form>
</body>
</html>
