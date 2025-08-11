<?php
// productos/actualizar_stock.php
require_once __DIR__ . '/../includes/auth.php';
require_login(); require_role('admin');

require_once __DIR__ . '/../includes/conexion.php';
$con = conexion();

$id     = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';
$stock  = isset($_POST['stock']) ? (int)$_POST['stock'] : null;

if ($id <= 0) {
  $_SESSION['flash_error'] = "ID inválido.";
  header("Location: index_producto.php"); exit();
}

// obtiene stock actual
$stmt = $con->prepare("SELECT stock FROM producto WHERE id_producto = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
  $_SESSION['flash_error'] = "Producto no encontrado.";
  header("Location: index_producto.php"); exit();
}

$current = (int)$row['stock'];

if ($action === 'inc') {
  $new = $current + 1;
} elseif ($action === 'dec') {
  $new = max(0, $current - 1);
} elseif ($action === 'set' && $stock !== null && $stock >= 0) {
  $new = $stock;
} else {
  $_SESSION['flash_error'] = "Acción inválida.";
  header("Location: index_producto.php"); exit();
}

$up = $con->prepare("UPDATE producto SET stock = ? WHERE id_producto = ?");
$up->bind_param("ii", $new, $id);
$up->execute();

$_SESSION['flash_success'] = "Stock actualizado a {$new}.";
header("Location: index_producto.php");
exit();
