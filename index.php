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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <div>
        <h2 style="text-align:center;">Registro de Usuarios</h2>
        <form action="ingresarDatos.php" method="POST">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="NOMBRE" required>

            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" placeholder="APELLIDO" required>

            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" placeholder="CORREO" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" placeholder="CONTRASEÑA" required>

            <button type="submit">Enviar</button>
        </form>
    </div>

    <h2 style="text-align:center;">Lista de Usuarios</h2>
    <div>
        <table>
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Clave</th>
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
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay usuarios registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
