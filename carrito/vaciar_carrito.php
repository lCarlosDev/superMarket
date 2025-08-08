<?php
session_start();

// Verifica sesión activa
if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['rol'])) {
    header("Location: /supermarketConexion/login/login.php");
    exit();
}

// Elimina el carrito
unset($_SESSION['carrito']);

// ⚠️ NUEVO: Guardamos un mensaje temporal
$_SESSION['mensaje_carrito_vacio'] = "🧺 El carrito ha sido vaciado exitosamente.";

// Redirige al ver_carrito
header("Location: ver_carrito.php");
exit();
