<?php

// ARCHIVO: reservar.php
require_once __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('cliente');

require_once __DIR__ . '/../models/db.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Habitacion.php'; 

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idTipoHabitacion = isset($_POST['idTipoHabitacion']) ? (int)$_POST['idTipoHabitacion'] : 0;
    $fechaDesde = isset($_POST['fechaDesde']) ? $_POST['fechaDesde'] : null;
    $fechaHasta = isset($_POST['fechaHasta']) ? $_POST['fechaHasta'] : null;
    $dniCliente = isset($_SESSION['DNICliente']) ? $_SESSION['DNICliente'] : null;

    if (empty($idTipoHabitacion) || empty($fechaDesde) || empty($fechaHasta) || empty($dniCliente)) {
        $error = "Faltan datos para procesar la solicitud. Por favor, inténtelo de nuevo.";
    } else {
        $habitacionesLibres = buscarHabitacionesLibresParaAsignar($pdo, $idTipoHabitacion, $fechaDesde, $fechaHasta);

        if (empty($habitacionesLibres)) {
            $error = "Lo sentimos, en el último momento la disponibilidad ha cambiado. No quedan habitaciones de ese tipo.";
        } else {
            $idHabitacionPlaceholder = $habitacionesLibres[0]['idHabitacion'];
            if (crearReservaCompleta($pdo, $dniCliente, [$idHabitacionPlaceholder], $fechaDesde, $fechaHasta)) {
                $exito = "¡Tu solicitud de reserva ha sido enviada con éxito! El personal del hotel la confirmará a la brevedad.";
            } else {
                $error = "Ocurrió un error al procesar tu solicitud. Por favor, inténtalo de nuevo.";
            }
        }
    }
} else {
    header('Location: /hotelProyecto/index.php?controller=habitaciones');
    exit();
}

include_once __DIR__ . '/../views/layout/header.php';
?>
<div class="contenedor">
    <div class="formulario" style="text-align: center;">
        <?php if ($exito): ?>
            <h2>¡Solicitud Enviada!</h2>
            <p class="alerta alerta-exito"><?php echo htmlspecialchars($exito); ?></p>
            <a href="/hotelProyecto/index.php?controller=misReservas" class="btn-principal" style="background-color: #0779e4; text-decoration: none; display:inline-block; margin: 5px;">Ver estado de mis reservas</a>
        <?php else: ?>
            <h2>Error en la Solicitud</h2>
            <p class="alerta alerta-error"><?php echo htmlspecialchars($error); ?></p>
            <a href="/hotelProyecto/index.php?controller=habitaciones" class="btn-principal" style="background-color: #6c757d; text-decoration: none; display:inline-block; margin: 5px;">Volver a intentarlo</a>
        <?php endif; ?>
    </div>
</div>
<?php include_once __DIR__ . '/../views/layout/footer.php'; ?>