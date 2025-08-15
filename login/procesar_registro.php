<?php
// login/procesar_registro.php
session_start();
require_once __DIR__ . '/../includes/conexion.php';
$con  = conexion();
$BASE = '/supermarketConexion';

// 1) Datos del form
$nombre      = trim($_POST['nombre'] ?? '');
$apellido    = trim($_POST['apellido'] ?? '');
$correo      = trim($_POST['correo'] ?? '');
$pass1       = trim($_POST['contrasena'] ?? '');
$pass2       = trim($_POST['contrasena2'] ?? '');
$quieroAdmin = !empty($_POST['quiero_admin']);      // <-- CLAVE
$cargo       = trim($_POST['cargo'] ?? '');

// 2) Validaciones básicas
if ($nombre==='' || $apellido==='' || $correo==='' || $pass1==='' || $pass2==='') {
  $_SESSION['flash_error'] = 'Completa todos los campos.';
  header("Location: $BASE/login/registro.php"); exit;
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['flash_error'] = 'Correo inválido.';
  header("Location: $BASE/login/registro.php"); exit;
}
if ($pass1 !== $pass2) {
  $_SESSION['flash_error'] = 'Las contraseñas no coinciden.';
  header("Location: $BASE/login/registro.php"); exit;
}

// 3) Correo único
$stmt = $con->prepare("SELECT 1 FROM usuario WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
if ($stmt->get_result()->num_rows) {
  $_SESSION['flash_error'] = 'Ese correo ya está registrado.';
  header("Location: $BASE/login/registro.php"); exit;
}
$stmt->close();

// 4) Crear usuario (NOTA: usas texto plano, mantengo eso para no romper tu login)
$pwd  = $pass1;
$stmt = $con->prepare("INSERT INTO usuario (nombre, apellido, correo, contrasena) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $apellido, $correo, $pwd);
if (!$stmt->execute()) {
  $_SESSION['flash_error'] = 'Error creando usuario: ' . $stmt->error;
  header("Location: $BASE/login/registro.php"); exit;
}
$id_usuario = $stmt->insert_id;
$stmt->close();

// 5) Registrar como cliente si existe la tabla cliente
if ($con->query("SHOW TABLES LIKE 'cliente'")->num_rows) {
  $stmt = $con->prepare("INSERT INTO cliente (id_usuario) VALUES (?)");
  $stmt->bind_param("i", $id_usuario);
  $stmt->execute();
  $stmt->close();
}

// 6) Si pidió rol admin -> insertar en solicitudes_admin
if ($quieroAdmin) {

  // (Opcional) verificar que la tabla existe
  if (!$con->query("SHOW TABLES LIKE 'solicitudes_admin'")->num_rows) {
    $_SESSION['flash_error'] = "La tabla solicitudes_admin no existe.";
    header("Location: $BASE/login/registro.php"); exit;
  }

  $stmt = $con->prepare("
    INSERT INTO solicitudes_admin (id_usuario, cargo, estado)
    VALUES (?, ?, 'pendiente')
  ");
  if (!$stmt) {
    $_SESSION['flash_error'] = "Error SQL (prepare): " . $con->error;
    header("Location: $BASE/login/registro.php"); exit;
  }

  // bind exacto: id_usuario INT, cargo VARCHAR
  $stmt->bind_param("is", $id_usuario, $cargo);

  if (!$stmt->execute()) {
    $_SESSION['flash_error'] = "No se pudo guardar la solicitud de admin: " . $stmt->error;
    header("Location: $BASE/login/registro.php"); exit;
  }
  $stmt->close();

  $_SESSION['flash_success'] = 'Cuenta creada. Tu solicitud de administrador está pendiente. Puedes iniciar sesión como cliente.';
} else {
  $_SESSION['flash_success'] = 'Cuenta creada. Ya puedes iniciar sesión.';
}

// 7) Volver al login
header("Location: $BASE/login/login.php");
exit;
