<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Registro de Nuevo Cliente</h2>

<?php if ($error): ?>
    <p class="alerta alerta-error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<?php if ($exito): ?>
    <p class="alerta alerta-exito"><?php echo $exito; ?></p>
<?php else: ?>
    <form class="formulario" action="/hotelProyecto/index.php?controller=registro" method="post">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirm">Confirmar Contraseña:</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
        
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>
        
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono">
        
        <button type="submit">Registrarse</button>
    </form>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>