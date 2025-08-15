<?php
// usuarios/administrador/solicitudes_admin.php
require_once __DIR__ . '/../../includes/auth.php';
require_login();
require_role('admin');

require_once __DIR__ . '/../../includes/conexion.php';
$con  = conexion();
$BASE = '/supermarketConexion';

/* ===========================
   1) Acciones (aprobar / rechazar)
   =========================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action      = $_POST['action'] ?? '';
  // admito id con ambos nombres por si venías del código anterior
  $idSolicitud = (int)($_POST['id_solicitud'] ?? ($_POST['id'] ?? 0));

  if ($idSolicitud > 0) {
    // Traemos la solicitud pendiente con nombres REALES de tu BD
    $stmt = $con->prepare("
      SELECT 
        s.id_solicitud, s.id_usuario, s.cargo, s.estado,
        u.nombre, u.apellido, u.correo
      FROM solicitudes_admin s
      JOIN usuario u ON u.id_usuario = s.id_usuario
      WHERE s.id_solicitud = ? AND s.estado = 'pendiente'
    ");
    $stmt->bind_param("i", $idSolicitud);
    $stmt->execute();
    $sol = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$sol) {
      $_SESSION['flash_error'] = "La solicitud no existe o ya fue atendida.";
      header("Location: {$BASE}/usuarios/administrador/solicitudes_admin.php");
      exit;
    }

    if ($action === 'aprobar') {
      $con->begin_transaction();
      try {
        // Evitar duplicar admin
        $stmt = $con->prepare("SELECT 1 FROM admin WHERE id_usuario = ?");
        $stmt->bind_param("i", $sol['id_usuario']);
        $stmt->execute();
        $yaEsAdmin = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if (!$yaEsAdmin) {
          $stmt = $con->prepare("INSERT INTO admin (id_usuario, cargo) VALUES (?, ?)");
          $stmt->bind_param("is", $sol['id_usuario'], $sol['cargo']);
          $stmt->execute();
          $stmt->close();
        }

        $stmt = $con->prepare("UPDATE solicitudes_admin SET estado = 'aprobada' WHERE id_solicitud = ?");
        $stmt->bind_param("i", $idSolicitud);
        $stmt->execute();
        $stmt->close();

        $con->commit();
        $_SESSION['flash_success'] = "Solicitud aprobada. {$sol['nombre']} {$sol['apellido']} ahora es administrador.";
      } catch (Throwable $e) {
        $con->rollback();
        $_SESSION['flash_error'] = "No se pudo aprobar: " . $e->getMessage();
      }

    } elseif ($action === 'rechazar') {
      $stmt = $con->prepare("UPDATE solicitudes_admin SET estado = 'rechazada' WHERE id_solicitud = ?");
      $stmt->bind_param("i", $idSolicitud);
      if ($stmt->execute()) {
        $_SESSION['flash_success'] = "Solicitud rechazada.";
      } else {
        $_SESSION['flash_error'] = "No se pudo rechazar: " . $stmt->error;
      }
      $stmt->close();
    }
  }

  header("Location: {$BASE}/usuarios/administrador/solicitudes_admin.php");
  exit;
}

/* ===========================
   2) Listado de pendientes (nombres REALES)
   =========================== */
$sql = "
  SELECT
    s.id_solicitud,
    s.cargo,
    s.fecha_solicitud,
    u.nombre,
    u.apellido,
    u.correo
  FROM solicitudes_admin s
  JOIN usuario u ON u.id_usuario = s.id_usuario
  WHERE s.estado = 'pendiente'
  ORDER BY s.fecha_solicitud DESC, s.id_solicitud DESC
";
$pendientes = $con->query($sql);

/* Header con navbar */
require_once __DIR__ . '/../../includes/header.php';

/* Flash (por si tu header no los muestra) */
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>

<style>
  .page-head{
    background: linear-gradient(135deg, var(--brand-green), var(--brand-green-dark));
    color:#fff; padding: 1rem 0;
    box-shadow: 0 6px 18px rgba(0,0,0,.08);
    margin-bottom: 1rem;
  }
</style>

<section class="page-head">
  <div class="container d-flex align-items-center justify-content-between">
    <h1 class="h4 m-0 d-flex align-items-center gap-2">
      <i class="bi bi-person-badge"></i> Solicitudes de administrador
    </h1>
    <div class="d-flex gap-2">
      <a href="<?= $BASE ?>/usuarios/administrador/index_admin.php" class="btn btn-light">
        <i class="bi bi-gear-wide-connected"></i> Panel
      </a>
      <a href="<?= $BASE ?>/productos/index_producto.php" class="btn btn-outline-light">
        <i class="bi bi-box-seam"></i> Inventario
      </a>
    </div>
  </div>
</section>

<div class="container pb-4">

  <?php if ($flash_success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($flash_success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  <?php endif; ?>

  <?php if ($flash_error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($flash_error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <h2 class="h5 mb-3"><i class="bi bi-inbox"></i> Pendientes</h2>

      <?php if ($pendientes && $pendientes->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead class="thead-brand">
              <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Cargo solicitado</th>
                <th>Fecha</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $pendientes->fetch_assoc()): ?>
                <tr>
                  <td><?= (int)$row['id_solicitud'] ?></td>
                  <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                  <td><?= htmlspecialchars($row['correo']) ?></td>
                  <td><span class="badge bg-success-subtle text-success"><?= htmlspecialchars($row['cargo'] ?: '—') ?></span></td>
                  <td><?= htmlspecialchars($row['fecha_solicitud']) ?></td>
                  <td class="text-end">
                    <form method="post" class="d-inline">
                      <input type="hidden" name="id_solicitud" value="<?= (int)$row['id_solicitud'] ?>">
                      <input type="hidden" name="action" value="aprobar">
                      <button class="btn btn-sm btn-success">
                        <i class="bi bi-check2"></i> Aprobar
                      </button>
                    </form>
                    <form method="post" class="d-inline" onsubmit="return confirm('¿Rechazar esta solicitud?')">
                      <input type="hidden" name="id_solicitud" value="<?= (int)$row['id_solicitud'] ?>">
                      <input type="hidden" name="action" value="rechazar">
                      <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-x-lg"></i> Rechazar
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info mb-0">
          No hay solicitudes pendientes por ahora.
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php
if (file_exists(__DIR__ . '/../../includes/footer.php')) {
  include __DIR__ . '/../../includes/footer.php';
}
