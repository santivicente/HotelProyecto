<?php


require_once __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('personal');

require_once __DIR__ . '/../models/Db.php';

// --- Funciones de Estadísticas ---
function obtenerReservasPorMes($pdo) {
    $sql = "SELECT MONTHNAME(fechaReserva) AS mes, COUNT(*) as total
            FROM reserva
            GROUP BY mes
            ORDER BY MONTH(fechaReserva)";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function obtenerOcupacionPorHabitacion($pdo) {
    $sql = "SELECT h.descripcion, COUNT(dr.idHabitacion) as total_reservas
            FROM habitacion h
            LEFT JOIN detallereserva dr ON h.idHabitacion = dr.idHabitacion
            GROUP BY h.idHabitacion, h.descripcion
            ORDER BY total_reservas DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function obtenerTopClientes($pdo) {
    $sql = "SELECT c.nombre, c.apellido, COUNT(r.idReserva) as total_reservas
            FROM cliente c
            JOIN reserva r ON c.DNICliente = r.DNICliente
            GROUP BY c.DNICliente, c.nombre, c.apellido
            ORDER BY total_reservas DESC
            LIMIT 5";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}
$topClientes = obtenerTopClientes($pdo);

// --- ESTADÍSTICA: Cantidad de reservas por cliente usando función almacenada ---
function obtenerCantidadReservasPorCliente($pdo) {
    $sql = "SELECT nombre, apellido, DNICliente FROM cliente";
    $stmt = $pdo->query($sql);
    $clientes = $stmt->fetchAll();
    $resultados = [];
    foreach ($clientes as $cliente) {
        $stmt2 = $pdo->prepare("SELECT fn_cantidad_reservas_cliente(:dni) AS cantidad");
        $stmt2->execute([':dni' => $cliente['DNICliente']]);
        $cantidad = $stmt2->fetchColumn();
        $resultados[] = [
            'nombre' => $cliente['nombre'],
            'apellido' => $cliente['apellido'],
            'dni' => $cliente['DNICliente'],
            'cantidad' => $cantidad
        ];
    }
    return $resultados;
}
$cantReservasPorCliente = obtenerCantidadReservasPorCliente($pdo);

// --- Obtención de datos ---
$reservasMes = obtenerReservasPorMes($pdo);
$ocupacionHabitacion = obtenerOcupacionPorHabitacion($pdo);

// Incluimos la vista
include __DIR__ . '/../views/estadisticas/index.php';
?>