<?php
// Paso 1: Iniciar sesión y verificar rol
include('../includes/auth.php');
verificarAdmin(); // Solo los administradores pueden eliminar usuarios

// Paso 2: Conectar a la base de datos
include('../includes/conexion.php');
$con = conexion();

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Paso 3: Verificar que venga el ID del usuario
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Paso 4: Preparar y ejecutar la consulta de eliminación
    $sql = "DELETE FROM usuario WHERE id_usuario = $id";
    $resultado = mysqli_query($con, $sql);

    if ($resultado) {
        // Paso 5: Redirigir si se eliminó correctamente
        header("Location: index_usuario.php");
        exit();
    } else {
        echo "Error al eliminar: " . mysqli_error($con);
    }
} else {
    echo "ID de usuario no proporcionado.";
}

// Paso 6: Cerrar la conexión
mysqli_close($con);
?>
