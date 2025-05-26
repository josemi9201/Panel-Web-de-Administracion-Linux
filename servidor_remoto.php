<?php
include_once 'inc/auth.php';
include_once 'inc/roles.php';

if (!tiene_permiso('configurar_remoto')) {
    exit("❌ No tienes permiso para configurar servidores remotos.");
}

session_start();

$archivo_remotos = __DIR__ . '/remotos.json';

// Leer remotos guardados (si no existe, arreglo vacío)
$remotos = [];
if (file_exists($archivo_remotos)) {
    $json = file_get_contents($archivo_remotos);
    $remotos = json_decode($json, true) ?: [];
}

// Guardar remotos en archivo
function guardar_remotos($remotos, $archivo) {
    file_put_contents($archivo, json_encode($remotos, JSON_PRETTY_PRINT));
}

// Manejo POST para agregar o activar remoto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['guardar_nuevo'])) {
        // Validar campos
        $host = trim($_POST['host'] ?? '');
        $usuario = trim($_POST['usuario'] ?? '');
        $clave = trim($_POST['clave'] ?? '');

        if ($host && $usuario && $clave) {
            // Añadir nuevo remoto a la lista y guardar
            $remotos[$host] = [
                'host' => $host,
                'usuario' => $usuario,
                'clave' => $clave
            ];
            guardar_remotos($remotos, $archivo_remotos);

            // Activar este servidor remoto en sesión
            $_SESSION['remoto'] = $remotos[$host];
            header("Location: servidor_remoto.php");
            exit;
        } else {
            $error = "Por favor, rellena todos los campos para guardar el servidor.";
        }
    } elseif (isset($_POST['activar_remoto'])) {
        // Activar servidor remoto existente
        $host_sel = $_POST['host'] ?? '';
        if (isset($remotos[$host_sel])) {
            $_SESSION['remoto'] = $remotos[$host_sel];
        } else {
            unset($_SESSION['remoto']);
        }
        header("Location: servidor_remoto.php");
        exit;
    }
}

// Manejo para desactivar conexión remota
if (isset($_GET['desactivar'])) {
    unset($_SESSION['remoto']);
    header("Location: servidor_remoto.php");
    exit;
}

// Variables para el formulario
$remoto_activo = $_SESSION['remoto'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configurar servidor remoto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Configurar conexión remota</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!-- Formulario para activar remoto existente -->
<form method="post" style="margin-bottom: 20px;">
    <label>Servidores remotos guardados:</label><br>
    <select name="host" required>
        <option value="">-- Selecciona servidor remoto --</option>
        <?php foreach ($remotos as $host => $datos): ?>
            <option value="<?= htmlspecialchars($host) ?>" <?= ($remoto_activo && $remoto_activo['host'] === $host) ? 'selected' : '' ?>>
                <?= htmlspecialchars($host) ?> (<?= htmlspecialchars($datos['usuario']) ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="activar_remoto">Activar servidor remoto</button>
</form>

<!-- Formulario para agregar nuevo servidor remoto -->
<form method="post" style="border-top:1px solid #ccc; padding-top:20px;">
    <h3>Agregar nuevo servidor remoto</h3>
    <label>Host (IP o dominio):</label><br>
    <input type="text" name="host" required><br><br>
    <label>Usuario SSH:</label><br>
    <input type="text" name="usuario" required><br><br>
    <label>Contraseña SSH:</label><br>
    <input type="password" name="clave" required><br><br>
    <button type="submit" name="guardar_nuevo">Guardar y activar</button>
</form>

<?php if ($remoto_activo): ?>
    <p>Servidor remoto activo: <strong><?= htmlspecialchars($remoto_activo['host']) ?></strong> (<?= htmlspecialchars($remoto_activo['usuario']) ?>)</p>
    <form method="get" action="servidor_remoto.php">
        <button type="submit" name="desactivar" value="1">Desactivar conexión remota</button>
    </form>
<?php endif; ?>

<form method="get" action="dashboard.php" style="margin-top:20px;">
    <button>⬅️ Volver al panel</button>
</form>

</body>
</html>
