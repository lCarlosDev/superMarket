<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['rol'])) {
    header("Location: /supermarketConexion/login/login.php");
    exit();
}

// Iniciar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Cargar conexión y encabezado
include('../includes/conexion.php');
include('../includes/header.php');

// Obtener todos los productos
$con = conexion();
$sql = "SELECT * FROM producto";
$resultado = $con->query($sql);
?>

<div class="container mt-4">
    <h2 class="mb-4">🛒 Productos disponibles</h2>
    <div class="row">
        <?php while ($row = $resultado->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['nombre'] ?></h5>
                        <p class="card-text"><?= $row['descripcion'] ?></p>
                        <p class="card-text"><strong>Precio:</strong> $<?= number_format($row['precio'], 2) ?></p>
                        <form action="agregar_al_carrito.php" method="POST">
                            <input type="hidden" name="id_producto" value="<?= $row['id_producto'] ?>">
                            <input type="hidden" name="nombre" value="<?= $row['nombre'] ?>">
                            <input type="hidden" name="precio" value="<?= $row['precio'] ?>">
                            <div class="mb-2">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" name="cantidad" value="1" min="1" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Agregar al carrito</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
