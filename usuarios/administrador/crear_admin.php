<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['rol'])) {
    // Redirige al login si no hay sesión activa
    header("Location: /supermarketConexion/login/login.php");
    exit();
}

// 1. Incluir la conexión
require('../../includes/conexion.php');
$con = conexion();

// 2. Verificar que se recibió una petición POST (es decir, que el formulario fue enviado)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3. Capturar los datos del formulario
    $nombre     = $_POST['nombre'];
    $apellido   = $_POST['apellido'];
    $correo     = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // 4. Definir el rol como "admin"
    $rol = 'admin';

    // 5. Preparar e insertar el nuevo administrador en la base de datos
    $sql = "INSERT INTO usuario (nombre, apellido, correo, contrasena, rol)
            VALUES ('$nombre', '$apellido', '$correo', '$contrasena', '$rol')";

    // 6. Ejecutar la consulta e ir al index si fue exitosa
    if (mysqli_query($con, $sql)) {
        header('Location: index_admin.php');
        exit();
    } else {
        echo "Error al registrar: " . mysqli_error($con);
    }
}

// 7. Cerrar la conexión si no se envió formulario
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Registrar Nuevo Administrador</h2>

    <form action="" method="POST" class="p-4 bg-light border rounded shadow-sm">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" name="apellido" id="apellido" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" name="correo" id="correo" required>
        </div>
        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="contrasena" id="contrasena" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="index_admin.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
