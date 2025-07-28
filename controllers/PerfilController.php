<?php

require_once __DIR__ . '/../includes/verificar_sesion.php';
require_once __DIR__ . '/../models/Db.php';
require_once __DIR__ . '/../models/Usuario.php';

// Solo usuarios logueados pueden ver su perfil
if (!isset($_SESSION['idUsuario'])) {
    header('Location: /hotelProyecto/index.php?controller=login');
    exit();
}

$idUsuario = $_SESSION['idUsuario'];
$cliente = obtenerDatosUsuarioPorId($pdo, $idUsuario);

$error = '';
$exito = '';

// Actualizar datos personales
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar_datos') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);

    if ($nombre && $apellido && $email) {
        if (actualizarDatosUsuario($pdo, $idUsuario, $nombre, $apellido, $email, $telefono)) {
            $exito = "Datos actualizados correctamente.";
            $cliente = obtenerDatosUsuarioPorId($pdo, $idUsuario);
        } else {
            $error = "No se pudo actualizar los datos.";
        }
    } else {
        $error = "Todos los campos obligatorios deben estar completos.";
    }
}

// Cambiar contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'cambiar_password') {
    $password_actual = $_POST['password_actual'];
    $password_nueva = $_POST['password_nueva'];
    $password_confirmar = $_POST['password_confirmar'];

    if ($password_nueva !== $password_confirmar) {
        $error = "La nueva contraseña y la confirmación no coinciden.";
    } elseif (!verificarPasswordUsuario($pdo, $idUsuario, $password_actual)) {
        $error = "La contraseña actual es incorrecta.";
    } else {
        if (actualizarPasswordUsuario($pdo, $idUsuario, $password_nueva)) {
            $exito = "Contraseña cambiada correctamente.";
        } else {
            $error = "No se pudo cambiar la contraseña.";
        }
    }
}

include __DIR__ . '/../views/usuario/perfil.php';