<?php
include_once 'inc/auth.php';

if ($_SESSION['rol'] !== 'admin') {
    exit("❌ Solo el administrador puede ver el log de acciones.");
}

$logfile = __DIR__ . '/logs/panel.log';
$log_content = '';

if (file_exists($logfile)) {
    $contenido = file_get_contents($logfile);
    if ($contenido === false || trim($contenido) === '') {
        $log_content = "No hay registros de acciones aún.";
    } else {
        $log_content = $contenido;
    }
} else {
    $log_content = "No hay registros de acciones aún.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Log de acciones del panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>📜 Log de acciones del panel</h2>
<pre style="background:#121212; padding:15px; border-radius:8px; max-height: 600px; overflow-y: scroll; color: #eee;">
<?= htmlspecialchars($log_content) ?>
</pre>
<form action="dashboard.php" method="get">
    <button>⬅️ Volver al panel</button>
</form>
</body>
</html>
