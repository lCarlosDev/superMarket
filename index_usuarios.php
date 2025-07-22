<?php
    include('conexion.php');
    $con = conexion();

    // Verifica si la conexión fue exitosa
    if (!$con) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Consulta para obtener los usuarios
    $sql = "SELECT * FROM usuario";
    $consulta = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Agregar Bootstrap en el <head> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Lista de Usuarios</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            width: 80%;
            margin: 20px auto;
        }
        label, input, button {
            display: block;
            width: 100%;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="form_registro">
        <h2 style="text-align:center;">Registro de Usuarios</h2>
        <form action="ingresarDatos.php" method="POST" class="p-4 bg-light border rounded shadow-sm w-50 mx-auto">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo" id="correo" placeholder="Correo" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>

    <!-- Botón para ir al módulo de productos -->
    <div style="text-align: center; margin: 20px;">
        <a href="productos/index_productos.php" class="btn btn-success">Ir al módulo de productos</a>
    </div>

    <h2 style="text-align:center;">Lista de Usuarios</h2>
    <div>
        <table class="table table-striped table-bordered table-hover mt-4 w-75 mx-auto">
            <thead class="table-dark">
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Clave</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($consulta && mysqli_num_rows($consulta) > 0): ?>
                    <?php while ($datos = mysqli_fetch_array($consulta)): ?>
                        <tr>
                            <td><?php echo $datos['id_usuario']; ?></td>
                            <td><?php echo $datos['nombre']; ?></td>
                            <td><?php echo $datos['apellido']; ?></td>
                            <td><?php echo $datos['correo']; ?></td>
                            <td><?php echo $datos['contrasena']; ?></td>
                            <td>
                                <a href="eliminar.php?id=<?php echo $datos['id_usuario']; ?>"
                                onclick="return confirm('¿Estás segura/o que deseas eliminar este usuario?');">Eliminar</a> | 
                                <a href="editar.php?id=<?php echo $datos['id_usuario']; ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay usuarios registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Script opcional de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
