<?php
    // Incluye la conexión a la base de datos
    require('conexion.php');
    $con = conexion();

    // Verifica si se recibió un ID por GET (cuando haces clic en "Editar")
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Consulta para obtener los datos del usuario con ese ID
        $sql = "SELECT * FROM usuario WHERE id_usuario = $id";
        $resultado = mysqli_query($con, $sql);

        // Si se encuentra el usuario, guarda los datos en $datos
        if (mysqli_num_rows($resultado) == 1) {
            $datos = mysqli_fetch_array($resultado);
        } else {
            echo "Usuario no encontrado.";
            exit(); // Detiene la ejecución si no hay usuario
        }
    }

    // Si se envió el formulario por método POST (cuando se hace clic en "Actualizar")
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];

        // Actualiza los datos en la base de datos
        $sql = "UPDATE usuario SET 
                    nombre='$nombre',
                    apellido='$apellido',
                    correo='$correo',
                    contrasena='$contrasena'
                WHERE id_usuario = $id";

        // Si se actualiza correctamente, redirige al index
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
    <!-- Enlace a Bootstrap desde CDN (hoja de estilos) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Usuario</h2>

        <form action="editar.php" method="POST" class="p-4 bg-light border rounded shadow-sm">

            <!-- Campo oculto para guardar el ID -->
            <input type="hidden" name="id" value="<?php echo $datos['id_usuario']; ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required
                       value="<?php echo $datos['nombre']; ?>">
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="apellido" id="apellido" required
                       value="<?php echo $datos['apellido']; ?>">
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo" id="correo" required
                       value="<?php echo $datos['correo']; ?>">
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contrasena" id="contrasena" required
                       value="<?php echo $datos['contrasena']; ?>">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Script opcional de Bootstrap para funcionalidades como modales -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

