<?php 
// ARCHIVO: vistas/vista_mi_perfil.php
include __DIR__ . '/../layout/header.php'; 
?>

<div class="contenedor">
    <div class="panel">
        <aside class="menu-lateral">
            <h3>Menú Cliente</h3>
            <a href="/hotelProyecto/index.php?controller=habitaciones">Nueva Reserva</a>
            <a href="/hotelProyecto/index.php?controller=misReservas">Mis Reservas</a>
            <a href="/hotelProyecto/index.php?controller=perfil" style="background:#0779e4;">Mi Perfil</a>
        </aside>
        <main class="contenido-principal">
            <h2>Mi Perfil</h2>
            <p>Aquí puedes actualizar tus datos personales y cambiar tu contraseña.</p>

            <?php if ($error): ?><p class="alerta alerta-error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
            <?php if ($exito): ?><p class="alerta alerta-exito"><?php echo htmlspecialchars($exito); ?></p><?php endif; ?>

            <!-- Formulario de Datos Personales -->
            <div class="formulario" style="max-width: none; margin: 20px 0;">
                <h3>Datos Personales</h3>
                <form action="/hotelProyecto/index.php?controller=perfil" method="post">
                    <input type="hidden" name="accion" value="actualizar_datos">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($cliente['apellido']); ?>" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['mail']); ?>" required>
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
                    <button type="submit">Actualizar Datos</button>
                </form>
            </div>

            <!-- Formulario de Cambio de Contraseña -->
            <div class="formulario" style="max-width: none; margin: 40px 0;">
                <h3>Cambiar Contraseña</h3>
                <form action="/hotelProyecto/index.php?controller=perfil" method="post">
                    <input type="hidden" name="accion" value="cambiar_password">

                    <label for="password_actual">Contraseña Actual:</label>
                    <input type="password" id="password_actual" name="password_actual" required>
                    
                    <label for="password_nueva">Nueva Contraseña:</label>
                    <input type="password" id="password_nueva" name="password_nueva" required>

                    <label for="password_confirmar">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="password_confirmar" name="password_confirmar" required>
                    
                    <button type="submit">Cambiar Contraseña</button>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>