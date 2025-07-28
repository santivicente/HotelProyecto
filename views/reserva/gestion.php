<?php

// ARCHIVO: vistas/vista_gestion_reservas.php
include __DIR__ . '/../layout/header.php'; 
?>

<div class="contenedor">
    <div class="panel">
        <aside class="menu-lateral">
            <h3>Menú Personal</h3>
            <a href="/hotelProyecto/index.php?controller=panelPersonal">Dashboard</a>
            <a href="/hotelProyecto/index.php?controller=reserva" style="background:#0779e4;">Gestionar Reservas</a>
            <a href="/hotelProyecto/index.php?controller=habitaciones">Gestionar Habitaciones</a>
            <a href="/hotelProyecto/index.php?controller=personal">Gestionar Personal</a>
            <a href="/hotelProyecto/index.php?controller=estadisticas">Estadísticas</a>
        </aside>
        <main class="contenido-principal">
            <h2>Gestión de Todas las Reservas</h2>
            <?php if (isset($_GET['exito'])): ?><p class="alerta alerta-exito">Operación realizada con éxito.</p><?php endif; ?>

            <!-- Búsqueda de reservas por DNI (AJAX) -->
            <div style="margin-bottom: 25px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <label for="dni_busqueda"><b>Buscar reservas por DNI:</b></label>
                <input type="text" id="dni_busqueda" autocomplete="off" style="margin-left:10px;">
                <div id="reservas_cliente" style="margin-top:10px;"></div>
            </div>
            <!-- Fin búsqueda AJAX -->

            <!-- Filtro original por fecha y estado -->
            <form method="GET" action="/hotelProyecto/index.php" class="filtros-reservas" style="margin-bottom:20px; display:flex; gap:20px; align-items:flex-end;">
                <input type="hidden" name="controller" value="reserva">
                <div>
                    <label for="fecha_reserva">Fecha de Reserva:</label>
                    <input type="date" id="fecha_reserva" name="fecha_reserva" value="<?php echo isset($_GET['fecha_reserva']) ? htmlspecialchars($_GET['fecha_reserva']) : ''; ?>">
                </div>
                <div>
                    <label for="estado_reserva">Estado de Reserva:</label>
                    <select id="estado_reserva" name="estado_reserva">
                        <option value="">-- Todos --</option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?php echo $estado['idEstadoReserva']; ?>" <?php echo (isset($_GET['estado_reserva']) && $_GET['estado_reserva'] == $estado['idEstadoReserva']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($estado['nombreEstadoReserva']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit">Filtrar</button>
                </div>
            </form>
            <!-- Fin filtro original -->

            <table class="tabla">
                <thead>
                    <tr>
                        <th>ID Reserva</th>
                        <th>Cliente</th>
                        <th>Tipo Solicitado</th>
                        <th>Fechas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($todas_las_reservas as $reserva): ?>
                    <tr style="<?php echo ($reserva['idEstadoReserva'] == 4) ? 'background-color: #fffbe6;' : ''; ?>">
                        <td>#<?php echo htmlspecialchars($reserva['idReserva']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['nombreCliente'] . ' ' . $reserva['apellidoCliente']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['nombreTipoHabitacion']); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($reserva['fechaDesde'])) . " - " . date("d/m/Y", strtotime($reserva['fechaHasta'])); ?></td>
                        <td>
                            <span style="font-weight: bold; color: <?php echo ($reserva['idEstadoReserva'] == 4) ? '#f0ad4e' : '#5cb85c'; ?>;">
                                <?php echo htmlspecialchars($reserva['nombreEstadoReserva']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($reserva['idEstadoReserva'] == 4): // Si está pendiente ?>
                                <a href="/hotelProyecto/index.php?controller=asignarHabitacion&id=<?php echo $reserva['idReserva']; ?>" style="background: #5cb85c; color: white; padding: 5px 10px; border-radius: 3px; text-decoration:none;">Asignar y Confirmar</a>
                            <?php else: ?>
                                <a href="/hotelProyecto/index.php?controller=detalleReserva&id=<?php echo $reserva['idReserva']; ?>">Ver Detalles</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
document.getElementById('dni_busqueda').addEventListener('input', function() {
    console.log('Buscando DNI:', this.value);
    var dni = this.value;
    var div = document.getElementById('reservas_cliente');
    if (dni.length >= 6) {
        fetch('/hotelProyecto/controllers/ajax_reservas_cliente.php?dni=' + dni)
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta AJAX:', data);
                if (data.length > 0) {
                    let html = '<table class="tabla"><thead><tr><th>ID</th><th>Cliente</th><th>Fecha Reserva</th><th>Desde</th><th>Hasta</th><th>Habitación</th><th>Estado</th></tr></thead><tbody>';
                    data.forEach(function(res) {
                        html += '<tr>' +
                            '<td>' + res.idReserva + '</td>' +
                            '<td>' + (res.nombreCliente ? res.nombreCliente + " " + res.apellidoCliente : "-") + '</td>' +
                            '<td>' + res.fechaReserva + '</td>' +
                            '<td>' + res.fechaDesde + '</td>' +
                            '<td>' + res.fechaHasta + '</td>' +
                            '<td>' + (res.habitacion || "-") + '</td>' +
                            '<td>' + res.nombreEstadoReserva + '</td>' +
                            '</tr>';
                    });
                    html += '</tbody></table>';
                    div.innerHTML = html;
                } else {
                    div.innerHTML = '<span style="color:red;">No se encontraron reservas para ese DNI.</span>';
                }
            });
    } else {
        div.innerHTML = '';
    }
});
</script>

<script>
document.getElementById('dni_busqueda').addEventListener('input', function() {
    var dni = this.value;
    var div = document.getElementById('reservas_cliente');
    if (dni.length >= 6) {
        fetch('/hotelProyecto/controllers/ajax_reservas_cliente.php?dni=' + dni)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let html = '<table class="tabla"><thead><tr><th>ID</th><th>Cliente</th><th>Fecha Reserva</th><th>Desde</th><th>Hasta</th><th>Habitación</th><th>Estado</th></tr></thead><tbody>';
                    data.forEach(function(res) {
                        html += '<tr>' +
                            '<td>' + res.idReserva + '</td>' +
                            '<td>' + (res.nombreCliente ? res.nombreCliente + " " + res.apellidoCliente : "-") + '</td>' +
                            '<td>' + res.fechaReserva + '</td>' +
                            '<td>' + res.fechaDesde + '</td>' +
                            '<td>' + res.fechaHasta + '</td>' +
                            '<td>' + (res.habitacion || "-") + '</td>' +
                            '<td>' + res.nombreEstadoReserva + '</td>' +
                            '</tr>';
                    });
                    html += '</tbody></table>';
                    div.innerHTML = html;
                } else {
                    div.innerHTML = '<span style="color:red;">No se encontraron reservas para ese DNI.</span>';
                }
            });
    } else {
        div.innerHTML = '';
    }
});
</script>