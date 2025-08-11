<?php
// productos/editar_producto.php
require __DIR__ . '/../includes/auth.php';
require_login(); require_role('admin');

require __DIR__ . '/../includes/conexion.php';
$con   = conexion();
$BASE  = '/supermarketConexion';
$err   = null;

// 1) Traer producto por id
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: {$BASE}/productos/index_producto.php"); exit(); }

$stmt = $con->prepare("SELECT * FROM producto WHERE id_producto = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
  $_SESSION['flash_error'] = "Producto no encontrado.";
  header("Location: {$BASE}/productos/index_producto.php");
  exit();
}

// 2) Guardar cambios (incluye imagen opcional)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre      = trim($_POST['nombre'] ?? '');
  $descripcion = trim($_POST['descripcion'] ?? '');
  $precio      = (float)($_POST['precio'] ?? 0);
  $stock       = (int)($_POST['stock'] ?? 0);
  $categoria   = trim($_POST['categoria'] ?? '');
  $imagenActual= $producto['imagen'] ?? null; // lo que hay en BD
  $imagenNueva = $imagenActual;               // por defecto conserva

  if ($nombre === '' || $descripcion === '' || $precio <= 0 || $stock < 0 || $categoria === '') {
    $err = "Por favor completa todos los campos con valores válidos.";
  }

  // Si suben una nueva imagen, la procesamos
  if (!$err && !empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imgDir = __DIR__ . '/../img';
    if (!is_dir($imgDir)) { mkdir($imgDir, 0777, true); }

    $tmp  = $_FILES['imagen']['tmp_name'];
    $orig = $_FILES['imagen']['name'];
    $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    $ok   = ['jpg','jpeg','png','webp','gif'];

    if (in_array($ext, $ok, true)) {
      $mime = mime_content_type($tmp);
      if (preg_match('~^image/(jpeg|png|webp|gif)$~', $mime)) {
        // nombre único seguro
        $safeBase  = preg_replace('/[^A-Za-z0-9._-]/', '_', pathinfo($orig, PATHINFO_FILENAME));
        $imagenNueva = 'prod_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
        $destino = $imgDir . '/' . $imagenNueva;

        if (move_uploaded_file($tmp, $destino)) {
          // (Opcional) borrar imagen previa si existía
          // if ($imagenActual && file_exists($imgDir . '/' . $imagenActual)) {
          //    unlink($imgDir . '/' . $imagenActual);
          // }
        } else {
          $err = "No se pudo guardar la imagen nueva.";
        }
      } else {
        $err = "Formato de imagen no válido (JPEG/PNG/WEBP/GIF).";
      }
    } else {
      $err = "Extensión no permitida. Usa JPG, PNG, WEBP o GIF.";
    }
  }

  if (!$err) {
    $sql = "UPDATE producto
            SET nombre=?, descripcion=?, imagen=?, precio=?, stock=?, categoria=?
            WHERE id_producto=?";
    $up = $con->prepare($sql);
    $up->bind_param("sssdisi", $nombre, $descripcion, $imagenNueva, $precio, $stock, $categoria, $id);
    $up->execute();

    $_SESSION['flash_success'] = "Producto actualizado.";
    header("Location: {$BASE}/productos/index_producto.php");
    exit();
  }
}

require __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
  <h2 class="mb-3 text-brand">
    <i class="bi bi-pencil-square me-2"></i>Editar producto
  </h2>

  <?php if (!empty($err)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre"
                   value="<?= htmlspecialchars($producto['nombre']) ?>" required>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Precio</label>
            <input type="number" class="form-control" name="precio" step="0.01" min="0"
                   value="<?= htmlspecialchars($producto['precio']) ?>" required>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" min="0"
                   value="<?= htmlspecialchars($producto['stock']) ?>" required>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Categoría</label>
            <input type="text" class="form-control" name="categoria"
                   value="<?= htmlspecialchars($producto['categoria']) ?>" required>
          </div>

          <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" rows="3" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Imagen actual</label>
            <div>
              <?php if (!empty($producto['imagen'])): ?>
                <img src="<?= $BASE ?>/img/<?= htmlspecialchars($producto['imagen']) ?>"
                     style="width:110px;height:110px;object-fit:cover;border-radius:10px;border:1px solid #eee;">
              <?php else: ?>
                <img src="<?= $BASE ?>/img/placeholder.png"
                     style="width:110px;height:110px;object-fit:cover;border-radius:10px;border:1px solid #eee;">
                <div class="small text-muted mt-1">Este producto no tiene imagen.</div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Nueva imagen (opcional)</label>
            <input type="file" class="form-control" name="imagen" accept="image/*">
            <div class="form-text">Se recomienda JPG/PNG/WEBP. Máximo 5–10&nbsp;MB.</div>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-brand"><i class="bi bi-check2"></i> Guardar cambios</button>
          <a href="<?= $BASE ?>/productos/index_producto.php" class="btn btn-outline-brand">Volver</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php if (file_exists(__DIR__ . '/../includes/footer.php')) include __DIR__ . '/../includes/footer.php'; ?>
