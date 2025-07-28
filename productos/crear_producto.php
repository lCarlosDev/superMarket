<?php
include '../includes/conexion.php';
include '../includes/header.php'; // encabezado con navegación
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="../includes/estilos.css"> <!-- hoja de estilos -->
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

<?php include '../includes/footer.php'; ?> <!-- pie de página -->
</body>
</html>
