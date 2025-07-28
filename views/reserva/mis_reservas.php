<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="contenedor">
    <div class="panel">
        <aside class="menu-lateral">
            <h3>Menú Cliente</h3>
            <a href="/hotelProyecto/index.php?controller=habitaciones">Nueva Reserva</a>
            <a href="/hotelProyecto/index.php?controller=misReservas" style="background:#0779e4;">Mis Reservas</a>
            <a href="/hotelProyecto/index.php?controller=perfil">Mi Perfil</a>
        </aside>
        <main class="contenido-principal">
            <h2>Mi Historial de Reservas</h2>

            <?php if (empty($reservas)): ?>
                <div class="alerta" style="background-color: #e9ecef;">
                    <p>Aún no has realizado ninguna reserva. ¿Por qué no exploras nuestras 
                        <a href="/hotelProyecto/index.php?controller=habitaciones">habitaciones</a>?
                    </p>
                </div>
            <?php else: ?>
                <p>Aquí puedes ver todas las reservas que has realizado con nosotros.</p>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Fecha de Operación</th>
                            <th>Habitación</th>
                            <th>Tipo</th>
                            <th>Fecha de Entrada</th>
                            <th>Fecha de Salida</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo date("d/m/Y H:i", strtotime($reserva['fechaReserva'])); ?></td>
                            <td><?php echo htmlspecialchars($reserva['habitacionDescripcion']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['nombreTipoHabitacion']); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($reserva['fechaDesde'])); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($reserva['fechaHasta'])); ?></td>
                            <td>
                                <span style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px;">
                                    <?php echo htmlspecialchars($reserva['nombreEstadoReserva']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>