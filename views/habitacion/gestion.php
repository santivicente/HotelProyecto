<?php 
include __DIR__ . '/../layout/header.php'; 
?>
<div class="contenedor">
    <div class="panel">
        <aside class="menu-lateral">
            <h3>Menú Personal</h3>
            <a href="/hotelProyecto/index.php?controller=panelPersonal">Dashboard</a>
            <a href="/hotelProyecto/index.php?controller=reserva">Gestionar Reservas</a>
            <a href="/hotelProyecto/index.php?controller=habitacionABM" style="background:#0779e4;">Gestionar Habitaciones</a>
            <a href="/hotelProyecto/index.php?controller=personal">Gestionar Personal</a>
            <a href="/hotelProyecto/index.php?controller=estadisticas">Estadísticas</a>
        </aside>
        <main class="contenido-principal">
            <h2>Gestión de Habitaciones</h2>
            <a href="/hotelProyecto/index.php?controller=habitacionABM&accion=alta" style="display:inline-block; margin-bottom: 20px; padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;">+ Añadir Nueva Habitación</a>
            <h3>Listado de Todas las Habitaciones</h3>
             <table class="tabla">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Tipo</th>
                        <th>Piso</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_habitaciones as $hab): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hab['idHabitacion']); ?></td>
                        <td><?php echo htmlspecialchars($hab['nombreTipoHabitacion']); ?></td>
                        <td><?php echo htmlspecialchars($hab['piso']); ?></td>
                        <td><?php echo htmlspecialchars($hab['nombreEstadoHabitacion']); ?></td>
                        <td>
                            <?php if ($hab['tiene_reservas_futuras']): ?>
                                <span style="color: #999; cursor: not-allowed;" title="No se puede editar porque tiene reservas futuras activas">No editable</span>
                            <?php else: ?>
                                <a href="/hotelProyecto/index.php?controller=habitacionABM&accion=editar&id=<?php echo $hab['idHabitacion']; ?>">Editar</a>
                                |
                                <a href="/hotelProyecto/index.php?controller=habitacionABM&accion=eliminar&id=<?php echo $hab['idHabitacion']; ?>"
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar esta habitación? Esta acción no se puede deshacer.');"
                                   style="color: red;">Eliminar</a>
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