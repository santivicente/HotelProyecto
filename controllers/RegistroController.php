<?php

require_once __DIR__ . '/../models/Db.php';
require_once __DIR__ . '/../models/Usuario.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $dni = trim($_POST['dni'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if (!$username || !$email || !$password || !$password_confirm || !$dni || !$nombre || !$apellido) {
        $error = "Todos los campos obligatorios deben estar completos.";
    } elseif ($password !== $password_confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verifica si el usuario o email ya existen
        $usuarioExistente = verificarUsuario($pdo, $email);
        if ($usuarioExistente) {
            $error = "El usuario o email ya están registrados.";
        } else {
            if (registrarCliente($pdo, $username, $email, $password, $dni, $nombre, $apellido, $telefono)) {
                $exito = "¡Registro exitoso! Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar el usuario. Intenta nuevamente.";
            }
        }
    }
}

include __DIR__ . '/../views/usuario/registro.php';