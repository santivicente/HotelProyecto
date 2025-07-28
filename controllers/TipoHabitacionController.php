<?php

require_once __DIR__ . '/../models/Db.php';
require_once __DIR__ . '/../models/Habitacion.php';

$idTipo = isset($_GET['tipo']) ? (int)$_GET['tipo'] : 0;
if (!$idTipo) {
    header('Location: /hotelProyecto/index.php?controller=habitaciones');
    exit();
}

$fechaDesde = isset($_POST['fechaDesde']) ? $_POST['fechaDesde'] : '';
$fechaHasta = isset($_POST['fechaHasta']) ? $_POST['fechaHasta'] : '';
$hayDisponibilidad = null;
$error = '';

$detallesTipo = obtenerDetallesTipoHabitacion($pdo, $idTipo);
if (!$detallesTipo) {
    header('Location: /hotelProyecto/index.php?controller=habitaciones');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'buscar') {
    if (empty($fechaDesde) || empty($fechaHasta)) {
        $error = "Debes seleccionar ambas fechas.";
    } elseif ($fechaDesde >= $fechaHasta) {
        $error = "La fecha de salida debe ser posterior a la de entrada.";
    } else {
        $hayDisponibilidad = verificarDisponibilidadPorTipo($pdo, $idTipo, $fechaDesde, $fechaHasta);
    }
}

include __DIR__ . '/../views/layout/header.php';
?>
<div class="contenedor">
    <a href="/hotelProyecto/index.php?controller=habitaciones" style="text-decoration: none; margin-bottom: 20px; display: inline-block;">← Volver a Tipos de Habitación</a>
    <h2><?php echo htmlspecialchars($detallesTipo['nombreTipoHabitacion']); ?></h2>
    <p>Selecciona las fechas de tu estancia para comprobar si tenemos disponibilidad para este tipo de habitación.</p>
    
    <div class="formulario" style="max-width: 600px; margin: 20px auto;">
        <form action="/hotelProyecto/index.php?controller=verTipoHabitacion&tipo=<?php echo $idTipo; ?>" method="post">
            <input type="hidden" name="accion" value="buscar">
            <div style="display: flex; gap: 20px; align-items: flex-end;">
                <div style="flex-grow: 1;">
                    <label for="fechaDesde">Fecha de Entrada:</label>
                    <input type="date" name="fechaDesde" id="fechaDesde" value="<?php echo htmlspecialchars($fechaDesde); ?>" required>
                </div>
                <div style="flex-grow: 1;">
                    <label for="fechaHasta">Fecha de Salida:</label>
                    <input type="date" name="fechaHasta" id="fechaHasta" value="<?php echo htmlspecialchars($fechaHasta); ?>" required>
                </div>
                <div><button type="submit">Comprobar</button></div>
            </div>
        </form>
    </div>

    <?php if ($error): ?>
        <p class="alerta alerta-error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($hayDisponibilidad === true): ?>
        <div class="alerta alerta-exito" style="margin-top:20px;">
            ¡Hay disponibilidad para las fechas seleccionadas!
            <form action="/hotelProyecto/index.php?controller=reservar" method="post" style="display:inline;">
                <input type="hidden" name="idTipoHabitacion" value="<?php echo $idTipo; ?>">
                <input type="hidden" name="fechaDesde" value="<?php echo htmlspecialchars($fechaDesde); ?>">
                <input type="hidden" name="fechaHasta" value="<?php echo htmlspecialchars($fechaHasta); ?>">
                <button type="submit" style="margin-left:15px;">Reservar Ahora</button>
            </form>
        </div>
    <?php elseif ($hayDisponibilidad === false): ?>
        <div class="alerta alerta-error" style="margin-top:20px;">
            Lo sentimos, no hay disponibilidad para este tipo de habitación en las fechas seleccionadas.
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../views/layout/footer.php'; ?>