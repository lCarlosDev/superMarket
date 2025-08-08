<?php
require('../includes/conexion.php');
require('../includes/auth.php'); // centraliza la sesión

$con = conexion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$correo     = trim($_POST['correo'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');

if ($correo === '' || $contrasena === '') {
    $_SESSION['flash_error'] = "Por favor ingresa correo y contraseña.";
    header("Location: login.php");
    exit();
}

// 1) Buscar usuario por correo
$stmt = $con->prepare("SELECT id_usuario, nombre, correo, contrasena FROM usuario WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
    $_SESSION['flash_error'] = "Usuario o contraseña incorrectos.";
    header("Location: login.php");
    exit();
}

$u = $res->fetch_assoc();

// 2) Validar contraseña (texto plano por ahora)
// TODO: migrar a password_hash()/password_verify() cuando puedas.
if ($u['contrasena'] !== $contrasena) {
    $_SESSION['flash_error'] = "Usuario o contraseña incorrectos.";
    header("Location: login.php");
    exit();
}

// 3) Setear sesión (coherente con includes/auth.php)
$_SESSION['id_usuario']     = (int)$u['id_usuario'];
$_SESSION['nombre_usuario'] = $u['nombre'];
unset($_SESSION['rol']); // limpiar rol previo por si acaso

// 4) Determinar rol y redirigir
$rol = null;

// ¿Es admin?
$stmt = $con->prepare("SELECT 1 FROM admin WHERE id_usuario = ? LIMIT 1");
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
if ($stmt->get_result()->num_rows === 1) {
    $rol = 'admin';
}

// ¿Es cliente?
if ($rol === null) {
    $stmt = $con->prepare("SELECT 1 FROM cliente WHERE id_usuario = ? LIMIT 1");
    $stmt->bind_param("i", $_SESSION['id_usuario']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 1) {
        $rol = 'cliente';
    }
}

if ($rol === 'admin') {
    $_SESSION['rol'] = 'admin';
    header("Location: ../usuarios/administrador/index_admin.php");
    exit();
}

if ($rol === 'cliente') {
    $_SESSION['rol'] = 'cliente';
    header("Location: ../usuarios/cliente/index_cliente.php");
    exit();
}

// Sin rol asignado → mensaje y volver a login (sin bloquear)
$_SESSION['flash_error'] = "Tu usuario no tiene rol asignado aún.";
header("Location: login.php");
exit();
