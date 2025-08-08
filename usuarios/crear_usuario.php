<?php
// Paso 1: Iniciar sesión y proteger acceso
include('../includes/auth.php');
verificarAdmin(); // Solo un administrador puede crear usuarios

// Paso 2: Conectar a la base de datos
include('../includes/conexion.php');
$con = conexion();

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Paso 3: Validar que los datos vienen por método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Paso 4: Capturar los datos del formulario
    $nombre     = trim($_POST['nombre']);
    $apellido   = trim($_POST['apellido']);
    $correo     = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);

    // Validación básica
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($contrasena)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Paso 5: Insertar en la base de datos
    $sql = "INSERT INTO usuario (nombre, apellido, correo, contrasena)
            VALUES ('$nombre', '$apellido', '$correo', '$contrasena')";

    if (mysqli_query($con, $sql)) {
        // Redirigir a la lista de usuarios
        header("Location: index_usuario.php");
        exit();
    } else {
        echo "Error al registrar: " . mysqli_error($con);
    }
} else {
    // Si se accede sin POST, redirigir a index
    header("Location: index_usuario.php");
    exit();
}

// Paso 6: Cerrar conexión
mysqli_close($con);
