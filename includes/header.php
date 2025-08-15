<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// OJO: auth.php debe sólo definir funciones (no redirigir por sí mismo)
require_once __DIR__ . '/auth.php';

// Ruta base de tu proyecto
$BASE = '/supermarketConexion';

// Datos de sesión
$u = [
  'id'     => $_SESSION['id_usuario'] ?? null,
  'rol'    => $_SESSION['rol'] ?? null,   // 'admin' | 'cliente' | null
  'nombre' => $_SESSION['nombre'] ?? '',
];

// Contador del carrito (si aplica)
$cartCount = 0;
if (!empty($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
  foreach ($_SESSION['carrito'] as $it) {
    $cartCount += isset($it['cantidad']) ? (int)$it['cantidad'] : 1;
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>SuperMarket</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= $BASE ?>/includes/estilos.css?v=6" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
    <?php
      // Logo: si es admin, a su panel; de lo contrario, al index público
      $homeHref = ($u['rol'] === 'admin')
        ? "$BASE/usuarios/administrador/index_admin.php"
        : "$BASE/index.php";
    ?>
    <a class="navbar-brand fw-bold text-brand" href="<?= $homeHref ?>">
      <i class="bi bi-basket2-fill me-1"></i> SuperMarket
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Menú">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menuPrincipal">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($u['id'] && $u['rol'] === 'admin'): ?>
          <!-- Menú para ADMIN -->
          <li class="nav-item">
            <a class="nav-link" href="<?= $BASE ?>/usuarios/administrador/index_admin.php">
              <i class="bi bi-shield-lock-fill me-1"></i> Administradores
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $BASE ?>/productos/index_producto.php">
              <i class="bi bi-box-seam me-1"></i> Inventario
            </a>
          </li>
          <!-- NUEVO: Solicitudes de admin -->
          <li class="nav-item">
            <a class="nav-link" href="<?= $BASE ?>/usuarios/administrador/solicitudes_admin.php">
              <i class="bi bi-person-badge me-1"></i> Solicitudes
            </a>
          </li>

        <?php elseif ($u['id'] && $u['rol'] === 'cliente'): ?>
          <!-- Menú para CLIENTE -->
          <li class="nav-item">
            <a class="nav-link text-dark fw-semibold" href="<?= $BASE ?>/carrito/ver_carrito.php">
              <i class="bi bi-cart3 me-1"></i> Carrito
              <?php if ($cartCount > 0): ?>
                <span class="badge badge-cart ms-1"><?= $cartCount ?></span>
              <?php endif; ?>
            </a>
          </li>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center gap-3">
        <?php if ($u['id']): ?>
          <span class="small text-muted">
            👋 Bienvenido, <strong><?= htmlspecialchars($u['nombre']) ?></strong>
            <?php if ($u['rol']): ?>
              <span class="badge badge-role ms-1 text-uppercase"><?= htmlspecialchars($u['rol']) ?></span>
            <?php endif; ?>
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
