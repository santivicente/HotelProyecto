<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verificarRol($rolRequerido) {
    $rolUsuario = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';

    if (!isset($_SESSION['idUsuario']) || $rolUsuario !== $rolRequerido) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: /hotelProyecto/index.php?controller=login');
        exit();
    }
}
?>