<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['rol'])) {
    header("Location: /supermarketConexion/login/login.php");
    exit();
}

if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) === 0) {
    $_SESSION['mensaje_carrito_vacio'] = "No puedes finalizar una compra con el carrito vacío.";
    header("Location: carrito.php");
    exit();
}

include '../includes/conexion.php';
$con = conexion();

$id_usuario = $_SESSION['id_usuario'];

// Obtener el id_cliente desde la tabla cliente
$sql_cliente = "SELECT id_cliente FROM cliente WHERE id_usuario = $id_usuario";
$res_cliente = mysqli_query($con, $sql_cliente);

if (!$res_cliente || mysqli_num_rows($res_cliente) == 0) {
    die("No se encontró un cliente asociado a este usuario.");
}

$id_cliente = mysqli_fetch_assoc($res_cliente)['id_cliente'];

// Calcular el total general de la compra
$total_general = 0;
foreach ($_SESSION['carrito'] as $producto) {
    $subtotal = $producto['precio'] * $producto['cantidad'];
    $total_general += $subtotal;
}

// Insertar la compra
$fecha = date('Y-m-d');
$sql_compra = "INSERT INTO compra (id_cliente, fecha, total) VALUES ($id_cliente, '$fecha', $total_general)";
if (!mysqli_query($con, $sql_compra)) {
    die("Error al registrar la compra: " . mysqli_error($con));
}

$id_compra = mysqli_insert_id($con); // id de la compra registrada

// Insertar productos comprados en tabla compra_producto
foreach ($_SESSION['carrito'] as $producto) {
    $id_producto = $producto['id'];
    $cantidad = $producto['cantidad'];
    $precio_unitario = $producto['precio'];

    $sql_detalle = "INSERT INTO compra_producto (id_compra, id_producto, cantidad, precio_unitario)
                    VALUES ($id_compra, $id_producto, $cantidad, $precio_unitario)";
    mysqli_query($con, $sql_detalle);
}

// Vaciar el carrito y redirigir
unset($_SESSION['carrito']);
$_SESSION['mensaje_exito'] = "Compra realizada exitosamente. ¡Gracias por tu compra!";
header("Location: carrito.php");
exit();
