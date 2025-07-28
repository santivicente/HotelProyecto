<?php
// ARCHIVO: gestion_habitaciones.php
require_once __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('personal');

require_once __DIR__ . '/../models/Db.php';
require_once __DIR__ . '/../models/Habitacion.php';

$lista_tipos = obtenerTiposDeHabitacionResumido($pdo);
$lista_habitaciones = obtenerTodasLasHabitacionesIndividuales($pdo);

include __DIR__ . '/../views/habitacion/gestion.php';
?>