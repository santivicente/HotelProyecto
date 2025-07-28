<?php
// ARCHIVO: modelo/modelo_reserva.php

/**
 * El cliente crea una SOLICITUD de reserva.
 * La reserva se crea con estado "Pendiente de Confirmación" (ID 4)
 * y con una habitación física ya asignada como "placeholder".
 * 
 * --- TRANSACCIÓN ---
 * Esta función utiliza una transacción para que, si ocurre un error en alguna inserción,
 * se deshagan todos los cambios y no queden reservas a medias.
 * La transacción comienza con $pdo->beginTransaction(),
 * se confirma con $pdo->commit() si todo sale bien,
 * y se revierte con $pdo->rollBack() si ocurre un error.
 */
function crearReservaCompleta($pdo, $dniCliente, $idsHabitaciones, $fechaDesde, $fechaHasta) {
    if (empty($idsHabitaciones) || empty($dniCliente) || empty($fechaDesde) || empty($fechaHasta)) { return false; }
    
    $fechaReserva = date('Y-m-d');
    $horaReserva = date('H:i:s');
    $idEstadoReserva = 4; // 4 = Pendiente de Confirmación
    $dniPersonal = null; // Lo crea el cliente, no el personal

    try {
        $pdo->beginTransaction(); // --- INICIO DE TRANSACCIÓN ---
        foreach ($idsHabitaciones as $idHabitacion) {
            $sqlReserva = "INSERT INTO reserva (DNICliente, DNIPersonalHotel, fechaReserva, horaReserva, fechaDesde, fechaHasta, idEstadoReserva) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtReserva = $pdo->prepare($sqlReserva);
            $stmtReserva->execute([$dniCliente, $dniPersonal, $fechaReserva, $horaReserva, $fechaDesde, $fechaHasta, $idEstadoReserva]);
            $idReserva = $pdo->lastInsertId();

            $sqlDetalle = "INSERT INTO detallereserva (idReserva, idHabitacion) VALUES (?, ?)";
            $stmtDetalle = $pdo->prepare($sqlDetalle);
            $stmtDetalle->execute([$idReserva, $idHabitacion]);
        }
        $pdo->commit(); // --- FIN EXITOSO DE TRANSACCIÓN ---
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack(); // --- ROLLBACK: SE DESHACEN TODOS LOS CAMBIOS ---
        error_log("Error al crear solicitud de reserva: " . $e->getMessage());
        return false;
    }
}

/**
 * El personal confirma una reserva pendiente cambiando su estado.
 */
function confirmarReserva($pdo, $idReserva, $dniPersonal) {
    $idEstadoConfirmado = 1; // 1 = Confirmada
    $sql = "UPDATE reserva SET idEstadoReserva = ?, DNIPersonalHotel = ? WHERE idReserva = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$idEstadoConfirmado, $dniPersonal, $idReserva]);
}

function obtenerReservasPorCliente($pdo, $dniCliente) {
    $sql = "SELECT r.idReserva, r.fechaReserva, r.fechaDesde, r.fechaHasta, h.descripcion AS habitacionDescripcion, th.nombreTipoHabitacion, er.nombreEstadoReserva 
            FROM reserva r 
            JOIN detallereserva dr ON r.idReserva = dr.idReserva 
            JOIN habitacion h ON dr.idHabitacion = h.idHabitacion 
            JOIN tipohabitacion th ON h.idTipoHabitacion = th.idTipoHabitacion 
            JOIN estadoReserva er ON r.idEstadoReserva = er.idEstadoReserva 
            WHERE r.DNICliente = ? 
            ORDER BY r.fechaReserva DESC, r.horaReserva DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dniCliente]);
    return $stmt->fetchAll();
}

/**
 * Muestra todas las reservas, poniendo las pendientes primero.
 */
function obtenerTodasLasReservas($pdo) {
    $sql = "SELECT 
                r.idReserva, r.DNICliente, c.nombre AS nombreCliente, c.apellido AS apellidoCliente, 
                r.fechaDesde, r.fechaHasta, er.nombreEstadoReserva, er.idEstadoReserva, 
                GROUP_CONCAT(dr.idHabitacion SEPARATOR ', ') as habitaciones_reservadas 
            FROM reserva r 
            LEFT JOIN cliente c ON r.DNICliente = c.DNICliente 
            JOIN estadoReserva er ON r.idEstadoReserva = er.idEstadoReserva 
            LEFT JOIN detallereserva dr ON r.idReserva = dr.idReserva 
            GROUP BY r.idReserva 
            ORDER BY FIELD(r.idEstadoReserva, 4) DESC, r.fechaReserva DESC";
    return $pdo->query($sql)->fetchAll();
}

function obtenerDetallesDeUnaReserva($pdo, $idReserva) {
    $sql_reserva = "SELECT r.*, c.nombre, c.apellido, c.mail, c.telefono, er.nombreEstadoReserva 
                    FROM reserva r
                    JOIN cliente c ON r.DNICliente = c.DNICliente
                    JOIN estadoReserva er ON r.idEstadoReserva = er.idEstadoReserva
                    WHERE r.idReserva = ?";
    $stmt_reserva = $pdo->prepare($sql_reserva);
    $stmt_reserva->execute([$idReserva]);
    $reserva_maestra = $stmt_reserva->fetch();

    if (!$reserva_maestra) { return false; }

    $sql_detalles = "SELECT h.idHabitacion, h.descripcion, h.piso, th.nombreTipoHabitacion
                     FROM detallereserva dr
                     JOIN habitacion h ON dr.idHabitacion = h.idHabitacion
                     JOIN tipohabitacion th ON h.idTipoHabitacion = th.idTipoHabitacion
                     WHERE dr.idReserva = ?";
    $stmt_detalles = $pdo->prepare($sql_detalles);
    $stmt_detalles->execute([$idReserva]);
    $habitaciones_detalle = $stmt_detalles->fetchAll();

    return [
        'reserva' => $reserva_maestra,
        'habitaciones' => $habitaciones_detalle
    ];
}
?>