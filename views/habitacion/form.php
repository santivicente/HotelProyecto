<?php

include __DIR__ . '/../layout/header.php'; ?>

<div class="contenedor">
    <h2><?php echo isset($habitacion['idHabitacion']) ? 'Editar Habitación' : 'Añadir Nueva Habitación'; ?></h2>

    <a href="/hotelProyecto/index.php?controller=habitacionABM" 
       style="display:inline-block; margin: 18px 0 30px 0; padding: 10px 18px; background: #0779e4; color: #fff; border-radius: 5px; text-decoration: none; font-weight: 500;">
        ← Volver a la Gestión de Habitaciones
    </a>

    <div class="form-contenedor" style="max-width: 800px; margin: 20px auto;">
        <form id="formHabitacion" class="formulario" method="post" action="/hotelProyecto/index.php?controller=habitacionABM&accion=<?php echo isset($habitacion['idHabitacion']) ? 'editar&id=' . $habitacion['idHabitacion'] : 'alta'; ?>" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">
            <?php if (isset($habitacion['idHabitacion'])): ?>
                <input type="hidden" name="idHabitacion" value="<?php echo htmlspecialchars($habitacion['idHabitacion']); ?>">
            <?php endif; ?>

            <label for="idTipoHabitacion" style="display:block; margin-top:10px;">Tipo de Habitación:</label>
            <select name="idTipoHabitacion" id="idTipoHabitacion" required style="width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;">
                <?php foreach ($tipos_habitacion as $tipo): ?>
                    <option value="<?php echo $tipo['idTipoHabitacion']; ?>" <?php echo (isset($habitacion['idTipoHabitacion']) && $habitacion['idTipoHabitacion'] == $tipo['idTipoHabitacion']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tipo['nombreTipoHabitacion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="descripcion" style="display:block; margin-top:10px;">Descripción:</label>
            <textarea name="descripcion" id="descripcion" rows="4" required style="width:100%; padding:12px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc; box-sizing:border-box;"><?php echo htmlspecialchars($habitacion['descripcion'] ?? ''); ?></textarea>

            <div class="form-row" style="display:flex; gap:20px;">
                <div style="flex:1;">
                    <label for="capacidad">Capacidad:</label>
                    <input type="number" name="capacidad" id="capacidad" min="1" required value="<?php echo htmlspecialchars($habitacion['capacidad'] ?? ''); ?>" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                </div>
                <div style="flex:1;">
                    <label for="piso">Piso:</label>
                    <input type="number" name="piso" id="piso" min="1" required value="<?php echo htmlspecialchars($habitacion['piso'] ?? ''); ?>" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                </div>
            </div>

            <label for="idEstadoHabitacion" style="display:block; margin-top:10px;">Estado:</label>
            <select name="idEstadoHabitacion" id="idEstadoHabitacion" required style="width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;">
                <?php foreach ($estados_habitacion as $estado): ?>
                    <option value="<?php echo $estado['idEstadoHabitacion']; ?>" <?php echo (isset($habitacion['idEstadoHabitacion']) && $habitacion['idEstadoHabitacion'] == $estado['idEstadoHabitacion']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($estado['nombreEstadoHabitacion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-guardar" style="background:#0779e4; color:#fff; padding:12px 25px; border:none; border-radius:5px; margin-top:10px; cursor:pointer;">Guardar Cambios</button>
            <div id="mensajeAjax" style="margin-top:15px;"></div>
        </form>
    </div>
</div>

<script src="/hotelProyecto/public/js/habitacion-editar.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>