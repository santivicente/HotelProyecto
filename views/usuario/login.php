<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Iniciar Sesión</h2>

<?php if ($error): ?>
    <p class="alerta alerta-error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form class="formulario" action="/hotelProyecto/index.php?controller=login" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Entrar</button>
</form>

<?php include __DIR__ . '/../layout/footer.php'; ?>