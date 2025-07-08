<?php
    // Incluimos el archivo de conexión a la base de datos
    include('conexion.php');
    
    // Establecemos la conexión
    $con = conexion();

    // Verificamos que la conexión haya sido exitosa
    if (!$con) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Validamos si en la URL viene el ID que se quiere eliminar
    if (isset($_GET['id'])) {
        // Guardamos el valor del ID recibido por la URL
        $id = $_GET['id'];

        // Creamos la consulta SQL para eliminar el registro con ese ID
        $sql = "DELETE FROM usuario WHERE id_usuario = '$id'";

        // Ejecutamos la consulta
        $consulta = mysqli_query($con, $sql);

        // Si la consulta fue exitosa, redireccionamos de nuevo a index.php
        if ($consulta) {
            header('Location: index.php');
            exit(); // Nos aseguramos de que el script se detenga aquí
        } else {
            // Si algo falló, mostramos el error
            echo "Error al eliminar: " . mysqli_error($con);
        }
    } else {
        // Si no se recibió ningún ID, mostramos un mensaje
        echo "ID no proporcionado.";
    }

    // Cerramos la conexión a la base de datos
    mysqli_close($con);
?>
