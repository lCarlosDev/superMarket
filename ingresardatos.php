<?php
    require('conexion.php');
    $con=conexion();
    $nombre=$_POST['nombre'];
    $apellido=$_POST['apellido'];
    $correo=$_POST['correo'];
    $telefono=$_POST['telefono'];
    $direccion=$_POST['direccion'];
    $idRol=$_POST['idRol'];

    $sql="INSERT INTO cliente(nombre, apellido, correo, telefono, direccion, idRol) VALUES('$nombre', '$apellido', '$correo', '$telefono', '$direccion', '$idRol')";
    $consulta=mysqli_query($con, $sql);

    if ($consulta){
        header("location:index.php");
    }
?>