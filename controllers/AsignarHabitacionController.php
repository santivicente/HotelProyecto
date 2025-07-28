<?php
<?php
require_once __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('personal');

require_once __DIR__ . '/../models/Db.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Habitacion.php';

$idReserva = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$idReserva) { 
    header('Location: /hotelProyecto/index.php?controller=reserva'); 
    exit(); 
}

// --- LÓGICA PARA PROCESAR EL FORMULARIO DE ASIGNACIÓN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idHabitacionAsignar = isset($_POST['idHabitacion']) ? (int)$_POST['idHabitacion'] : 0;
    $dniPersonal = $_SESSION['DNIPersonal'];
    if ($idHabitacionAsignar > 0) {
        if (asignarHabitacionAReserva($pdo, $idReserva, $idHabitacionAsignar, $dniPersonal)) {
            header('Location: /hotelProyecto/index.php?controller=reserva&exito=asignada');
            exit();
        } else {
            $error = "Error al asignar la habitación. Puede que ya no esté disponible.";
        }
    } else {
        $error = "Debes seleccionar una habitación para asignar.";
    }
}

// --- LÓGICA PARA CARGAR DATOS PARA LA VISTA ---
$reserva = obtenerDetallesDeUnaReserva($pdo, $idReserva);
if (!$reserva || $reserva['idEstadoReserva'] != 4) { // Si no existe o no está pendiente
    header('Location: /hotelProyecto/index.php?controller=reserva');
    exit();
}
$habitacionesLibres = buscarHabitacionesLibresParaAsignar($pdo, $reserva['idTipoHabitacion'], $reserva['fechaDesde'], $reserva['fechaHasta']);

include __DIR__ . '/../views/habitacion/asignar.php';
?>