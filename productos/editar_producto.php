<?php
include '../includes/conexion.php'; // Nueva ruta correcta
include '../includes/header.php';   // Encabezado con menú de navegación

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
        include '../includes/footer.php';
        exit();
    }
} else {
    echo "ID de producto no proporcionado.";
    include '../includes/footer.php';
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
        header("Location: index_productos.php");
        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>

<h2 style="text-align:center;">Editar Producto</h2>

<form method="POST" action="" class="p-4 bg-light border rounded shadow-sm w-50 mx-auto">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="<?= $producto['nombre'] ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control" required><?= $producto['descripcion'] ?></textarea>
    </div>

    <div class="mb-3">
        <label for="precio" class="form-label">Precio</label>
        <input type="number" name="precio" id="precio" step="0.01" value="<?= $producto['precio'] ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" name="stock" id="stock" value="<?= $producto['stock'] ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="categoria" class="form-label">Categoría</label>
        <input type="text" name="categoria" id="categoria" value="<?= $producto['categoria'] ?>" class="form-control" required>
    </div>

    <input type="submit" value="Actualizar" class="btn btn-primary">
    <a href="index_productos.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include '../includes/footer.php'; ?>
