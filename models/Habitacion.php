<?php


function obtenerTiposDeHabitacion($pdo) {
    $sql = "SELECT th.idTipoHabitacion, th.nombreTipoHabitacion, MIN(h.descripcion) as descripcion_ejemplo, MIN(p.precio) as precio, imagen_tipo 
            FROM tipohabitacion th 
            LEFT JOIN habitacion h ON th.idTipoHabitacion = h.idTipoHabitacion 
            LEFT JOIN preciohabitacion p ON th.idTipoHabitacion = p.idTipoHabitacion 
            GROUP BY th.idTipoHabitacion, th.nombreTipoHabitacion 
            ORDER BY MIN(p.precio) ASC";
    return $pdo->query($sql)->fetchAll();
}

function obtenerDetallesTipoHabitacion($pdo, $idTipo) {
    $sql = "SELECT nombreTipoHabitacion FROM tipohabitacion WHERE idTipoHabitacion = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idTipo]);
    return $stmt->fetch();
}

function verificarDisponibilidadPorTipo($pdo, $idTipo, $fechaDesde, $fechaHasta) {
    try {
        $sql = "SELECT EXISTS (
                    SELECT 1
                    FROM habitacion h
                    WHERE h.idTipoHabitacion = ? AND h.idEstadoHabitacion = 1
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM detallereserva dr
                        JOIN reserva r ON dr.idReserva = r.idReserva
                        WHERE dr.idHabitacion = h.idHabitacion 
                        AND r.fechaDesde < ? AND r.fechaHasta > ?
                    )
                ) as hay_disponibilidad";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idTipo, $fechaHasta, $fechaDesde]);
        return (bool)$stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error al verificar disponibilidad: " . $e->getMessage());
        return false;
    }
}

function buscarHabitacionesLibresParaAsignar($pdo, $idTipo, $fechaDesde, $fechaHasta) {
    $sql = "SELECT * FROM habitacion
            WHERE idTipoHabitacion = ? AND idEstadoHabitacion = 1
            AND idHabitacion NOT IN (
                SELECT dr.idHabitacion 
                FROM detallereserva dr
                JOIN reserva r ON dr.idReserva = r.idReserva
                WHERE r.fechaDesde < ? AND r.fechaHasta > ?
            )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idTipo, $fechaHasta, $fechaDesde]);
    return $stmt->fetchAll();
}

function buscarHabitacionesDisponiblesPorTipo($pdo, $idTipo, $fechaDesde, $fechaHasta) {
    $sql = "SELECT h.*, th.nombreTipoHabitacion 
            FROM habitacion h 
            JOIN tipohabitacion th ON h.idTipoHabitacion = th.idTipoHabitacion 
            WHERE h.idTipoHabitacion = ? AND h.idEstadoHabitacion = 1 
            AND NOT EXISTS (
                SELECT 1 FROM detallereserva dr 
                JOIN reserva r ON dr.idReserva = r.idReserva 
                WHERE dr.idHabitacion = h.idHabitacion 
                AND r.fechaDesde < ? AND r.fechaHasta > ?
            )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idTipo, $fechaHasta, $fechaDesde]);
    return $stmt->fetchAll();
}

function obtenerTiposDeHabitacionResumido($pdo) {
    return $pdo->query("SELECT idTipoHabitacion, nombreTipoHabitacion FROM tipohabitacion ORDER BY nombreTipoHabitacion")->fetchAll();
}

function obtenerTodasLasHabitacionesIndividuales($pdo) {
    $hoy = date('Y-m-d');
    $sql = "SELECT 
                h.*, 
                th.nombreTipoHabitacion, 
                eh.nombreEstadoHabitacion,
                (EXISTS (
                    SELECT 1 
                    FROM detallereserva dr
                    JOIN reserva r ON dr.idReserva = r.idReserva
                    WHERE dr.idHabitacion = h.idHabitacion AND r.fechaHasta >= ?
                )) AS tiene_reservas_futuras
            FROM habitacion h
            LEFT JOIN tipohabitacion th ON h.idTipoHabitacion = th.idTipoHabitacion
            LEFT JOIN estadohabitacion eh ON h.idEstadoHabitacion = eh.idEstadoHabitacion
            ORDER BY h.idHabitacion ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hoy]);
    return $stmt->fetchAll();
}

function obtenerHabitacionPorId($pdo, $idHabitacion) {
    $stmt = $pdo->prepare("SELECT * FROM habitacion WHERE idHabitacion = ?");
    $stmt->execute([$idHabitacion]);
    return $stmt->fetch();
}

function obtenerEstadosDeHabitacion($pdo) {
    return $pdo->query("SELECT * FROM estadohabitacion ORDER BY nombreEstadoHabitacion")->fetchAll();
}

// MODIFICADO: sin imágenes
function crearHabitacion($pdo, $capacidad, $descripcion, $piso, $idTipo, $idEstado) {
    $sql = "INSERT INTO habitacion (capacidad, descripcion, piso, idTipoHabitacion, idEstadoHabitacion) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$capacidad, $descripcion, $piso, $idTipo, $idEstado]);
}

// MODIFICADO: sin imágenes
function actualizarHabitacion($pdo, $id, $capacidad, $descripcion, $piso, $idTipo, $idEstado) {
    $sql = "UPDATE habitacion SET 
                capacidad = ?, descripcion = ?, piso = ?, 
                idTipoHabitacion = ?, idEstadoHabitacion = ?
            WHERE idHabitacion = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$capacidad, $descripcion, $piso, $idTipo, $idEstado, $id]);
}

function obtenerTodasLasHabitaciones($pdo) {
    $hoy = date('Y-m-d');
    $sql = "SELECT 
                h.*, 
                th.nombreTipoHabitacion, 
                eh.nombreEstadoHabitacion,
                (EXISTS (
                    SELECT 1 
                    FROM detallereserva dr
                    JOIN reserva r ON dr.idReserva = r.idReserva
                    WHERE dr.idHabitacion = h.idHabitacion 
                      AND r.fechaDesde > ?
                      AND r.idEstadoReserva IN (1,4,5)
                )) AS tiene_reservas_futuras
            FROM habitacion h
            LEFT JOIN tipohabitacion th ON h.idTipoHabitacion = th.idTipoHabitacion
            LEFT JOIN estadohabitacion eh ON h.idEstadoHabitacion = eh.idEstadoHabitacion
            ORDER BY h.idHabitacion ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hoy]);
    $habitaciones = $stmt->fetchAll();

    // Convertir la bandera a booleano
    foreach ($habitaciones as &$hab) {
        $hab['tiene_reservas_futuras'] = (bool)$hab['tiene_reservas_futuras'];
    }
    return $habitaciones;
}
?>