<?php


include __DIR__ . '/../layout/header.php'; 
?>

<div class="contenedor">
    <div class="panel">
        <aside class="menu-lateral">
            <h3>Menú Personal</h3>
            <a href="/hotelProyecto/index.php?controller=panelPersonal">Dashboard</a>
            <a href="/hotelProyecto/index.php?controller=reserva">Gestionar Reservas</a>
            <a href="/hotelProyecto/index.php?controller=habitacionABM">Gestionar Habitaciones</a>
            <a href="/hotelProyecto/index.php?controller=personal" style="background:#0779e4;">Gestionar Personal</a>
            <a href="/hotelProyecto/index.php?controller=estadisticas">Estadísticas</a>
        </aside>
        <main class="contenido-principal">
            <h2>Gestión de Personal</h2>
            <?php if ($exito): ?><p class="alerta alerta-exito"><?php echo htmlspecialchars($exito); ?></p><?php endif; ?>
            <?php if ($error): ?><p class="alerta alerta-error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>

            <!-- Formulario de ALTA o EDICIÓN -->
            <?php if ($accion === 'editar' && $empleado): ?>
                <h3>Editando a <?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']); ?></h3>
                <form class="formulario" action="/hotelProyecto/index.php?controller=personal" method="post" style="max-width:none; margin: 20px 0;">
                    <input type="hidden" name="accion_form" value="editar">
                    <input type="hidden" name="dni" value="<?php echo htmlspecialchars($empleado['DNIPersonalHotel']); ?>">
                    <input type="hidden" name="idUsuario" value="<?php echo htmlspecialchars($empleado['idUsuario']); ?>">
                    <label>Nombre:</label><input type="text" name="nombre" value="<?php echo htmlspecialchars($empleado['nombre'] ?? ''); ?>" required>
                    <label>Apellido:</label><input type="text" name="apellido" value="<?php echo htmlspecialchars($empleado['apellido'] ?? ''); ?>" required>
                    <label>Email:</label><input type="email" name="email" value="<?php echo htmlspecialchars($empleado['email'] ?? ''); ?>" required>
                    <label>Teléfono:</label><input type="text" name="telefono" value="<?php echo htmlspecialchars($empleado['telefono'] ?? ''); ?>">
                    <label>Rol:</label>
                    <select name="idRol" required>
                        <?php foreach($roles as $rol): ?>
                            <option value="<?php echo $rol['idRol']; ?>" <?php echo ($rol['idRol'] == ($empleado['idRol'] ?? '')) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($rol['nombreRol']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Guardar Cambios</button>
                    <a href="/hotelProyecto/index.php?controller=personal" style="display:block; text-align:center; margin-top:10px;">Cancelar Edición</a>
                </form>
            <?php else: ?>
                <h3>Añadir Nuevo Empleado</h3>
                <form class="formulario" action="/hotelProyecto/index.php?controller=personal" method="post" style="max-width:none; margin: 20px 0;">
                    <input type="hidden" name="accion_form" value="crear">
                    <label>DNI:</label><input type="text" name="dni" required>
                    <label>Nombre de Usuario:</label><input type="text" name="username" required>
                    <label>Contraseña:</label><input type="password" name="password" required>
                    <label>Nombre:</label><input type="text" name="nombre" required>
                    <label>Apellido:</label><input type="text" name="apellido" required>
                    <label>Email:</label><input type="email" name="email" required>
                    <label>Teléfono:</label><input type="text" name="telefono">
                    <label>Rol:</label>
                    <select name="idRol" required>
                        <option value="">-- Seleccione un rol --</option>
                        <?php foreach($roles as $rol): ?>
                            <option value="<?php echo $rol['idRol']; ?>"><?php echo htmlspecialchars($rol['nombreRol']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Registrar Empleado</button>
                </form>
            <?php endif; ?>

            <!-- Lista de Personal -->
            <h3 style="margin-top: 40px;">Personal Actual</h3>
            <table class="tabla">
                <thead>
                    <tr><th>Nombre Completo</th><th>Email</th><th>Rol</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php foreach($listaPersonal as $persona): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($persona['nombre'] . ' ' . $persona['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($persona['mail']); ?></td>
                        <td><?php echo htmlspecialchars($persona['nombreRol']); ?></td>
                        <td>
                            <a href="/hotelProyecto/index.php?controller=personal&accion=editar&dni=<?php echo $persona['DNIPersonalHotel']; ?>">Editar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>