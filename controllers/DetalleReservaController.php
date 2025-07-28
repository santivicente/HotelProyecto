<?php

// ARCHIVO: ver_detalle_reserva.php
require_once __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('personal');

require_once __DIR__ . '/../models/Db.php';
require_once __DIR__ . '/../models/Reserva.php';

$idReserva = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$idReserva) {
    header('Location: ../index.php?controller=reserva');
    exit();
}

$detalles_reserva = obtenerDetallesDeUnaReserva($pdo, $idReserva);

if (!$detalles_reserva) {
    // Si no se encuentra la reserva, redirigir con un mensaje (opcional)
    header('Location: ../index.php?controller=reserva&error=no_encontrada');
    exit();
}

include __DIR__ . '/../views/reserva/detalle_reserva.php';
?>