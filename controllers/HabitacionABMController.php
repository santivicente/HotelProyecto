<?php

require_once __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('personal');

require_once __DIR__ . '/../models/Db.php';
require_once __DIR__ . '/../models/Habitacion.php';

$accion = $_GET['accion'] ?? '';
$idHabitacion = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$error = '';
$exito = '';
$habitacion = null;

// --- Lógica para eliminar habitación ---
if ($accion === 'eliminar' && $idHabitacion > 0) {
    $habitacionEliminar = obtenerHabitacionPorId($pdo, $idHabitacion);
    if ($habitacionEliminar) {
        // Verifica reservas futuras
        $hoy = date('Y-m-d');
        $sql = "SELECT COUNT(*) FROM detallereserva dr
                JOIN reserva r ON dr.idReserva = r.idReserva
                WHERE dr.idHabitacion = ? AND r.fechaDesde > ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idHabitacion, $hoy]);
        $tieneFuturas = $stmt->fetchColumn() > 0;

        if (!$tieneFuturas) {
            $stmt = $pdo->prepare("DELETE FROM habitacion WHERE idHabitacion = ?");
            $stmt->execute([$idHabitacion]);
            header('Location: /hotelProyecto/index.php?controller=habitacionABM&exito=eliminada');
            exit();
        } else {
            $error = "No se puede eliminar la habitación porque tiene reservas futuras.";
        }
    }
}

// --- Lógica para procesar el envío de formularios ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['idHabitacion'] ?? 0);
    $capacidad = (int)($_POST['capacidad'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $piso = (int)($_POST['piso'] ?? 0);
    $idTipoHabitacion = (int)($_POST['idTipoHabitacion'] ?? 0);
    $idEstadoHabitacion = (int)($_POST['idEstadoHabitacion'] ?? 0);

    // Detectar si es AJAX
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($id === 0) { // Crear
        $ok = crearHabitacion($pdo, $capacidad, $descripcion, $piso, $idTipoHabitacion, $idEstadoHabitacion);
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $ok,
                'message' => $ok ? 'Habitación creada correctamente.' : 'Error al crear la nueva habitación.'
            ]);
            exit();
        } else {
            if ($ok) {
                header('Location: /hotelProyecto/index.php?controller=habitacionABM&exito=creada');
                exit();
            } else {
                $error = "Error al crear la nueva habitación.";
                $accion = 'alta'; // Volver al formulario de alta con error
            }
        }
    } else { // Actualizar
        $ok = actualizarHabitacion($pdo, $id, $capacidad, $descripcion, $piso, $idTipoHabitacion, $idEstadoHabitacion);
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $ok,
                'message' => $ok ? 'Habitación actualizada correctamente.' : 'Error al actualizar la habitación.'
            ]);
            exit();
        } else {
            if ($ok) {
                header('Location: /hotelProyecto/index.php?controller=habitacionABM&exito=editada');
                exit();
            } else {
                $error = "Error al actualizar la habitación.";
                $accion = 'editar';
                $idHabitacion = $id;
            }
        }
    }
    // ¡IMPORTANTE! Si fue AJAX, ya hicimos exit() arriba, así que nada más se ejecuta aquí.
}

// --- Flujo de vistas ---
$tipos_habitacion = obtenerTiposDeHabitacionResumido($pdo);
$estados_habitacion = obtenerEstadosDeHabitacion($pdo);

if ($accion === 'alta') {
    // Formulario vacío para alta
    $habitacion = [];
    include __DIR__ . '/../views/habitacion/form.php';
} elseif ($accion === 'editar' && $idHabitacion > 0) {
    // Formulario con datos para edición
    $habitacion = obtenerHabitacionPorId($pdo, $idHabitacion);
    if (!$habitacion) {
        header('Location: /hotelProyecto/index.php?controller=habitacionABM&error=no_encontrada');
        exit();
    }
    include __DIR__ . '/../views/habitacion/form.php';
} else {
    // Listado de habitaciones (por defecto)
    $lista_habitaciones = obtenerTodasLasHabitaciones($pdo);
    include __DIR__ . '/../views/habitacion/gestion.php';
}

