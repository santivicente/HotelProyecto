<?php include __DIR__ . '/layout/header.php'; ?>

<div class="contenedor">
    <div class="panel">
        <aside class="menu-lateral">
            <h3>Menú Personal</h3>
            <a href="/hotelProyecto/index.php?controller=panelPersonal">Dashboard</a>
            <a href="/hotelProyecto/index.php?controller=reserva">Gestionar Reservas</a>
            <a href="/hotelProyecto/index.php?controller=habitacionABM">Gestionar Habitaciones</a>
            <a href="/hotelProyecto/index.php?controller=personal">Gestionar Personal</a>
            <a href="/hotelProyecto/index.php?controller=estadisticas">Estadísticas</a>
        </aside>
        <main class="contenido-principal">
            <h2>Buscar reservas por cliente (DNI)</h2>
            <form method="get" action="/hotelProyecto/index.php">
                <input type="hidden" name="controller" value="buscarReservasCliente">
                <label for="dni_busqueda"><b>Ingrese DNI del cliente:</b></label>
                <input type="text" id="dni_busqueda" name="dni" autocomplete="off" style="margin-left:10px;">
                <button type="submit">Buscar</button>
            </form>
            <?php if (isset($reservas_cliente)): ?>
                <div style="margin-top:20px;">
                    <?php if (empty($reservas_cliente)): ?>
                        <span style="color:red;">No se encontraron reservas para ese DNI.</span>
                    <?php else: ?>
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Fecha Reserva</th>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Habitación</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($reservas_cliente as $res): ?>
                                    <tr>
                                        <td><?php echo $res['idReserva']; ?></td>
                                        <td><?php echo $res['nombreCliente'] . " " . $res['apellidoCliente']; ?></td>
                                        <td><?php echo $res['fechaReserva']; ?></td>
                                        <td><?php echo $res['fechaDesde']; ?></td>
                                        <td><?php echo $res['fechaHasta']; ?></td>
                                        <td><?php echo $res['habitacion'] ?? '-'; ?></td>
                                        <td><?php echo $res['nombreEstadoReserva']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>