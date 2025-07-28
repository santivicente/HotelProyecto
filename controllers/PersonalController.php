<?php

require_once __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('personal');
require_once __DIR__ . '/../models/Db.php';

$error = '';
$exito = '';

// --- Procesar formulario de alta o edición ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accionForm = $_POST['accion_form'] ?? '';
    if ($accionForm === 'crear') {
        // Alta de empleado
        $dni = trim($_POST['dni']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $mail = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $idRol = (int)$_POST['idRol'];

        // Validar campos obligatorios
        if (!$dni || !$username || !$password || !$nombre || !$apellido || !$mail || !$idRol) {
            $error = "Todos los campos obligatorios deben estar completos.";
        } else {
            // Verificar si el DNI ya existe en personalhotel
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM personalhotel WHERE DNIPersonalHotel = ?");
            $stmt->execute([$dni]);
            $dniExists = $stmt->fetchColumn() > 0;

            // Verificar si el username ya existe en usuario
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE username = ?");
            $stmt->execute([$username]);
            $usernameExists = $stmt->fetchColumn() > 0;

            if ($dniExists || $usernameExists) {
                $error = "Ya existe un empleado con ese DNI o nombre de usuario.";
            } else {
                // Insertar usuario en tabla usuario
                $stmtUser = $pdo->prepare("INSERT INTO usuario (username, passwordHash) VALUES (?, ?)");
                $okUser = $stmtUser->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
                if ($okUser) {
                    $idUsuario = $pdo->lastInsertId();
                    // Insertar empleado
                    $stmtEmp = $pdo->prepare("INSERT INTO personalhotel (DNIPersonalHotel, nombre, apellido, mail, telefono, idRol, idUsuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $okEmp = $stmtEmp->execute([$dni, $nombre, $apellido, $mail, $telefono, $idRol, $idUsuario]);
                    if ($okEmp) {
                        $exito = "Empleado registrado correctamente.";
                        // Redirigir para evitar reenvío del formulario y refrescar la lista
                        header('Location: /hotelProyecto/index.php?controller=personal&exito=' . urlencode($exito));
                        exit();
                    } else {
                        $error = "Error al registrar el empleado.";
                    }
                } else {
                    $error = "Error al crear el usuario.";
                }
            }
        }
    } elseif ($accionForm === 'editar') {
        // Edición de empleado
        $dni = trim($_POST['dni']);
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $mail = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $idRol = (int)$_POST['idRol'];
        $idUsuario = (int)$_POST['idUsuario'];

        if (!$dni || !$nombre || !$apellido || !$mail || !$idRol) {
            $error = "Todos los campos obligatorios deben estar completos.";
        } else {
            // Actualizar empleado
            $stmtEmp = $pdo->prepare("UPDATE personalhotel SET nombre=?, apellido=?, mail=?, telefono=?, idRol=? WHERE DNIPersonalHotel=?");
            $okEmp = $stmtEmp->execute([$nombre, $apellido, $mail, $telefono, $idRol, $dni]);
            if ($okEmp) {
                $exito = "Empleado actualizado correctamente.";
                header('Location: /hotelProyecto/index.php?controller=personal&exito=' . urlencode($exito));
                exit();
            } else {
                $error = "Error al actualizar el empleado.";
            }
        }
    }
}

// --- Obtener lista de personal ---
$stmt = $pdo->query("SELECT p.*, r.nombreRol 
    FROM personalhotel p 
    JOIN rol r ON p.idRol = r.idRol");
$listaPersonal = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Obtener roles para el formulario ---
$stmtRoles = $pdo->query("SELECT * FROM rol");
$roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

// --- Si estás editando, busca el empleado ---
$empleado = null;
if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['dni'])) {
    $stmtEmp = $pdo->prepare("SELECT * FROM personalhotel WHERE DNIPersonalHotel = ?");
    $stmtEmp->execute([$_GET['dni']]);
    $empleado = $stmtEmp->fetch(PDO::FETCH_ASSOC);
    $accion = 'editar';
} else {
    $accion = '';
}

// Mensaje de éxito por GET
if (isset($_GET['exito'])) {
    $exito = $_GET['exito'];
}

// Incluye la vista
include __DIR__ . '/../views/personal/gestion.php';