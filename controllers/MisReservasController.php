<?php

require __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('cliente');

require __DIR__ . '/../models/Db.php';
require __DIR__ . '/../models/Reserva.php';

$dniCliente = $_SESSION['DNICliente'];
$reservas = obtenerReservasPorCliente($pdo, $dniCliente);

include __DIR__ . '/../views/reserva/mis_reservas.php';
?>