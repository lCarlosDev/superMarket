<?php
// productos/crear_producto.php
require __DIR__ . '/../includes/auth.php';
require_login();
require_role('admin');

require __DIR__ . '/../includes/conexion.php';
$con = conexion();

$BASE = '/supermarketConexion';
$err = null;

// POST: guardar producto + imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = trim($_POST['nombre'] ?? '');
    $descripcion= trim($_POST['descripcion'] ?? '');
    $precio     = (float)($_POST['precio'] ?? 0);
    $stock      = (int)($_POST['stock'] ?? 0);
    $categoria  = trim($_POST['categoria'] ?? '');
    $imagen     = null;

    // Validación básica
    if ($nombre === '' || $descripcion === '' || $precio <= 0 || $stock < 0 || $categoria === '') {
        $err = "Por favor completa todos los campos con valores válidos.";
    }

    // Subir imagen (opcional)
    if (!$err && !empty($_FILES['imagen']['name'])) {
        $uploadsDir = __DIR__ . '/../uploads';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $origName  = $_FILES['imagen']['name'];
        $ext       = pathinfo($origName, PATHINFO_EXTENSION);
        $baseName  = pathinfo($origName, PATHINFO_FILENAME);
        $safeName  = preg_replace('/[^A-Za-z0-9._-]/', '_', $baseName);
        $filename  = time() . '_' . $safeName . '.' . $ext;
        $destino   = $uploadsDir . '/' . $filename;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            $err = "No se pudo guardar la imagen. Intenta con otra.";
        } else {
            $imagen = $filename; // guardaremos solo el nombre en la BD
        }
    }

    // Insertar en BD
    if (!$err) {
        $stmt = $con->prepare("INSERT INTO producto (nombre, descripcion, precio, stock, categoria, imagen)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiss", $nombre, $descripcion, $precio, $stock, $categoria, $imagen);

        if ($stmt->execute()) {
            $_SESSION['flash_success'] = "Producto creado correctamente.";
            header("Location: {$BASE}/productos/index_producto.php");
            exit();
        } else {
            $err = "Error al guardar: " . $stmt->error;
        }
    }
}

// Header con navbar (ya incluye <html>, <head> y apertura de <body>)
require __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
  <h2 class="mb-3 text-brand"><i class="bi bi-box-seam me-2"></i>Agregar nuevo producto</h2>

  <?php if (!empty($err)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" required>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Precio</label>
            <input type="number" class="form-control" name="precio" step="0.01" min="0" required>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" min="0" required>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Categoría</label>
            <input type="text" class="form-control" name="categoria" required>
          </div>

          <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" rows="3" required></textarea>
          </div>

          <div class="col-12">
            <label class="form-label">Imagen del producto (opcional)</label>
            <input type="file" class="form-control" name="imagen" accept="image/*">
            <div class="form-text">Se recomienda JPG/PNG. Tamaño máximo ~2–5 MB.</div>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-brand"><i class="bi bi-check2"></i> Guardar</button>
          <a href="<?= $BASE ?>/productos/index_producto.php" class="btn btn-outline-brand">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
// Footer si lo usas (cierra el body y html si corresponde)
if (file_exists(__DIR__ . '/../includes/footer.php')) {
    include __DIR__ . '/../includes/footer.php';
}
