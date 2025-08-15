<?php require_once __DIR__.'/../includes/auth.php'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registrarse | SuperMarket</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root{ --brand-green:#28a745; --brand-green-dark:#1e7e34; --bg:url('../img/imgindex3.jpg'); }
    html,body{height:100%}
    body{background:linear-gradient(#ffffffeb,#ffffffeb),var(--bg) center/cover no-repeat fixed;display:flex;align-items:center;justify-content:center}
    .cardx{width:min(92%,560px);border:1px solid #eee;border-radius:18px;box-shadow:0 22px 60px rgba(0,0,0,.1);overflow:hidden}
    .head{background:linear-gradient(135deg,var(--brand-green),var(--brand-green-dark));color:#fff;text-align:center;padding:18px}
    .btn-brand{background:var(--brand-green);border-color:var(--brand-green)}
    .btn-brand:hover{background:var(--brand-green-dark);border-color:var(--brand-green-dark)}
  </style>
</head>
<body>
  <div class="cardx">
    <div class="head">
      <div class="d-flex justify-content-center align-items-center gap-2">
        <i class="bi bi-basket2-fill fs-3"></i><b>SuperMarket</b>
      </div>
      <div>Crear cuenta</div>
    </div>

    <div class="p-4 p-md-5">
      <?php if(!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?=$_SESSION['flash_error']; unset($_SESSION['flash_error']);?></div>
      <?php endif; ?>
      <?php if(!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?=$_SESSION['flash_success']; unset($_SESSION['flash_success']);?></div>
      <?php endif; ?>

      <form action="procesar_registro.php" method="POST" novalidate>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input class="form-control" name="nombre" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Apellido</label>
            <input class="form-control" name="apellido" required>
          </div>
          <div class="col-12">
            <label class="form-label">Correo</label>
            <input type="email" class="form-control" name="correo" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="contrasena" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control" name="contrasena2" required>
          </div>

          <div class="col-12 mt-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="quiero_admin" name="quiero_admin" value="1">
              <label class="form-check-label" for="quiero_admin">Quiero solicitar rol de administrador</label>
            </div>
          </div>
          <div class="col-12" id="campoCargo" style="display:none">
            <label class="form-label">Cargo (opcional)</label>
            <input class="form-control" name="cargo" placeholder="Ej: coordinador">
            <div class="form-text">Un administrador deberá aprobar tu solicitud.</div>
          </div>
        </div>

        <div class="d-grid mt-4">
          <button class="btn btn-brand btn-lg">Crear cuenta</button>
        </div>

        <div class="d-flex justify-content-between mt-3">
          <a class="text-muted text-decoration-none" href="../index.php"><i class="bi bi-house"></i> Inicio</a>
          <a class="text-decoration-none" style="color:#28a745" href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    const cb=document.getElementById('quiero_admin'), cargo=document.getElementById('campoCargo');
    cb.addEventListener('change',()=>cargo.style.display=cb.checked?'block':'none');
  </script>
</body>
</html>
