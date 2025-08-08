<?php
// includes/auth.php

// Asegura cookie de sesión válida para todo el sitio
if (session_status() === PHP_SESSION_NONE) {
    // opcional, pero ayuda cuando tienes rutas /supermarketConexion/...
    session_set_cookie_params(['path' => '/']);
    session_start();
}

/**
 * Requiere que exista sesión iniciada.
 * NO valida rol; eso lo hace require_role().
 */
function require_login(): void {
    if (empty($_SESSION['id_usuario'])) {          // clave principal para saber si hay sesión
        header("Location: /supermarketConexion/login/login.php");
        exit();
    }
}

/**
 * Requiere que el usuario tenga uno de los roles permitidos.
 * $roles puede ser 'admin', 'cliente' o ['admin','cliente'].
 */
function require_role($roles): void {
    require_login();
    if (is_string($roles)) {
        $roles = [$roles];
    }
    $rol = $_SESSION['rol'] ?? null;
    if (!$rol || !in_array($rol, $roles, true)) {
        header("Location: /supermarketConexion/sin_acceso.php");
        exit();
    }
}

/**
 * Devuelve datos mínimos del usuario autenticado.
 */
function current_user(): array {
    return [
        'id'     => $_SESSION['id_usuario']   ?? null,
        'nombre' => $_SESSION['nombre_usuario'] ?? null,
        'rol'    => $_SESSION['rol']          ?? null,
    ];
}
