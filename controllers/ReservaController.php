<?php

// ARCHIVO: gestion_reservas.php
require_once __DIR__ . '/../includes/verificar_sesion.php';
verificarRol('personal');

require_once __DIR__ . '/../models/db.php';

include __DIR__ . '/../views/layout/header.php';

// Obtener estados de reserva desde la base de datos
$estados = [];
$sql = "SELECT idEstadoReserva, nombreEstadoReserva FROM estadoreserva";
$result = $pdo->query($sql);
if ($result) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $estados[] = $row;
    }
}

// Parámetros de paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Filtros
$filtros = [];
$parametros = [];

// Filtrar por fecha de reserva si se recibe por GET
if (isset($_GET['fecha_reserva']) && $_GET['fecha_reserva'] !== '') {
    $filtros[] = "fechaReserva = :fechaReserva";
    $parametros[':fechaReserva'] = $_GET['fecha_reserva'];
}

// Filtrar por estado de reserva si se recibe por GET
if (isset($_GET['estado_reserva']) && $_GET['estado_reserva'] !== '') {
    $filtros[] = "idEstadoReserva = :estadoReserva";
    $parametros[':estadoReserva'] = $_GET['estado_reserva'];
}

// Construir cláusula WHERE solo si hay filtros
$where = $filtros ? 'WHERE ' . implode(' AND ', $filtros) : '';

// Total de registros para paginación
$sql_total = "SELECT COUNT(*) FROM reserva";
if ($where) {
    $sql_total .= " $where";
}
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($parametros);
$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta principal
$sql = "SELECT * FROM vistaReserva
        $where
        ORDER BY fechaReserva DESC
        LIMIT :offset, :limite";
        echo $sql;
$stmt = $pdo->prepare($sql);
foreach ($parametros as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar actualización de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $idReserva = (int)$_POST['idReserva'];
    $nuevoEstado = (int)$_POST['nuevo_estado'];
    $idHabitacionNueva = !empty($_POST['nueva_habitacion']) ? (int)$_POST['nueva_habitacion'] : null;

    // Si se pasa a En Curso, debe asignar habitación
    if ($nuevoEstado === 5 && $idHabitacionNueva) {
        $sql_update = "UPDATE reserva SET idEstadoReserva = :nuevoEstado WHERE idReserva = :idReserva";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':nuevoEstado' => $nuevoEstado,
            ':idReserva' => $idReserva
        ]);

        $sql_update = "UPDATE detallereserva SET idhabitacion = :nueva_habitacion WHERE idReserva = :idReserva";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':nueva_habitacion' => $idHabitacionNueva,
            ':idReserva' => $idReserva
        ]);

    } else {
        $sql_update = "UPDATE reserva SET idEstadoReserva = :nuevoEstado WHERE idReserva = :idReserva";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':nuevoEstado' => $nuevoEstado,
            ':idReserva' => $idReserva
        ]);
    }
    // Redirigir para evitar reenvío de formulario
    $params = $_GET;
    unset($params['pagina']);
    $query = http_build_query($params);
    header("Location: /hotelProyecto/index.php?controller=reserva&" . $query . "&pagina=" . $pagina_actual);
    exit;
}

