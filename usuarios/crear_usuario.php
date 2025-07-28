<?php
    require('../includes/conexion.php');
    $con = conexion();

    // Verifica si la conexión fue exitosa
    if (!$con) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Captura los datos del formulario
    $nombre     = $_POST['nombre'];
    $apellido   = $_POST['apellido'];
    $correo     = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Inserta los datos en la tabla
    $sql = "INSERT INTO usuario(nombre, apellido, correo, contrasena)
            VALUES ('$nombre', '$apellido', '$correo', '$contrasena')";

    // Ejecuta la consulta
    if (mysqli_query($con, $sql)) {
        header('Location: index_usuarios.php');
        exit();
    } else {
        echo "Error al registrar: " . mysqli_error($con);
    }

    mysqli_close($con);
?>
