<?php
<form class="formulario" action="/hotelProyecto/index.php?controller=reservar" method="post">
    <label for="fechaDesde">Fecha de Entrada:</label>
    <input type="date" name="fechaDesde" id="fechaDesde" required>

    <label for="fechaHasta">Fecha de Salida:</label>
    <input type="date" name="fechaHasta" id="fechaHasta" required>

    <button type="submit">Buscar Habitaciones Disponibles</button>
</form>

<!-- Resultados de la búsqueda -->
<?php if (!empty($habitacionesDisponibles)): ?>
    <h3>Habitaciones Disponibles</h3>
    <table class="tabla">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Piso</th>
                <th>Capacidad</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($habitacionesDisponibles as $habitacion): ?>
            <tr>
                <td><?php echo htmlspecialchars($habitacion['nombreTipoHabitacion']); ?></td>
                <td><?php echo htmlspecialchars($habitacion['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($habitacion['piso']); ?></td>
                <td><?php echo htmlspecialchars($habitacion['capacidad']); ?></td>
                <td>
                    <form action="/hotelProyecto/index.php?controller=reservar" method="post">
                        <input type="hidden" name="accion" value="reservar">
                        <input type="hidden" name="idHabitacion" value="<?php echo $habitacion['idHabitacion']; ?>">
                        <input type="hidden" name="fechaDesde" value="<?php echo htmlspecialchars($fechaDesde); ?>">
                        <input type="hidden" name="fechaHasta" value="<?php echo htmlspecialchars($fechaHasta); ?>">
                        <button type="submit">Reservar Ahora</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>