// Función para obtener habitaciones disponibles
function obtenerHabitacionesDisponibles($pdo, $fechaDesde, $fechaHasta) {
   
    $sql = "SELECT h.idHabitacion, h.idtipoHabitacion, th.nombreTipoHabitacion as tipoHabitacion
            FROM habitacion h
            JOIN tipohabitacion th ON h.idtipoHabitacion = th.idtipoHabitacion
            WHERE h.idHabitacion NOT IN (
                SELECT dr.idhabitacion
                FROM reserva r
                JOIN detallereserva dr ON r.idreserva = dr.idreserva
                WHERE (:fechaDesde BETWEEN r.fechaDesde AND r.fechaHasta) AND (:fechaHasta BETWEEN r.fechaDesde AND r.fechaHasta)
            ) AND h.idEstadoHabitacion = 1"; // Solo habitaciones activas;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':fechaDesde' => $fechaDesde, ':fechaHasta' => $fechaHasta]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="contenedor">
    <div class="panel">
    <aside class="menu-lateral">
        <h3>Menú Personal</h3>
        <a href="/hotelProyecto/index.php?controller=panelPersonal">Dashboard</a>
        <a href="/hotelProyecto/index.php?controller=reserva" style="background:#0779e4;">Gestionar Reservas</a>
        <a href="/hotelProyecto/index.php?controller=habitacionABM">Gestionar Habitaciones</a>
        <a href="/hotelProyecto/index.php?controller=personal">Gestionar Personal</a>
        <a href="/hotelProyecto/index.php?controller=estadisticas">Estadísticas</a>
</aside>
        <main class="contenido-principal">
            <h2>Gestión de Todas las Reservas</h2>

            <form method="GET" action="/hotelProyecto/index.php" class="filtros-reservas" style="margin-bottom:20px; display:flex; gap:20px; align-items:flex-end;">
                <input type="hidden" name="controller" value="reserva">
                <div>
                    <label for="fecha_reserva">Fecha de Reserva:</label>
                    <input type="date" id="fecha_reserva" name="fecha_reserva" value="<?php echo isset($_GET['fecha_reserva']) ? htmlspecialchars($_GET['fecha_reserva']) : ''; ?>">
                </div>
                <div>
                    <label for="estado_reserva">Estado de Reserva:</label>
                    <select id="estado_reserva" name="estado_reserva">
                        <option value="">-- Todos --</option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?php echo $estado['idEstadoReserva']; ?>" <?php echo (isset($_GET['estado_reserva']) && $_GET['estado_reserva'] == $estado['idEstadoReserva']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($estado['nombreEstadoReserva']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit">Filtrar</button>
                </div>
            </form>

            <table border="1" cellpadding="8" cellspacing="0" style="width:100%;margin-bottom:20px;">
                <thead>
                    <tr>
                        <th>Fecha de Reserva</th>
                        <th>Nombre del Cliente</th>
                        <th>Tipo de Habitación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($reservas)): ?>
                    <tr><td colspan="5" style="text-align:center;">No se encontraron reservas.</td></tr>
                <?php else: ?>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($reserva['fechaReserva']); ?>
                                <br>
                                <span style="font-size: 0.8em;">
                                    (<?php echo htmlspecialchars($reserva['fechaDesde']); ?><br> - <?php echo htmlspecialchars($reserva['fechaHasta']); ?>)
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($reserva['nombreCliente']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['tipoHabitacion'] ?? 'Sin asignar'); ?></td>
                            <td><?php echo htmlspecialchars($reserva['nombreEstadoReserva']); ?><br>hab:<?php echo htmlspecialchars($reserva['idHabitacion']); ?></td>
                            <td>
                                <?php
                                switch ($reserva['idEstadoReserva']) {
                                    case 4: // Pendiente de confirmación
                                        ?>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="idReserva" value="<?php echo $reserva['idReserva']; ?>">
                                            <button type="submit" name="actualizar_estado" value="4" onclick="this.form.nuevo_estado.value=1;">Confirmar</button>
                                            <input type="hidden" name="nuevo_estado" value="1">
                                        </form>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="idReserva" value="<?php echo $reserva['idReserva']; ?>">
                                            <button type="submit" name="actualizar_estado" value="4" onclick="this.form.nuevo_estado.value=2;">Cancelar</button>
                                            <input type="hidden" name="nuevo_estado" value="2">
                                        </form>
                                        <?php
                                        break;
                                    case 1: // Confirmada
                                        ?>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="idReserva" value="<?php echo $reserva['idReserva']; ?>">
                                            <button type="submit" name="actualizar_estado" value="1" onclick="this.form.nuevo_estado.value=2;">Cancelar</button>
                                            <input type="hidden" name="nuevo_estado" value="2">
                                        </form>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="idReserva" value="<?php echo $reserva['idReserva']; ?>">
                                            <input type="hidden" name="nuevo_estado" value="5">
                                            <?php
                                            $habitaciones = obtenerHabitacionesDisponibles($pdo, $reserva['fechaDesde'], $reserva['fechaHasta']);
                                            if ($habitaciones):
                                            ?>
                                                <select name="nueva_habitacion" required>
                                                    <option value="">Seleccionar habitación</option>
                                                    <?php foreach ($habitaciones as $hab): ?>
                                                        <option value="<?php echo $hab['idHabitacion']; ?>">
                                                            <?php echo htmlspecialchars($hab['tipoHabitacion']) . " (ID: " . $hab['idHabitacion'] . ")"; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" name="actualizar_estado" value="5">Poner En Curso</button>
                                            <?php else: ?>
                                                <span style="color:#888;">No hay habitaciones disponibles</span>
                                            <?php endif; ?>
                                        </form>
                                        <?php
                                        break;
                                    case 5: // En Curso
                                        ?>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="idReserva" value="<?php echo $reserva['idReserva']; ?>">
                                            <input type="hidden" name="nuevo_estado" value="3">
                                            <button type="submit" name="actualizar_estado" value="3">Completar</button>
                                        </form>
                                        <?php
                                        break;
                                    case 2: // Cancelada
                                    case 3: // Completa
                                    default:
                                        echo '<span style="color:#888;">Sin acciones</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div style="text-align:center;">
                <?php
                $params = $_GET;
                unset($params['pagina']);
                $query = http_build_query($params);
                for ($i = 1; $i <= $total_paginas; $i++): 
                    if ($i == $pagina_actual): ?>
                        <strong><?php echo $i; ?></strong>
                    <?php else: ?>
                        <a href="/hotelProyecto/index.php?controller=reserva&<?php echo $query . '&pagina=' . $i; ?>"><?php echo $i; ?></a>
                    <?php endif;
                    if ($i < $total_paginas) echo " | ";
                endfor;
                ?>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../views/layout/footer.php'; ?>