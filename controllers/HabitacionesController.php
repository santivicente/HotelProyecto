<?php

require __DIR__ . '/../models/Db.php';
require __DIR__ . '/../models/Habitacion.php';

$tipos_habitacion = obtenerTiposDeHabitacion($pdo);

include __DIR__ . '/../views/layout/header.php';
?>

<div class="contenedor">
    <h2>Nuestros Tipos de Habitación</h2>
    <p>Elige el estilo que mejor se adapte a tu viaje. Cada uno ofrece una experiencia única.</p>

    <div class="habitacion-grid">
        <?php if (empty($tipos_habitacion)): ?>
            <p>No hay tipos de habitación disponibles en este momento.</p>
        <?php else: ?>
            <?php foreach ($tipos_habitacion as $tipo): ?>
                <div class="habitacion-card">
                    <img src="img/habitaciones/<?php echo htmlspecialchars($tipo['imagen_tipo']); ?>" alt="<?php echo htmlspecialchars($tipo['nombreTipoHabitacion']); ?>">
                    <div class="habitacion-card-contenido">
                        <h3><?php echo htmlspecialchars($tipo['nombreTipoHabitacion']); ?></h3>
                        <p><?php echo htmlspecialchars($tipo['descripcion_ejemplo']); ?></p>
                        <p><strong>Precio desde:</strong> $<?php echo htmlspecialchars(number_format($tipo['precio'], 2)); ?> / noche</p>
                        <a href="/hotelProyecto/index.php?controller=verTipoHabitacion&tipo=<?php echo htmlspecialchars($tipo['idTipoHabitacion']); ?>" class="btn-reservar">Ver Disponibilidad</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../views/layout/footer.php'; ?>