<?php
    include('conexion.php');
    $conexion = conexion(); 
        $sql = "SELECT * FROM cliente";
    $resultado = $conexion->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>REGISTRO DE USUARIOS</h1>
        <form action="ingresardatos.php" method="POST">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="NOMBRE">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" placeholder="APELLIDO">
            <label for="correo">Correo</label>
            <input type="text" name="correo" id="correo" placeholder="CORREO">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" placeholder="TELEFONO">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" placeholder="DIRECCION">
            <label for="idRol">Rol_id</label>
            <input type="text" name="idRol" id="idRol" placeholder="idRol">
            <button type="submit">Enviar</button>
        </form>
    </div>

    <div>
        <h2>LEER DATOS DE LA TABLA USUARIOS DE LA BASE DE DATOS</h2>
        <table>
            <thead>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>Apellido</th> 
                <th>Correo</th>
                <th>Telefono</th>
                <th>Direccion</th>
                <th>rol_ID</th>
            </thead>
            <tbody>
                <?php
            if ($resultado->num_rows > 0) {
             
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila['id_cliente'] . "</td>";
                    echo "<td>" . $fila['nombre'] . "</td>";
                    echo "<td>" . $fila['apellido'] . "</td>";
                    echo "<td>" . $fila['correo'] . "</td>";
                    echo "<td>" . $fila['telefono'] . "</td>";
                    echo "<td>" . $fila['direccion'] . "</td>";
                    echo "<td>" . $fila['idRol'] . "</td>";
                    echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay registros</td></tr>";
                }
                ?>
        </tbody>
        </table>
    </div>
</body>
</html>

