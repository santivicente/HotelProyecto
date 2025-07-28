<?php

require __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('cliente');

include __DIR__ . '/../views/layout/header.php';
?>

<div class="contenedor">
    <div class="panel">
        <aside class="menu-lateral">
            <h3>Menú Cliente</h3>
            <a href="/hotelProyecto/index.php?controller=habitaciones">Nueva Reserva</a>
            <a href="/hotelProyecto/index.php?controller=misReservas">Mis Reservas</a>
            <a href="/hotelProyecto/index.php?controller=perfil">Mi Perfil</a>
        </aside>
        <main class="contenido-principal">
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Desde aquí puedes gestionar tus reservas y tu perfil.</p>
            <p>Selecciona una opción del menú de la izquierda para comenzar.</p>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../views/layout/footer.php'; ?>