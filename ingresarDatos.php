<?php
    require('conexion.php');
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

    // Inserta los datos en la tabla (ajusta el nombre del campo de la base de datos si es necesario)
    $sql = "INSERT INTO usuario(nombre, apellido, correo, password)
            VALUES ('$nombre', '$apellido', '$correo', '$contrasena')";

    // Ejecuta la consulta
    if (mysqli_query($con, $sql)) {
        echo "Registro exitoso";
    } else {
        echo "Error al registrar: " . mysqli_error($con);
    }

    // Cierra la conexión
    mysqli_close($con);
?>
