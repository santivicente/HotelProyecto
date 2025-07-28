<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Enrutamiento simple MVC
if (isset($_GET['controller'])) {
    $controller = $_GET['controller'];

    switch ($controller) {
        case 'panelCliente':
            require_once __DIR__ . '/controllers/PanelClienteController.php';
            exit();
        case 'panelPersonal':
            require_once __DIR__ . '/controllers/PanelPersonalController.php';
            exit();
        case 'habitaciones':
            require_once __DIR__ . '/controllers/HabitacionesController.php';
            exit();
        case 'reserva':
            require_once __DIR__ . '/controllers/ReservaController.php';
            exit();
        case 'personal':
            require_once __DIR__ . '/controllers/PersonalController.php';
            exit();
        case 'estadisticas':
            require_once __DIR__ . '/controllers/EstadisticasController.php';
            exit();
        case 'login':
            require_once __DIR__ . '/controllers/LoginController.php';
            exit();
        case 'logout':
            require_once __DIR__ . '/controllers/LogoutController.php';
            exit();
        case 'registro':
            require_once __DIR__ . '/controllers/RegistroController.php';
            exit();
        case 'perfil':
            require_once __DIR__ . '/controllers/PerfilController.php';
            exit();
        case 'misReservas':
            require_once __DIR__ . '/controllers/MisReservasController.php';
            exit();
        case 'reservar':
            require_once __DIR__ . '/controllers/ReservarController.php';
            exit();
        case 'asignarHabitacion':
            require_once __DIR__ . '/controllers/AsignarHabitacionController.php';
            exit();
        case 'detalleReserva':
            require_once __DIR__ . '/controllers/DetalleReservaController.php';
            exit();
        case 'habitacionABM':
            require_once __DIR__ . '/controllers/HabitacionABMController.php';
            exit();
        case 'verTipoHabitacion':
            require_once __DIR__ . '/controllers/TipoHabitacionController.php';
            exit();
        // Eliminado: buscarReservasCliente y todo lo relacionado con buscar por DNI
        // Agrega aquí más controladores según los necesites
    }
}

// Solo redirige al panel si NO hay controller en la URL
if (!isset($_GET['controller']) && isset($_SESSION['idUsuario'])) {
    if ($_SESSION['rol'] === 'cliente') {
        header('Location: index.php?controller=panelCliente');
        exit();
    } elseif ($_SESSION['rol'] === 'personal') {
        header('Location: index.php?controller=panelPersonal');
        exit();
    }
}

// Si no está logueado, incluimos el header estándar
include __DIR__ . '/views/layout/header.php';
?>

<!-- El contenido principal de la página de bienvenida -->
<div class="contenedor">
    <div style="text-align: center; padding: 50px 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <h2>Bienvenido a Hotel Paradiso</h2>
        <p style="font-size: 1.2em; color: #555;">La estancia de tus sueños te está esperando.</p>

        <a href="index.php?controller=habitaciones"
           style="display: inline-block; text-decoration: none; background-color: #0779e4; color: #fff; padding: 15px 30px; border-radius: 5px; font-weight: bold; transition: background-color 0.2s;"
           onmouseover="this.style.backgroundColor='#0056b3'"
           onmouseout="this.style.backgroundColor='#0779e4'">
           Ver Habitaciones
        </a>
    </div>
</div>

<?php include __DIR__ . '/views/layout/footer.php';