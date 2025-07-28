<?php 
// ARCHIVO: vistas/vista_detalle_reserva.php
include __DIR__ . '/../layout/header.php'; 
?>

<div class="contenedor">
    <a href="<?php echo dirname(__DIR__, 2) . '/controllers/ReservaController.php'; ?>" style="text-decoration: none; margin-bottom: 20px; display: inline-block;">← Volver a Todas las Reservas</a>
    
    <h2>Detalles de la Reserva #<?php echo htmlspecialchars($detalles_reserva['reserva']['idReserva']); ?></h2>

    <div class="panel">
        <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h3>Datos del Cliente</h3>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($detalles_reserva['reserva']['nombre'] . ' ' . $detalles_reserva['reserva']['apellido']); ?></p>
            <p><strong>DNI:</strong> <?php echo htmlspecialchars($detalles_reserva['reserva']['DNICliente']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($detalles_reserva['reserva']['mail']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($detalles_reserva['reserva']['telefono']); ?></p>
        </div>
        
        <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h3>Datos de la Reserva</h3>
            <p><strong>Fechas:</strong> del <?php echo date("d/m/Y", strtotime($detalles_reserva['reserva']['fechaDesde'])); ?> al <?php echo date("d/m/Y", strtotime($detalles_reserva['reserva']['fechaHasta'])); ?></p>
            <p><strong>Fecha de Operación:</strong> <?php echo date("d/m/Y H:i", strtotime($detalles_reserva['reserva']['fechaReserva'])); ?></p>
            <p><strong>Estado:</strong> <?php echo htmlspecialchars($detalles_reserva['reserva']['nombreEstadoReserva']); ?></p>
        </div>
    </div>

    <h3 style="margin-top: 30px;">Habitaciones Incluidas en esta Reserva</h3>
    <table class="tabla">
        <thead>
            <tr>
                <th>N° Habitación</th>
                <th>Tipo</th>
                <th>Piso</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($detalles_reserva['habitaciones'] as $habitacion): ?>
            <tr>
                <td><?php echo htmlspecialchars($habitacion['idHabitacion']); ?></td>
                <td><?php echo htmlspecialchars($habitacion['nombreTipoHabitacion']); ?></td>
                <td><?php echo htmlspecialchars($habitacion['piso']); ?></td>
                <td><?php echo htmlspecialchars($habitacion['descripcion']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>