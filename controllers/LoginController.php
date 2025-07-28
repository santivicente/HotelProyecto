<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../models/Db.php';
require __DIR__ . '/../models/Usuario.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $credencial = trim($_POST['credencial']);
    $password = $_POST['password'];

    if (empty($credencial) || empty($password)) {
        $error = 'El usuario/email y la contraseña son obligatorios.';
    } else {
        $usuario = verificarUsuario($pdo, $credencial);

        if ($usuario && password_verify($password, $usuario['passwordHash'])) {
            $_SESSION['idUsuario'] = $usuario['idUsuario'];
            $_SESSION['username'] = $usuario['username'];

            if (!empty($usuario['DNIPersonalHotel'])) {
                $_SESSION['rol'] = 'personal';
                $_SESSION['DNIPersonal'] = $usuario['DNIPersonalHotel'];
                $redirect_to = '/hotelProyecto/index.php?controller=panelPersonal';
            } 
            elseif (!empty($usuario['DNICliente'])) {
                $_SESSION['rol'] = 'cliente';
                $_SESSION['DNICliente'] = $usuario['DNICliente'];
                $redirect_to = '/hotelProyecto/index.php?controller=panelCliente';
            } 
            else {
                $error = "Error de configuración de la cuenta. Contacte al administrador.";
                session_destroy();
                $usuario = null; 
            }

            if (isset($redirect_to)) {
                if (isset($_SESSION['redirect_url'])) {
                    $redirect_url = $_SESSION['redirect_url'];
                    unset($_SESSION['redirect_url']);
                    header('Location: ' . $redirect_url);
                } else {
                    header('Location: ' . $redirect_to);
                }
                exit();
            }

        } else {
            $error = 'Usuario/email o contraseña incorrectos.';
        }
    }
}

include __DIR__ . '/../views/layout/header.php';
?>

<div class="contenedor">
    <div class="formulario">
        <h2>Iniciar Sesión</h2>

        <?php if ($error): ?>
            <p class="alerta alerta-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="/hotelProyecto/index.php?controller=login" method="post">
            <label for="credencial">Email o Nombre de Usuario:</label>
            <input type="text" id="credencial" name="credencial" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Entrar</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../views/layout/footer.php'; ?>