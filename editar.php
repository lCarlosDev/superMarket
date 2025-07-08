<?php
    // Incluye el archivo de conexión
    require('conexion.php');
    $con = conexion();

    // Verifica si se envió un ID por URL (GET)
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Consulta para obtener los datos actuales del usuario por su ID
        $sql = "SELECT * FROM usuario WHERE id_usuario = $id";
        $resultado = mysqli_query($con, $sql);

        // Si existe un resultado, extrae los datos en un array asociativo
        if (mysqli_num_rows($resultado) == 1) {
            $datos = mysqli_fetch_array($resultado);
        } else {
            echo "Usuario no encontrado.";
            exit();
        }
    }

    // Si se envió el formulario (por POST), actualiza los datos
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];

        // Consulta SQL para actualizar los datos del usuario
        $sql = "UPDATE usuario SET 
                    nombre='$nombre',
                    apellido='$apellido',
                    correo='$correo',
                    contrasena='$contrasena'
                WHERE id_usuario = $id";

        // Ejecuta la consulta y redirige si fue exitosa
        if (mysqli_query($con, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error al actualizar: " . mysqli_error($con);
        }
    }

    // Cierra la conexión
    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <style>
        form {
            width: 50%;
            margin: 30px auto;
        }

        label, input, button {
            display: block;
            width: 100%;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Editar Usuario</h2>
    <form method="POST">
        <!-- Campo oculto para enviar el ID del usuario -->
        <input type="hidden" name="id" value="<?php echo $datos['id_usuario']; ?>">

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" required>

        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" value="<?php echo $datos['apellido']; ?>" required>

        <label for="correo">Correo</label>
        <input type="email" name="correo" value="<?php echo $datos['correo']; ?>" required>

        <label for="contrasena">Contraseña</label>
        <input type="password" name="contrasena" value="<?php echo $datos['contrasena']; ?>" required>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
