<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Paradiso</title>
    <link rel="stylesheet" href="/hotelProyecto/css/estilos.css">
</head>
<body>
    <header>
        <div class="contenedor">
            <h1><a href="/hotelProyecto/index.php">Hotel Paradiso</a></h1>
            <nav>
                <ul>
                    <li><a href="/hotelProyecto/index.php?controller=habitaciones">Habitaciones</a></li>
                    <?php if (isset($_SESSION['idUsuario'])): ?>
                        <?php if ($_SESSION['rol'] === 'cliente'): ?>
                            <li><a href="/hotelProyecto/index.php?controller=panelCliente">Mi Panel</a></li>
                        <?php elseif ($_SESSION['rol'] === 'personal'): ?>
                            <li><a href="/hotelProyecto/index.php?controller=panelPersonal">Panel Personal</a></li>
                        <?php endif; ?>
                        <li><a href="/hotelProyecto/index.php?controller=logout">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="/hotelProyecto/index.php?controller=login">Login</a></li>
                        <li><a href="/hotelProyecto/index.php?controller=registro">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>