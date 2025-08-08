<?php
// C:\xampp\htdocs\supermarketConexion\carrito\agregar_al_carrito.php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_role('cliente');

require_once __DIR__ . '/../includes/conexion.php';
$con = conexion();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  $_SESSION['flash_error'] = "Producto inválido.";
  header("Location: ../usuarios/cliente/index_cliente.php");
  exit();
}

// Traer datos del producto (nombre, precio, stock)
$stmt = $con->prepare("SELECT id_producto, nombre, precio, stock FROM producto WHERE id_producto = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();

if (!$p) {
  $_SESSION['flash_error'] = "El producto no existe.";
  header("Location: ../usuarios/cliente/index_cliente.php");
  exit();
}

// Inicializar carrito
if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

// Sumar cantidad (con control de stock opcional)
if (isset($_SESSION['carrito'][$id])) {
  // No exceder stock disponible (opcional)
  if ($_SESSION['carrito'][$id]['cantidad'] < (int)$p['stock']) {
    $_SESSION['carrito'][$id]['cantidad'] += 1;
  }
} else {
  $_SESSION['carrito'][$id] = [
    'id'       => (int)$p['id_producto'],
    'nombre'   => $p['nombre'],
    'precio'   => (float)$p['precio'],
    'cantidad' => 1
  ];
}

$_SESSION['flash_success'] = "Producto agregado al carrito.";
header("Location: ../usuarios/cliente/index_cliente.php");
exit();
