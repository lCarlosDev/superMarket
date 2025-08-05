<?php
require('../includes/conexion.php');
$con = conexion();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM cliente WHERE id_cliente = $id";

    if (mysqli_query($con, $sql)) {
        header("Location: index_cliente.php");
        exit();
    } else {
        echo "Error al eliminar: " . mysqli_error($con);
    }
} else {
    echo "ID de cliente no proporcionado.";
}

mysqli_close($con);
?>
