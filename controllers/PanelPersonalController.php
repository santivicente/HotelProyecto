<?php


require __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('personal');

include __DIR__ . '/../views/layout/header.php';
?>

<div class="contenedor">
    <div class="panel">
        <aside class="menu-lateral">
            <h3>Menú Personal</h3>
            <a href="/hotelProyecto/index.php?controller=panelPersonal">Dashboard</a>
            <a href="/hotelProyecto/index.php?controller=reserva">Gestionar Reservas</a>
            <a href="/hotelProyecto/index.php?controller=habitacionABM">Gestionar Habitaciones</a>
            <a href="/hotelProyecto/index.php?controller=personal">Gestionar Personal</a>
            <a href="/hotelProyecto/index.php?controller=estadisticas">Estadísticas</a>
        </aside>
        <main class="contenido-principal">
            <h2>Panel de Administración del Hotel</h2>
            <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>.</p>
            <p>Usa el menú para gestionar el sistema.</p>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../views/layout/footer.php'; ?>