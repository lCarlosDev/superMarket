<?php
// Inicia sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Supermarket App</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../includes/estilos.css">
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <ul class="nav-menu">
            <li><a href="/supermarketConexion/usuarios/index_usuario.php">👤 Usuarios</a></li>
            <li><a href="/supermarketConexion/productos/index_producto.php">🛒 Productos</a></li>
            <!-- Puedes agregar más módulos aquí -->
        </ul>
    </nav>
    <hr>

    <!-- Bienvenida y botón de cerrar sesión si hay sesión activa -->
    <?php if (isset($_SESSION['nombre_usuario']) && isset($_SESSION['rol'])): ?>
        <div style="text-align: right; margin: 10px 20px;">
            <span>👋 Bienvenido, <strong><?= $_SESSION['nombre_usuario'] ?></strong> (<?= $_SESSION['rol'] ?>)</span>
            <a href="/supermarketConexion/login/logout.php" class="btn btn-sm btn-danger" style="margin-left: 10px;">Cerrar sesión</a>
        </div>
    <?php endif; ?>
