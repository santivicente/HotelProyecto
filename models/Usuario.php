<?php
// modelo/modelo_usuario.php

// --- FUNCIONES DE CLIENTE ---
function registrarCliente($pdo, $username, $email, $password, $dni, $nombre, $apellido, $telefono) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $pdo->beginTransaction();
        $sql = "INSERT INTO usuario (username, passwordHash, email, activo) VALUES (?, ?, ?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $passwordHash, $email]);
        $idUsuario = $pdo->lastInsertId();
        $sql = "INSERT INTO cliente (DNICliente, nombre, apellido, mail, telefono, idUsuario) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dni, $nombre, $apellido, $email, $telefono, $idUsuario]);
        $pdo->commit();
        return true;
    } catch (PDOException $e) { $pdo->rollBack(); return false; }
}

function verificarUsuario($pdo, $credencial) {
    $sql = "SELECT u.idUsuario, u.username, u.passwordHash, u.email, c.DNICliente, p.DNIPersonalHotel, p.idRol
            FROM usuario u
            LEFT JOIN cliente c ON u.idUsuario = c.idUsuario
            LEFT JOIN personalhotel p ON u.idUsuario = p.idUsuario
            WHERE u.email = ? OR u.username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$credencial, $credencial]);
    return $stmt->fetch();
}

function obtenerDatosCliente($pdo, $dniCliente) {
    $sql = "SELECT c.nombre, c.apellido, c.mail, c.telefono 
            FROM cliente c WHERE c.DNICliente = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dniCliente]);
    return $stmt->fetch();
}

function actualizarDatosCliente($pdo, $dniCliente, $idUsuario, $nombre, $apellido, $email, $telefono) {
    try {
        $pdo->beginTransaction();
        $sql_cliente = "UPDATE cliente SET nombre = ?, apellido = ?, mail = ?, telefono = ? WHERE DNICliente = ?";
        $stmt_cliente = $pdo->prepare($sql_cliente);
        $stmt_cliente->execute([$nombre, $apellido, $email, $telefono, $dniCliente]);
        $sql_usuario = "UPDATE usuario SET email = ? WHERE idUsuario = ?";
        $stmt_usuario = $pdo->prepare($sql_usuario);
        $stmt_usuario->execute([$email, $idUsuario]);
        $pdo->commit();
        return true;
    } catch (PDOException $e) { $pdo->rollBack(); return false; }
}

function cambiarPasswordCliente($pdo, $idUsuario, $passwordActual, $passwordNueva) {
    $sql = "SELECT passwordHash FROM usuario WHERE idUsuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idUsuario]);
    $usuario = $stmt->fetch();
    if (!$usuario) { return "Error: Usuario no encontrado."; }
    if (password_verify($passwordActual, $usuario['passwordHash'])) {
        $nuevoHash = password_hash($passwordNueva, PASSWORD_DEFAULT);
        $sql_update = "UPDATE usuario SET passwordHash = ? WHERE idUsuario = ?";
        $stmt_update = $pdo->prepare($sql_update);
        if ($stmt_update->execute([$nuevoHash, $idUsuario])) {
            return true;
        } else { return "Error al actualizar la contraseña."; }
    } else { return "La contraseña actual es incorrecta."; }
}

// --- FUNCIONES DE PERFIL GENERALES (para el controlador de perfil) ---
function obtenerDatosUsuarioPorId($pdo, $idUsuario) {
    $sql = "SELECT u.idUsuario, u.username, u.email, c.nombre, c.apellido, c.mail, c.telefono
            FROM usuario u
            LEFT JOIN cliente c ON u.idUsuario = c.idUsuario
            WHERE u.idUsuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idUsuario]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function actualizarDatosUsuario($pdo, $idUsuario, $nombre, $apellido, $email, $telefono) {
    try {
        $pdo->beginTransaction();
        // Actualiza datos en cliente
        $sql_cliente = "UPDATE cliente SET nombre = ?, apellido = ?, mail = ?, telefono = ? WHERE idUsuario = ?";
        $stmt_cliente = $pdo->prepare($sql_cliente);
        $stmt_cliente->execute([$nombre, $apellido, $email, $telefono, $idUsuario]);
        // Actualiza email en usuario
        $sql_usuario = "UPDATE usuario SET email = ? WHERE idUsuario = ?";
        $stmt_usuario = $pdo->prepare($sql_usuario);
        $stmt_usuario->execute([$email, $idUsuario]);
        $pdo->commit();
        return true;
    } catch (PDOException $e) { $pdo->rollBack(); return false; }
}

