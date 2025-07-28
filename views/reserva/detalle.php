<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="contenido-caja">
    <h2>Confirmar tu Reserva</h2>
    <p>Estás a un paso de reservar tu habitación. Por favor, selecciona las fechas de tu estancia.</p>

    <?php if ($error): ?><p class="alerta alerta-error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    
    <?php if ($exito): ?>
        <p class="alerta alerta-exito"><?php echo $exito; ?></p>
        <a href="/hotelProyecto/index.php?controller=misReservas" style="display:block; text-align:center;">Ver mis reservas</a>
    <?php else: ?>
        <form class="formulario" action="/hotelProyecto/index.php?controller=reservar&id=<?php echo htmlspecialchars($idHabitacion); ?>" method="post">
            <label for="fechaDesde">Fecha de Entrada:</label>
            <input type="date" name="fechaDesde" id="fechaDesde" required>

            <label for="fechaHasta">Fecha de Salida:</label>
            <input type="date" name="fechaHasta" id="fechaHasta" required>

            <button type="submit">Confirmar Reserva</button>
        </form>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>