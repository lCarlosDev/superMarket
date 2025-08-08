<?php
// carrito/ver_carrito.php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_role('cliente');

/* ── Acciones del carrito (POST) ───────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id  = (int)($_POST['id'] ?? 0);
    $act = $_POST['action'] ?? '';

    if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if ($id > 0 && isset($_SESSION['carrito'][$id])) {
        if ($act === 'inc') {
            $_SESSION['carrito'][$id]['cantidad'] += 1;
        } elseif ($act === 'dec') {
            $_SESSION['carrito'][$id]['cantidad'] -= 1;
            if ($_SESSION['carrito'][$id]['cantidad'] <= 0) {
                unset($_SESSION['carrito'][$id]);
            }
        } elseif ($act === 'del') {
            unset($_SESSION['carrito'][$id]);
        }
    }

    header('Location: ver_carrito.php');
    exit;
}

/* ── Datos para mostrar ────────────────────────────────────── */
$BASE    = '/supermarketConexion';
$carrito = $_SESSION['carrito'] ?? [];
$items   = array_values($carrito);
$subtotal = 0.0;
foreach ($items as $it) {
    $subtotal += ((float)($it['precio'] ?? 0)) * ((int)($it['cantidad'] ?? 0));
}

/* Header (abre <html> y <body>) */
require_once __DIR__ . '/../includes/header.php';
?>

<style>
    /* Encabezado de tabla con color de marca */
.thead-brand th{
  background: var(--brand-green);
  color: #fff;
  border-color: var(--brand-green-dark);
}

  :root{
    /* Cambia esta imagen cuando quieras */
    --bg-url: url('../img/cart-bg.jpg');
  }
  body{
    background:
      linear-gradient(rgba(255,255,255,.92), rgba(255,255,255,.92)),
      var(--bg-url) center/cover no-repeat fixed;
  }

  .cart-title{
    display:flex; align-items:center; gap:.6rem;
    margin: 1rem 0 1rem;
  }
  .cart-title i{ font-size: 1.6rem; color: var(--brand-green); }

  /* Botones de cantidad */
  .qty-actions .btn{
    width: 36px; height: 36px; padding: 0;
    display:flex; align-items:center; justify-content:center;
  }
  .qty-number{
    min-width: 42px;
    text-align:center;
    font-weight: 600;
  }
</style>

<div class="container py-3">
  <div class="cart-title">
    <i class="bi bi-cart3"></i>
    <h1 class="h4 m-0">Carrito de Compras</h1>
  </div>

  <?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
  <?php endif; ?>

  <?php if (empty($items)): ?>
    <div class="alert alert-warning">Tu carrito está vacío.</div>
    <a href="<?= $BASE ?>/usuarios/cliente/index_cliente.php" class="btn btn-outline-brand">
      <i class="bi bi-arrow-left"></i> Seguir comprando
    </a>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="thead-brand">
          <tr>
            <th>Producto</th>
            <th class="text-end">Precio Unitario</th>
            <th class="text-center" style="width:200px">Cantidad</th>
            <th class="text-end">Total</th>
            <th style="width:70px"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it):
            $id     = (int)($it['id'] ?? 0);
            $nombre = $it['nombre'] ?? 'Producto';
            $precio = (float)($it['precio'] ?? 0);
            $cant   = (int)($it['cantidad'] ?? 0);
            $total  = $precio * $cant;
          ?>
            <tr>
              <td><?= htmlspecialchars($nombre) ?></td>
              <td class="text-end">$<?= number_format($precio, 0, ',', '.') ?></td>
              <td class="text-center">
                <div class="d-inline-flex align-items-center gap-2 qty-actions">
                  <!-- − -->
                  <form method="post" class="d-inline">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="hidden" name="action" value="dec">
                    <button class="btn btn-outline-brand" type="submit" title="Restar">
                      <i class="bi bi-dash-lg"></i>
                    </button>
                  </form>

                  <span class="qty-number"><?= $cant ?></span>

                  <!-- + -->
                  <form method="post" class="d-inline">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="hidden" name="action" value="inc">
                    <button class="btn btn-brand" type="submit" title="Sumar">
                      <i class="bi bi-plus-lg"></i>
                    </button>
                  </form>
                </div>
              </td>
              <td class="text-end">$<?= number_format($total, 0, ',', '.') ?></td>
              <td class="text-end">
                <!-- Eliminar -->
                <form method="post">
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <input type="hidden" name="action" value="del">
                  <button class="btn btn-sm btn-outline-danger" type="submit" title="Eliminar">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" class="text-end">Subtotal</th>
            <th class="text-end">$<?= number_format($subtotal, 0, ',', '.') ?></th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="d-flex gap-2">
      <a href="<?= $BASE ?>/usuarios/cliente/index_cliente.php" class="btn btn-outline-brand">
        <i class="bi bi-arrow-left"></i> Seguir comprando
      </a>
      <button class="btn btn-brand" disabled>
        <i class="bi bi-credit-card"></i> Pagar (próximamente)
      </button>
    </div>
  <?php endif; ?>
</div>

<?php
// (Opcional) cierra <body> y </html> si tienes un footer.
if (file_exists(__DIR__ . '/../includes/footer.php')) {
    include __DIR__ . '/../includes/footer.php';
}
?>