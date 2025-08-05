<?php
include('../../includes/conexion.php');
include('../../includes/header.php');
$con = conexion();

// --- 1. Obtener los datos del admin si viene por GET (para mostrar en el formulario) ---
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT admin.*, usuario.nombre, usuario.apellido 
            FROM admin 
            INNER JOIN usuario ON admin.id_usuario = usuario.id_usuario 
            WHERE admin.id_admin = $id";

    $resultado = mysqli_query($con, $sql);

    if ($resultado && mysqli_num_rows($resultado) == 1) {
        $admin = mysqli_fetch_assoc($resultado);
    } else {
        echo "<p class='text-danger'>Administrador no encontrado.</p>";
        exit();
    }
}

// --- 2. Si el formulario fue enviado por POST, actualizamos el cargo ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $cargo = $_POST['cargo'];

    $sql = "UPDATE admin SET cargo = '$cargo' WHERE id_admin = $id";

    if (mysqli_query($con, $sql)) {
        header("Location: index_admin.php");
        exit();
    } else {
        echo "<p class='text-danger'>Error al actualizar: " . mysqli_error($con) . "</p>";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Administrador</h2>

    <form method="POST" action="editar_admin.php" class="p-4 bg-light border rounded shadow-sm w-50 mx-auto">
        <input type="hidden" name="id" value="<?= $admin['id_admin'] ?>">

        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" class="form-control" value="<?= $admin['nombre'] ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Apellido:</label>
            <input type="text" class="form-control" value="<?= $admin['apellido'] ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="cargo" class="form-label">Cargo</label>
            <input type="text" class="form-control" name="cargo" id="cargo" required value="<?= $admin['cargo'] ?>">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index_admin.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
