<?php include_once 'inc/auth.php'; ?>

<?php
// Datos iniciales (se actualizan vía JS)
$cpu = 0;
$ram_usada = 0;
$ram_libre = 0;
$disk_usada = 0;
$disk_libre = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📊 Uso del sistema</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #121212;
            color: #e0e0e0;
            padding: 30px;
            text-align: center;
            margin: 0;
        }
        h2 {
            color: #bb86fc;
            margin-bottom: 40px;
        }
        .chart-container {
            max-width: 400px;
            margin: 0 auto 40px auto;
            background: #1f1f1f;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.7);
        }
        canvas {
            max-width: 100%;
            height: auto !important;
        }
        button {
            margin-top: 30px;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            background-color: #bb86fc;
            color: #121212;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.25s ease;
        }
        button:hover {
            background-color: #985eff;
        }
    </style>
</head>
<body>
    <h2>📊 Uso del sistema</h2>

    <div class="chart-container">
        <canvas id="cpuChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="ramChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="diskChart"></canvas>
    </div>

    <script>
    let cpuChart, ramChart, diskChart;

    function actualizarGraficos(data) {
        const { cpu, ram_usada, ram_libre, disk_usada, disk_libre } = data;

        cpuChart.data.datasets[0].data = [cpu, 100 - cpu];
        ramChart.data.datasets[0].data = [ram_usada, ram_libre];
        diskChart.data.datasets[0].data = [disk_usada, disk_libre];

        cpuChart.update();
        ramChart.update();
        diskChart.update();
    }

    function cargarDatos() {
        fetch('uso_sistema_datos.php')
            .then(res => res.json())
            .then(actualizarGraficos)
            .catch(err => console.error("Error al cargar datos:", err));
    }

    window.onload = function () {
        cpuChart = new Chart(document.getElementById('cpuChart'), {
            type: 'doughnut',
            data: {
                labels: ['CPU Usada', 'Libre'],
                datasets: [{ data: [0, 100], backgroundColor: ['#bb86fc', '#444'] }]
            },
            options: {
                plugins: {
                    title: { display: true, text: 'Uso de CPU (%)', color: '#e0e0e0' },
                    legend: {
                        labels: { color: '#e0e0e0' }
                    }
                }
            }
        });

        ramChart = new Chart(document.getElementById('ramChart'), {
            type: 'bar',
            data: {
                labels: ['RAM Usada', 'Libre'],
                datasets: [{ data: [0, 0], backgroundColor: ['#bb86fc', '#444'] }]
            },
            options: {
                plugins: {
                    title: { display: true, text: 'Uso de RAM (MB)', color: '#e0e0e0' },
                    legend: { display: false }
                },
                scales: {
                    y: {
                        ticks: { color: '#e0e0e0' },
                        beginAtZero: true
                    },
                    x: {
                        ticks: { color: '#e0e0e0' }
                    }
                }
            }
        });

        diskChart = new Chart(document.getElementById('diskChart'), {
            type: 'bar',
            data: {
                labels: ['Disco Usado', 'Libre'],
                datasets: [{ data: [0, 0], backgroundColor: ['#bb86fc', '#444'] }]
            },
            options: {
                plugins: {
                    title: { display: true, text: 'Uso de Disco (MB)', color: '#e0e0e0' },
                    legend: { display: false }
                },
                scales: {
                    y: {
                        ticks: { color: '#e0e0e0' },
                        beginAtZero: true
                    },
                    x: {
                        ticks: { color: '#e0e0e0' }
                    }
                }
            }
        });

        cargarDatos();
        setInterval(cargarDatos, 5000);
    };
    </script>

    <form action="dashboard.php" method="get">
        <button>⬅️ Volver al panel</button>
    </form>
</body>
</html>
