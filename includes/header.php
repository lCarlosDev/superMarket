<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/auth.php';   // ← require_once evita la doble carga
$u = current_user();
$BASE = '/supermarketConexion';
$cartCount = 0;
if (!empty($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
  foreach ($_SESSION['carrito'] as $it) { $cartCount += isset($it['cantidad']) ? (int)$it['cantidad'] : 1; }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>SuperMarket</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="/supermarketConexion/includes/estilos.css?v=5" rel="stylesheet">



</head>

<body>
<nav class="navbar navbar-expand-lg bg-light shadow-sm">
  <div class="container">
    <!-- Logo + Marca (verde) -->
    <a class="navbar-brand fw-bold text-brand" href="<?= $BASE ?>/index.php">
      <i class="bi bi-basket2-fill me-1"></i> SuperMarket
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menuPrincipal">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($u['id'] && $u['rol'] === 'cliente'): ?>
          <!-- Solo mostrar Carrito para cliente (negro) -->
          <li class="nav-item">
            <a class="nav-link text-dark fw-semibold" href="<?= $BASE ?>/carrito/ver_carrito.php">
              <i class="bi bi-cart3 me-1"></i> Carrito
              <?php if ($cartCount > 0): ?>
                <span class="badge bg-dark ms-1"><?= $cartCount ?></span>
              <?php endif; ?>
            </a>
          </li>
        <?php elseif ($u['id'] && $u['rol'] === 'admin'): ?>
          <!-- Admin sí ve módulos -->
          <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>/usuarios/index_usuario.php">👤 Usuarios</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $BASE ?>/productos/index_producto.php">🛒 Productos</a></li>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center gap-3">
        <?php if ($u['id']): ?>
          <span class="small text-muted">
            👋 Bienvenido, <strong><?= htmlspecialchars($u['nombre'] ?? 'Usuario') ?></strong>
            <?php if ($u['rol']): ?><span class="badge badge-role ms-1 text-uppercase"><?= $u['rol'] ?></span><?php endif; ?>
          </span>
          <a class="btn btn-sm btn-danger" href="<?= $BASE ?>/login/logout.php">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
          </a>
        <?php else: ?>
          <a class="btn btn-sm btn-brand" href="<?= $BASE ?>/login/login.php">Iniciar sesión</a>
          <a class="btn btn-sm btn-outline-brand" href="<?= $BASE ?>/login/registro.php">Registrarse</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
