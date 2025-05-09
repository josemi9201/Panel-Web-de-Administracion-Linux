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
            font-family: sans-serif;
            text-align: center;
            background: #f4f4f4;
            padding: 30px;
        }
        canvas {
            max-width: 400px;
            margin: 20px auto;
            display: block;
        }
        h2 {
            margin-bottom: 40px;
            color: #333;
        }
        button {
            margin-top: 30px;
            padding: 10px 20px;
            font-size: 15px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>📊 Uso del sistema</h2>

    <canvas id="cpuChart"></canvas>
    <canvas id="ramChart"></canvas>
    <canvas id="diskChart"></canvas>

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
                datasets: [{ data: [0, 100], backgroundColor: ['red', 'lightgray'] }]
            },
            options: {
                plugins: {
                    title: { display: true, text: 'Uso de CPU (%)' }
                }
            }
        });

        ramChart = new Chart(document.getElementById('ramChart'), {
            type: 'bar',
            data: {
                labels: ['RAM Usada', 'Libre'],
                datasets: [{ data: [0, 0], backgroundColor: ['orange', 'green'] }]
            },
            options: {
                plugins: {
                    title: { display: true, text: 'Uso de RAM (MB)' }
                }
            }
        });

        diskChart = new Chart(document.getElementById('diskChart'), {
            type: 'bar',
            data: {
                labels: ['Disco Usado', 'Libre'],
                datasets: [{ data: [0, 0], backgroundColor: ['blue', 'gray'] }]
            },
            options: {
                plugins: {
                    title: { display: true, text: 'Uso de Disco (MB)' }
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
