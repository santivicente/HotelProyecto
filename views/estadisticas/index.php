<?php


include __DIR__ . '/../layout/header.php'; ?>
<div class="contenedor">
    <div class="panel">
        <aside class="menu-lateral">
            <h3>Menú Personal</h3>
            <a href="/hotelProyecto/index.php?controller=panelPersonal">Dashboard</a>
            <a href="/hotelProyecto/index.php?controller=reserva">Gestionar Reservas</a>
            <a href="/hotelProyecto/index.php?controller=habitacionABM">Gestionar Habitaciones</a>
            <a href="/hotelProyecto/index.php?controller=personal">Gestionar Personal</a>
            <a href="/hotelProyecto/index.php?controller=estadisticas" style="background:#0779e4;">Estadísticas</a>
        </aside>
        <main class="contenido-principal">
            <h2>Estadísticas del Hotel</h2>

            <h3>Reservas por Mes</h3>
            <canvas id="graficoReservasMes" width="400" height="120"></canvas>
            <table class="tabla">
                <thead><tr><th>Mes</th><th>Total de Operaciones de Reserva</th></tr></thead>
                <tbody>
                <?php foreach($reservasMes as $stat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stat['mes']); ?></td>
                        <td><?php echo htmlspecialchars($stat['total']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <h3 style="margin-top: 40px;">Top 5 Clientes con Más Reservas</h3>
            <canvas id="graficoTopClientes" width="400" height="120"></canvas>
            <table class="tabla">
                <thead><tr><th>Cliente</th><th>Cantidad de Reservas</th></tr></thead>
                <tbody>
                <?php foreach($topClientes as $cliente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['total_reservas']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <!-- ESTADÍSTICA: Cantidad de reservas por cliente usando función almacenada -->
            <h3 style="margin-top: 40px;">Cantidad de Reservas por Cliente (usando función almacenada)</h3>
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>DNI</th>
                        <th>Cantidad de Reservas</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($cantReservasPorCliente as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['nombre'] . ' ' . $c['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($c['dni']); ?></td>
                        <td><?php echo htmlspecialchars($c['cantidad']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <!-- FIN ESTADÍSTICA función almacenada -->

        </main>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos de Reservas por Mes
    const reservasMesLabels = <?php echo json_encode(array_column($reservasMes, 'mes')); ?>;
    const reservasMesData = <?php echo json_encode(array_column($reservasMes, 'total')); ?>;

    // Datos del Top 5 Clientes
    const topClientesLabels = <?php echo json_encode(array_map(function($c) {
        return $c['nombre'] . ' ' . $c['apellido'];
    }, $topClientes)); ?>;
    const topClientesData = <?php echo json_encode(array_column($topClientes, 'total_reservas')); ?>;

    // Gráfico de Barras: Reservas por Mes
    new Chart(document.getElementById('graficoReservasMes'), {
        type: 'bar',
        data: {
            labels: reservasMesLabels,
            datasets: [{
                label: 'Reservas',
                data: reservasMesData,
                backgroundColor: 'rgba(7, 121, 228, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Gráfico de Barras: Top 5 Clientes
    new Chart(document.getElementById('graficoTopClientes'), {
        type: 'bar',
        data: {
            labels: topClientesLabels,
            datasets: [{
                label: 'Reservas',
                data: topClientesData,
                backgroundColor: 'rgba(40, 167, 69, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>