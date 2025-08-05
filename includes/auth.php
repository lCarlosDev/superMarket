<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica que el usuario esté logueado
function verificarLogin() {
    if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['rol'])) {
        // Si no hay sesión, redirige al login
        header("Location: /supermarketConexion/login/login.php");
        exit();
    }
}

// Verifica si el usuario es administrador
function verificarAdmin() {
    verificarLogin(); // Primero se asegura que haya login
    if ($_SESSION['rol'] !== 'admin') {
        echo "⛔ Acceso denegado: esta sección es solo para administradores.";
        exit();
    }
}

// Verifica si el usuario es cliente
function verificarCliente() {
    verificarLogin(); // Primero se asegura que haya login
    if ($_SESSION['rol'] !== 'cliente') {
        echo "⛔ Acceso denegado: esta sección es solo para clientes.";
        exit();
    }
}
