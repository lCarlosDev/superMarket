<?php
// index.php - Página inicial pública
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperMarket - Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
            background-image: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.85)), url('img/imgindex2.jpg');
            background-size: cover;
            background-position: center;
        }
        .hero-section {
            padding: 5rem 1rem;
            text-align: center;
        }
        .hero-section h1 {
            font-weight: 700;
            color: #28a745;
        }
        .hero-section p {
            font-size: 1.2rem;
            color: #555;
        }
        .btn-custom {
            min-width: 200px;
            font-size: 1.1rem;
        }
        footer {
            margin-top: 3rem;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>

 <div class="container hero-section">
    <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
        <i class="bi bi-basket2-fill fs-1 text-success"></i>
        <h1 class="fw-bold text-success">SuperMarket</h1>
    </div>
    <p>Tu tienda virtual de confianza. Compra fácil, rápido y seguro.</p>
    <div class="mt-4">
        <a href="login/login.php" class="btn btn-success btn-lg btn-custom me-3">Iniciar Sesión</a>
        <a href="login/registro.php" class="btn btn-outline-success btn-lg btn-custom">Registrarse</a>
    </div>
</div>


    <footer>
        <p>&copy; <?php echo date("Y"); ?> SuperMarket. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
