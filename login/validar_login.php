<?php
session_start(); // Importante para poder guardar datos de sesión (usuario logueado)

require('../includes/conexion.php'); // Conectamos a la base de datos
$con = conexion(); // Llamamos la función para obtener la conexión

// Verificamos si se enviaron los datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // 1. Buscamos en la tabla usuario
    $sql = "SELECT * FROM usuario WHERE correo = '$correo' AND contrasena = '$contrasena'";
    $resultado = mysqli_query($con, $sql);

    // 2. Si hay coincidencia con un usuario
    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        // Guardamos datos básicos en sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];

        $id_usuario = $usuario['id_usuario'];

        // 3. Verificamos si es administrador
        $sql_admin = "SELECT * FROM admin WHERE id_usuario = $id_usuario";
        $resultado_admin = mysqli_query($con, $sql_admin);

        if (mysqli_num_rows($resultado_admin) == 1) {
            $_SESSION['rol'] = 'admin';
            header("Location: ../usuarios/administrador/index_admin.php"); // Redirigimos al módulo admin
            exit();
        }

        // 4. Verificamos si es cliente
        $sql_cliente = "SELECT * FROM cliente WHERE id_usuario = $id_usuario";
        $resultado_cliente = mysqli_query($con, $sql_cliente);

        if (mysqli_num_rows($resultado_cliente) == 1) {
            $_SESSION['rol'] = 'cliente';
            header("Location: ../usuarios/cliente/index_cliente.php"); // Redirigimos al módulo cliente
            exit();
        }

        // 5. Si no está ni en admin ni en cliente
        echo "El usuario no tiene rol asignado.";
    } else {
        echo "Correo o contraseña incorrectos.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
