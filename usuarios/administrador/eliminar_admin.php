<?php
// usuarios/administrador/eliminar_admin.php
require __DIR__ . '/../../includes/auth.php';
require_login();
require_role('admin');

require __DIR__ . '/../../includes/conexion.php';
$con  = conexion();
$BASE = '/supermarketConexion';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
  $_SESSION['flash_error'] = 'ID inválido.';
  header("Location: {$BASE}/usuarios/administrador/index_admin.php");
  exit;
}

// (Opcional) Evita que un admin se elimine a sí mismo si lo deseas
// if ((int)$_SESSION['id_admin'] === $id) { ... }

$stmt = $con->prepare("DELETE FROM admin WHERE id_admin = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
  $_SESSION['flash_success'] = 'Administrador eliminado correctamente.';
} else {
  $_SESSION['flash_error'] = 'No se pudo eliminar (¿ya no existe?).';
}

header("Location: {$BASE}/usuarios/administrador/index_admin.php");
exit;
