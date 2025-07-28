<?php
    function conexion(){
        $server="localhost";
        $user="root";
        $contrasena="";
        $bd="supermarket";
        $connect=new mysqli($server, $user, $contrasena, $bd);
        return $connect;
    }
    
?>