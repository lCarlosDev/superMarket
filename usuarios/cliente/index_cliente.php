<?php
// usuarios/cliente/index_cliente.php
require __DIR__ . '/../../includes/auth.php';
require_login();
require_role('cliente');

require __DIR__ . '/../../includes/conexion.php';
$con = conexion();

// Traer productos disponibles (stock > 0)
$stmt = $con->prepare("
  SELECT id_producto, nombre, descripcion, precio, stock, imagen
  FROM producto
  WHERE stock > 0
  ORDER BY nombre
");

$stmt->execute();
$productos = $stmt->get_result();

// Base del proyecto para armar URLs (ajusta si tu carpeta cambia)
$BASE = '/supermarketConexion';
?>

<?php require __DIR__ . '/../../includes/header.php'; ?>

<style>
  .catalog-hero{
    padding: 1.5rem 0 0.5rem;
    background: linear-gradient(135deg, var(--brand-green), var(--brand-green-dark));
    color:#fff;
  }
  .product-card{
    border:1px solid #eee; border-radius:16px; overflow:hidden;
    transition: transform .08s ease, box-shadow .2s ease;
    background:#fff;
  }
  .product-card:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(0,0,0,.08);
  }
  .product-thumb{
    height: 160px; background: linear-gradient(135deg, #f6f8f7, #e9f5ec);
    display:flex; align-items:center; justify-content:center;
  }
  .product-thumb i{ font-size:42px; color:#9ab9a2; }
  .price{ font-weight:700; color: var(--brand-green); }
  .stock{ font-size:.9rem; color:#6c757d; }
  .search-wrap{ margin-top: -26px; }
</style>

<section class="catalog-hero">
  <div class="container">
    <h1 class="h3 mb-2"><i class="bi bi-basket2-fill me-2"></i>Catálogo de productos</h1>
    <p class="mb-3" style="opacity:.9">Explora y añade artículos a tu carrito.</p>
  </div>
</section>

<div class="container search-wrap">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8">
      <form class="input-group mb-4" method="get">
        <input type="text" class="form-control" name="q" placeholder="Buscar por nombre o descripción…" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button class="btn btn-outline-brand" type="submit"><i class="bi bi-search"></i> Buscar</button>
      </form>
    </div>
  </div>
</div>

<div class="container pb-5">
  <div class="row g-4">
    <?php
    // Si viene búsqueda, filtramos en memoria (rápido y simple). Si prefieres SQL, te lo cambio.
    $q = trim($_GET['q'] ?? '');
    $hayProductos = false;

    while ($p = $productos->fetch_assoc()):
      if ($q !== '') {
        $needle = mb_strtolower($q);
        $hay = str_contains(mb_strtolower($p['nombre']), $needle) || str_contains(mb_strtolower($p['descripcion'] ?? ''), $needle);
        if (!$hay) continue;
      }
      $hayProductos = true;
    ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="product-card h-100 d-flex flex-column">
          <div class="product-thumb">
  <?php
    // Mostrar imagen si existe, si no usar placeholder
    $imgNombre = $p['imagen'] ?? '';
    $imgPath   = __DIR__ . '/../../img/' . $imgNombre;              // ruta en disco
    $imgUrl    = $BASE . '/img/' . rawurlencode($imgNombre);        // url pública
    $hasImg    = $imgNombre !== '' && file_exists($imgPath);
  ?>

  <?php if ($hasImg): ?>
    <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($p['nombre']) ?>"
         style="max-height:100%; max-width:100%; object-fit:contain;">
  <?php else: ?>
    <!-- placeholder si no hay imagen -->
    <img src="<?= $BASE ?>/img/placeholder.png" alt="Sin imagen"
         style="max-height:100%; max-width:100%; object-fit:contain; opacity:.9;">
  <?php endif; ?>
</div>

          <div class="p-3 d-flex flex-column gap-1 flex-grow-1">
            <h2 class="h6 m-0"><?= htmlspecialchars($p['nombre']) ?></h2>
            <?php if (!empty($p['descripcion'])): ?>
              <div class="text-muted" style="font-size:.92rem;"><?= htmlspecialchars($p['descripcion']) ?></div>
            <?php endif; ?>
            <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
              <span class="price">$<?= number_format((float)$p['precio'], 0, ',', '.') ?></span>
              <span class="stock">Stock: <?= (int)$p['stock'] ?></span>
            </div>
          </div>
          <div class="p-3 pt-0">
            <a class="btn btn-brand w-100"
   href="<?= $BASE ?>/carrito/agregar_al_carrito.php?id=<?= (int)$p['id_producto'] ?>">
  <i class="bi bi-cart-plus"></i> Agregar al carrito
</a>

          </div>
        </div>
      </div>
    <?php endwhile; ?>

    <?php if (!$hayProductos): ?>
      <div class="col-12">
        <div class="alert alert-warning text-center">
          No encontramos productos disponibles<?= $q ? " para “".htmlspecialchars($q)."”" : "" ?>.
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
