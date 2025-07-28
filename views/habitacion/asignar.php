<?php 
// ARCHIVO: vistas/vista_asignar_habitacion.php
include __DIR__ . '/../layout/header.php'; 
?>
<div class="contenedor">
    <a href="/hotelProyecto/index.php?controller=reserva" style="text-decoration: none; margin-bottom: 20px; display: inline-block;">← Volver a Todas las Reservas</a>
    <h2>Asignar Habitación a Reserva #<?php echo htmlspecialchars($reserva['idReserva']); ?></h2>
    <div class="panel">
        <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px;">
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($reserva['nombre'] . ' ' . $reserva['apellido']); ?></p>
            <p><strong>Tipo Solicitado:</strong> <?php echo htmlspecialchars($reserva['nombreTipoHabitacion']); ?></p>
            <p><strong>Fechas:</strong> del <?php echo date("d/m/Y", strtotime($reserva['fechaDesde'])); ?> al <?php echo date("d/m/Y", strtotime($reserva['fechaHasta'])); ?></p>
        </div>
    </div>
    <div class="formulario" style="max-width:none; margin-top:30px;">
        <h3>Habitaciones Disponibles para Asignar</h3>
        <?php if (empty($habitacionesLibres)): ?>
            <p class="alerta alerta-error">¡Atención! No se encontraron habitaciones libres de este tipo para las fechas solicitadas. Es posible que haya un overbooking.</p>
        <?php else: ?>
            <form action="/hotelProyecto/index.php?controller=asignarHabitacion&id=<?php echo $reserva['idReserva']; ?>" method="POST">
                <label for="idHabitacion">Seleccione la habitación a asignar:</label>
                <select name="idHabitacion" id="idHabitacion" required>
                    <option value="">-- Habitaciones --</option>
                    <?php foreach($habitacionesLibres as $hab): ?>
                        <option value="<?php echo $hab['idHabitacion']; ?>">
                            Habitación #<?php echo htmlspecialchars($hab['idHabitacion']); ?> (Piso <?php echo htmlspecialchars($hab['piso']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Asignar y Confirmar Reserva</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>