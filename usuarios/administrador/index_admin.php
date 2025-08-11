<?php
// usuarios/administrador/index_admin.php

// Autenticación y rol
require __DIR__ . '/../../includes/auth.php';
require_login();
require_role('admin');

// Conexión + header común
require __DIR__ . '/../../includes/conexion.php';
$con   = conexion();
$BASE  = '/supermarketConexion';

require __DIR__ . '/../../includes/header.php';

// Datos para la vista
// (1) Lista de administradores
$sqlAdmins = "
  SELECT a.id_admin, a.cargo, u.nombre, u.apellido
  FROM admin a
  INNER JOIN usuario u ON u.id_usuario = a.id_usuario
  ORDER BY u.nombre, u.apellido
";
$admins = mysqli_query($con, $sqlAdmins);

// (2) Usuarios para el select (si quieres excluir los que ya son admin, puedes filtrar aquí)
$sqlUsuarios = "SELECT id_usuario, nombre, apellido FROM usuario ORDER BY nombre, apellido";
$usuarios = mysqli_query($con, $sqlUsuarios);
?>

<!-- Encabezado del panel -->
<section class="page-head">
  <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2">
    <h1 class="h4 m-0 d-flex align-items-center gap-2">
      <i class="bi bi-gear-wide-connected"></i> Panel de administración
    </h1>
    <div class="d-flex gap-2">
      <a href="<?= $BASE ?>/productos/index_producto.php" class="btn btn-outline-light">
        <i class="bi bi-box-seam"></i> Inventario
      </a>
      <a href="<?= $BASE ?>/productos/crear_producto.php" class="btn btn-light text-success">
        <i class="bi bi-plus-circle"></i> Crear producto
      </a>
    </div>
  </div>
</section>

<div class="container py-4">

  <!-- Flash messages (opcional) -->
  <?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <!-- Tarjeta: Registrar administrador -->
    <div class="col-12 col-lg-5">
      <div class="card card-soft">
        <div class="card-header bg-white">
          <h2 class="h5 m-0 d-flex align-items-center gap-2">
            <i class="bi bi-person-plus"></i> Registrar administrador
          </h2>
        </div>
        <div class="card-body">
          <form action="crear_admin.php" method="POST" class="vstack gap-3">
            <div>
              <label for="id_usuario" class="form-label">Seleccionar usuario</label>
              <select name="id_usuario" id="id_usuario" class="form-select" required>
                <?php while ($u = mysqli_fetch_assoc($usuarios)): ?>
                  <option value="<?= (int)$u['id_usuario'] ?>">
                    <?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div>
              <label for="cargo" class="form-label">Cargo</label>
              <input type="text" name="cargo" id="cargo" class="form-control" placeholder="Ej: coordinador" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-brand">
                <i class="bi bi-check2"></i> Registrar administrador
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Tarjeta: Lista de administradores -->
    <div class="col-12 col-lg-7">
      <div class="card card-soft">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
          <h2 class="h5 m-0 d-flex align-items-center gap-2">
            <i class="bi bi-people"></i> Lista de administradores
          </h2>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="thead-brand">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cargo</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($admins && mysqli_num_rows($admins) > 0): ?>
                <?php while ($a = mysqli_fetch_assoc($admins)): ?>
                  <tr>
                    <td><?= (int)$a['id_admin'] ?></td>
                    <td><?= htmlspecialchars($a['nombre']) ?></td>
                    <td><?= htmlspecialchars($a['apellido']) ?></td>
                    <td>
                      <span class="badge bg-success-subtle text-success">
                        <?= htmlspecialchars($a['cargo']) ?>
                      </span>
                    </td>
                    <td class="text-end">
                      <a href="<?= $BASE ?>/usuarios/administrador/editar_admin.php?id=<?= (int)$a['id_admin'] ?>"
                        class="btn btn-sm btn-outline-brand">
                        <i class="bi bi-pencil"></i> Editar
                        </a>

                        <a href="<?= $BASE ?>/usuarios/administrador/eliminar_admin.php?id=<?= (int)$a['id_admin'] ?>"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('¿Eliminar administrador?');">
                        <i class="bi bi-trash"></i> Eliminar
                     </a>

                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">No hay administradores registrados.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<?php
// Footer común
if (file_exists(__DIR__ . '/../../includes/footer.php')) {
  include __DIR__ . '/../../includes/footer.php';
}
