<?php
require('../includes/auth.php'); // sesión consistente
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Sesión | SuperMarket</title>

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- CSS global con ruta ABSOLUTA -->
  <link href="/supermarketConexion/includes/estilos.css" rel="stylesheet">

  <style>
    /* Fallback por si estilos.css no carga */
    :root{
      --brand-green: #28a745;
      --brand-green-dark: #218838;
      --brand-yellow: #f6c453;

      --bg-url: url('../img/imgindex3.jpg'); /* tu imagen */
    }

    html, body { height: 100%; }
    body{
      background:
        linear-gradient( rgba(255,255,255,.85), rgba(255,255,255,.85) ),
        var(--bg-url) center/cover no-repeat fixed;
      display:flex; align-items:center; justify-content:center;
    }

    .auth-card{
      width:min(92%, 520px);
      backdrop-filter: blur(8px);
      background: rgba(255,255,255,.75);
      border:1px solid rgba(255,255,255,.6);
      box-shadow: 0 20px 50px rgba(0,0,0,.08);
      border-radius: 18px;
      overflow: hidden;
      animation: pop .5s ease-out;
    }
    @keyframes pop { from { transform: scale(.98); opacity:.5 } to { transform: scale(1); opacity:1 } }

    /* Header con gradiente de la paleta (forzado con !important) */
    .auth-head{
      background: linear-gradient(135deg, var(--brand-green), var(--brand-green-dark)) !important;
      color:#fff !important;
    }

    .has-icon .form-control{ padding-left: 2.4rem; }
    .input-icon{
      position:absolute; left:.9rem; top:50%; transform: translateY(-50%); color:#7e7e7e;
    }
    .toggle-pass{
      position:absolute; right:.6rem; top:50%; transform: translateY(-50%);
      border:none; background:transparent; color:#6c757d;
    }
    .toggle-pass:hover{ color:#343a40; }

    .mini-link{ color:#6b6b6b; text-decoration:none; }
    .mini-link:hover{ color:#222; text-decoration:underline; }
  </style>
</head>
<body>

  <div class="auth-card">
    <!-- LOGO + Marca -->
    <div class="auth-head p-4 text-center">
      <div class="d-flex align-items-center justify-content-center gap-2">
        <i class="bi bi-basket2-fill fs-3"></i>
        <h1 class="h3 m-0 fw-bold">SuperMarket</h1>
      </div>
      <p class="mb-0" style="opacity:.85">Bienvenido de nuevo</p>
    </div>

    <div class="p-4 p-md-5">
      <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger text-center">
          <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        </div>
      <?php endif; ?>

      <form action="validar_login.php" method="POST" novalidate>
        <div class="mb-3 position-relative has-icon">
          <i class="bi bi-envelope-fill input-icon"></i>
          <label for="correo" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="correo" name="correo" placeholder="tucorreo@ejemplo.com" required>
        </div>

        <div class="mb-2 position-relative has-icon">
          <i class="bi bi-lock-fill input-icon"></i>
          <label for="contrasena" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Tu contraseña" required>
          <button type="button" class="toggle-pass" aria-label="Mostrar/Ocultar contraseña" onclick="togglePass()">
            <i id="passIcon" class="bi bi-eye"></i>
          </button>
        </div>

        <div class="d-grid mt-4">
          <!-- Usa el botón del tema global -->
          <button type="submit" class="btn btn-brand btn-lg">Ingresar</button>
        </div>
      </form>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <a class="mini-link" href="../index.php"><i class="bi bi-house-door"></i> Inicio</a>
        <a class="mini-link" href="registro.php">¿No tienes cuenta? Regístrate</a>
      </div>
    </div>
  </div>

  <script>
    function togglePass(){
      const input = document.getElementById('contrasena');
      const icon  = document.getElementById('passIcon');
      const isText = input.type === 'text';
      input.type = isText ? 'password' : 'text';
      icon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
