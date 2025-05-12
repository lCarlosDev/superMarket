<?php
function conexion() {
    $server = "localhost";
    $user = "root";
    $password = "2205LUI.v";
    $bd = "supermarket";
    $puerto = 23306;

    $conexion = new mysqli($server, $user, $password, $bd, $puerto);

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    return $conexion;
}
?>
