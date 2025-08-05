<?php
session_start(); // Iniciamos la sesión

// Destruimos todas las variables de sesión
session_unset();  // Limpia todas las variables de sesión
session_destroy(); // Destruye la sesión

// Redirigimos al login
header("Location: login.php");
exit();
?>

