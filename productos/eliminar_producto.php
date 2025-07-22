<?php
include '../conexion.php';
$conn = conexion();

// Verificamos si recibimos un ID por la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminamos el producto con ese ID
    $sql = "DELETE FROM producto WHERE id_producto = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirigimos al listado después de eliminar
        header("Location: index_productos.php");
        exit();
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }
} else {
    echo "ID de producto no proporcionado.";
}
