<?php
include('../../includes/conexion.php');   // Conexión a la base de datos
include('../../includes/header.php');     // Encabezado común del sitio

$con = conexion(); // Establecemos la conexión
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Registrar Cliente</h2>

    <!-- Formulario para registrar un nuevo cliente -->
    <form action="crear_cliente.php" method="POST" class="p-4 bg-light border rounded shadow-sm w-50 mx-auto">

        <!-- Selección de usuario existente -->
        <div class="mb-3">
            <label for="id_usuario" class="form-label">Seleccionar Usuario</label>
            <select name="id_usuario" id="id_usuario" class="form-select" required>
                <?php
                $usuarios = mysqli_query($con, "SELECT id_usuario, nombre, apellido FROM usuario");
                while ($user = mysqli_fetch_assoc($usuarios)) {
                    echo "<option value='{$user['id_usuario']}'>{$user['nombre']} {$user['apellido']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Campo de dirección -->
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" name="direccion" id="direccion" required>
        </div>

        <!-- Campo de teléfono -->
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" name="telefono" id="telefono" required>
        </div>

        <!-- Botones de acción -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Registrar Cliente</button>
            <a href="index_cliente.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
// Procesamos el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Insertar en la tabla cliente
    $sql = "INSERT INTO cliente (id_usuario, direccion, telefono)
            VALUES ('$id_usuario', '$direccion', '$telefono')";

    if (mysqli_query($con, $sql)) {
        // Redirigir a la lista de clientes si todo fue bien
        header("Location: index_cliente.php");
        exit();
    } else {
        // Mostrar error si falla la inserción
        echo "<div class='alert alert-danger text-center mt-4'>Error al registrar cliente: " . mysqli_error($con) . "</div>";
    }
}
?>

<?php include('../../includes/footer.php'); ?>
