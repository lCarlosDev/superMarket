<?php
require_once __DIR__ . '/../includes/auth.php';
require_login(); require_role('admin');

require_once __DIR__ . '/../includes/conexion.php';
$con = conexion();

$BASE = '/supermarketConexion';

// búsqueda simple
$q = trim($_GET['q'] ?? '');
$sql = "SELECT id_producto, nombre, descripcion, imagen, precio, stock, categoria
        FROM producto ";
$params = [];
if ($q !== '') {
  $sql .= "WHERE nombre LIKE ? OR descripcion LIKE ? OR categoria LIKE ? ";
  $like = "%{$q}%";
  $params = [$like, $like, $like];
}
$sql .= "ORDER BY id_producto DESC";

$stmt = $con->prepare($sql);
if ($params) { $stmt->bind_param("sss", ...$params); }
$stmt->execute();
$productos = $stmt->get_result();

require_once __DIR__ . '/../includes/header.php';
?>
<style>
  .inv-hero{
    background: linear-gradient(135deg, var(--brand-green), var(--brand-green-dark));
    color:#fff; padding: 1.25rem 0;
  }
  .thumb {
    width: 56px; height: 56px; object-fit: cover; border-radius: 8px;
    background:#f6f6f6; border:1px solid #eee;
  }
  .table td, .table th { vertical-align: middle; }
  .toolbar { gap:.5rem; }
</style>

<section class="inv-hero">
  <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h1 class="h4 m-0"><i class="bi bi-box-seam me-2"></i>Inventario y productos</h1>
      <small class="opacity-75">Crea, edita y ajusta stock.</small>
    </div>
    <div class="d-flex toolbar">
      <a href="<?= $BASE ?>/usuarios/administrador/index_admin.php" class="btn btn-outline-light">
        <i class="bi bi-shield-lock"></i> Administradores
      </a>
      <a href="<?= $BASE ?>/productos/crear_producto.php" class="btn btn-light">
        <i class="bi bi-plus-circle"></i> Crear producto
      </a>
    </div>
  </div>
</section>

<div class="container py-4">

  <!-- mensajes -->
  <?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
  <?php endif; ?>

  <!-- buscador -->
  <form class="row g-2 mb-3" method="get">
    <div class="col-12 col-md-6">
      <input type="text" class="form-control" name="q" placeholder="Buscar por nombre, descripción o categoría…" value="<?= htmlspecialchars($q) ?>">
    </div>
    <div class="col-auto">
      <button class="btn btn-brand" type="submit"><i class="bi bi-search"></i> Buscar</button>
    </div>
    <?php if ($q !== ''): ?>
    <div class="col-auto">
      <a class="btn btn-outline-brand" href="<?= $BASE ?>/productos/index_producto.php"><i class="bi bi-x-circle"></i> Limpiar</a>
    </div>
    <?php endif; ?>
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead class="thead-brand">
        <tr>
          <th style="width:72px">Img</th>
          <th>Producto</th>
          <th>Categoría</th>
          <th class="text-end">Precio</th>
          <th class="text-center" style="width:210px">Stock</th>
          <th style="width:220px" class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($p = $productos->fetch_assoc()): ?>
        <tr>
          <td>
            <?php if (!empty($p['imagen'])): ?>
              <img src="<?= $BASE ?>/img/<?= htmlspecialchars($p['imagen']) ?>" alt="" class="thumb">
            <?php else: ?>
              <img src="<?= $BASE ?>/img/placeholder.png" alt="" class="thumb">
            <?php endif; ?>
          </td>
          <td>
            <strong><?= htmlspecialchars($p['nombre']) ?></strong><br>
            <small class="text-muted"><?= htmlspecialchars($p['descripcion']) ?></small>
          </td>
          <td><?= htmlspecialchars($p['categoria']) ?></td>
          <td class="text-end">$<?= number_format((float)$p['precio'], 0, ',', '.') ?></td>

          <!-- Ajuste de stock inline -->
          <td class="text-center">
            <div class="d-inline-flex align-items-center gap-2">
              <form method="post" action="actualizar_stock.php" class="d-inline">
                <input type="hidden" name="id" value="<?= (int)$p['id_producto'] ?>">
                <input type="hidden" name="action" value="dec">
                <button class="btn btn-sm btn-outline-brand" title="Restar" type="submit">
                  <i class="bi bi-dash-lg"></i>
                </button>
              </form>

              <form method="post" action="actualizar_stock.php" class="d-inline-flex align-items-center gap-2">
                <input type="hidden" name="id" value="<?= (int)$p['id_producto'] ?>">
                <input type="hidden" name="action" value="set">
                <input type="number" min="0" class="form-control form-control-sm" name="stock" value="<?= (int)$p['stock'] ?>" style="width:90px">
                <button class="btn btn-sm btn-brand" type="submit"><i class="bi bi-check2"></i></button>
              </form>

              <form method="post" action="actualizar_stock.php" class="d-inline">
                <input type="hidden" name="id" value="<?= (int)$p['id_producto'] ?>">
                <input type="hidden" name="action" value="inc">
                <button class="btn btn-sm btn-brand" title="Sumar" type="submit">
                  <i class="bi bi-plus-lg"></i>
                </button>
              </form>
            </div>
            <div class="small text-muted mt-1">Actual: <strong><?= (int)$p['stock'] ?></strong></div>
          </td>

          <td class="text-end">
            <a class="btn btn-sm btn-outline-brand" href="<?= $BASE ?>/productos/editar_producto.php?id=<?= (int)$p['id_producto'] ?>">
              <i class="bi bi-pencil"></i> Editar
            </a>
            <a class="btn btn-sm btn-outline-danger"
               href="<?= $BASE ?>/productos/eliminar_producto.php?id=<?= (int)$p['id_producto'] ?>"
               onclick="return confirm('¿Eliminar este producto?');">
               <i class="bi bi-trash"></i> Eliminar
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if (file_exists(__DIR__ . '/../includes/footer.php')) include __DIR__ . '/../includes/footer.php'; ?>