function verificarPasswordUsuario($pdo, $idUsuario, $password_actual) {
    $sql = "SELECT passwordHash FROM usuario WHERE idUsuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idUsuario]);
    $usuario = $stmt->fetch();
    if (!$usuario) return false;
    return password_verify($password_actual, $usuario['passwordHash']);
}

function actualizarPasswordUsuario($pdo, $idUsuario, $password_nueva) {
    $nuevoHash = password_hash($password_nueva, PASSWORD_DEFAULT);
    $sql = "UPDATE usuario SET passwordHash = ? WHERE idUsuario = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nuevoHash, $idUsuario]);
}

// --- FUNCIONES DE GESTIÓN DE PERSONAL ---
function registrarPersonal($pdo, $username, $email, $password, $dni, $nombre, $apellido, $telefono, $idRol) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $pdo->beginTransaction();
        $sql = "INSERT INTO usuario (username, passwordHash, email, activo) VALUES (?, ?, ?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $passwordHash, $email]);
        $idUsuario = $pdo->lastInsertId();
        $sql = "INSERT INTO personalhotel (DNIPersonalHotel, nombre, apellido, mail, telefono, idRol, idUsuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dni, $nombre, $apellido, $email, $telefono, $idRol, $idUsuario]);
        $pdo->commit();
        return true;
    } catch (PDOException $e) { $pdo->rollBack(); return false; }
}

function obtenerTodoElPersonal($pdo) {
    $sql = "SELECT p.DNIPersonalHotel, p.nombre, p.apellido, p.mail, u.activo, r.nombreRol, u.idUsuario 
            FROM personalhotel p
            JOIN usuario u ON p.idUsuario = u.idUsuario
            JOIN rol r ON p.idRol = r.idRol
            ORDER BY p.apellido, p.nombre";
    return $pdo->query($sql)->fetchAll();
}

function obtenerDatosPersonal($pdo, $dni) {
    $sql = "SELECT p.DNIPersonalHotel, p.nombre, p.apellido, p.mail, p.telefono, p.idRol, u.username, u.email, u.idUsuario
            FROM personalhotel p
            JOIN usuario u ON p.idUsuario = u.idUsuario
            WHERE p.DNIPersonalHotel = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dni]);
    return $stmt->fetch();
}

function actualizarDatosPersonal($pdo, $dni, $idUsuario, $nombre, $apellido, $email, $telefono, $idRol) {
    try {
        $pdo->beginTransaction();
        $sql1 = "UPDATE personalhotel SET nombre = ?, apellido = ?, mail = ?, telefono = ?, idRol = ? WHERE DNIPersonalHotel = ?";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute([$nombre, $apellido, $email, $telefono, $idRol, $dni]);
        $sql2 = "UPDATE usuario SET email = ? WHERE idUsuario = ?";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([$email, $idUsuario]);
        $pdo->commit();
        return true;
    } catch (PDOException $e) { $pdo->rollBack(); return false; }
}

function cambiarEstadoUsuario($pdo, $idUsuario, $nuevoEstado) {
    $sql = "UPDATE usuario SET activo = ? WHERE idUsuario = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nuevoEstado, $idUsuario]);
}

function obtenerRoles($pdo) {
    return $pdo->query("SELECT * FROM rol")->fetchAll();
}
?>