<?php
// Paso 1: Iniciar sesión y verificar si es administrador
include('../includes/auth.php');
verificarAdmin(); // Solo administradores pueden editar usuarios

// Paso 2: Conexión a la base de datos
include('../includes/conexion.php');
$con = conexion();

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Paso 3: Si se accede con GET (desde botón Editar)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar el usuario por ID
    $sql = "SELECT * FROM usuario WHERE id_usuario = $id";
    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado); // Guardamos sus datos para el formulario
    } else {
        echo "Usuario no encontrado.";
        exit();
    }
}

// Paso 4: Si se envió el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = $_POST['id'];
    $nombre     = trim($_POST['nombre']);
    $apellido   = trim($_POST['apellido']);
    $correo     = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);

    // Validar campos
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($contrasena)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Actualizar los datos en la base
    $sql = "UPDATE usuario SET 
                nombre = '$nombre',
                apellido = '$apellido',
                correo = '$correo',
                contrasena = '$contrasena'
            WHERE id_usuario = $id";

    if (mysqli_query($con, $sql)) {
        header("Location: index_usuario.php");
        exit();
    } else {
        echo "Error al actualizar: " . mysqli_error($con);
    }
}

// Paso 5: Cerrar conexión
mysqli_close($con);
?>

<!-- Paso 6: Formulario de edición -->
<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Usuario</h2>

    <form action="editar_usuario.php" method="POST" class="p-4 bg-light border rounded shadow-sm w-50 mx-auto">
        <input type="hidden" name="id" value="<?= $usuario['id_usuario'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= $usuario['nombre'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" id="apellido" class="form-control" value="<?= $usuario['apellido'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" value="<?= $usuario['correo'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" class="form-control" value="<?= $usuario['contrasena'] ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index_usuario.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
