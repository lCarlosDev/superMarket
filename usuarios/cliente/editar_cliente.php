<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['rol'])) {
    // Redirige al login si no hay sesión activa
    header("Location: /supermarketConexion/login/login.php");
    exit();
}

require('../includes/conexion.php');
$con = conexion();

// PASO 1: Verificamos si viene el ID por la URL (GET)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // PASO 2: Consultamos los datos del cliente
    $sql = "SELECT * FROM cliente WHERE id_cliente = $id";
    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $datos = mysqli_fetch_array($resultado); // Guarda los datos del cliente para el formulario
    } else {
        echo "Cliente no encontrado.";
        exit(); // Salimos si no existe ese cliente
    }
}

// PASO 3: Si el formulario fue enviado (POST), actualizamos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];

    // PASO 4: Ejecutamos el UPDATE
    $sql = "UPDATE cliente SET 
                nombre='$nombre',
                apellido='$apellido',
                correo='$correo',
                direccion='$direccion'
            WHERE id_cliente = $id";

    if (mysqli_query($con, $sql)) {
        header("Location: index_cliente.php"); // Redirige al listado
        exit();
    } else {
        echo "Error al actualizar: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Cliente</h2>

    <form action="editar_cliente.php" method="POST" class="p-4 bg-light border rounded shadow-sm">
        <!-- Campo oculto para el ID -->
        <input type="hidden" name="id" value="<?= $datos['id_cliente'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= $datos['nombre'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="<?= $datos['apellido'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" value="<?= $datos['correo'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?= $datos['direccion'] ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index_cliente.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
