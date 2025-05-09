<?php
include_once 'inc/auth.php';
if ($_SESSION['usuario'] !== 'admin') {
    exit("❌ Solo el administrador puede gestionar permisos.");
}

$archivo_usuarios = __DIR__ . '/inc/usuarios.php';
$archivo_permisos = __DIR__ . '/inc/permisos.php';

include $archivo_usuarios;
include $archivo_permisos;

$usuarios = array_keys($usuarios_validos);

// Acciones posibles
$acciones_disponibles = [
    "hacer_backup", "listar_backups", "eliminar_backups", "eliminar_todos_los_backups", "descargar_backup",
    "uso_sistema", "ver_procesos", "ver_conexiones", "ver_logs", "ver_logs_apache", "ver_uptime",
    "usuarios_conectados", "intentos_fallidos", "actualizar_sistema", "reiniciar_sistema"
];

if (isset($_POST['guardar'])) {
    $nuevo_permisos = [];

    foreach ($usuarios as $user) {
        if (($usuarios_validos[$user]['rol'] ?? '') === 'admin') {
            $nuevo_permisos[$user] = ["*"];
            continue;
        }

        $nuevo_permisos[$user] = $_POST["acciones_$user"] ?? [];
    }

    $contenido = "<?php\n\$permisos = " . var_export($nuevo_permisos, true) . ";\n";

    if (file_put_contents($archivo_permisos, $contenido) !== false) {
        $mensaje = "✅ Permisos actualizados correctamente.";
        include $archivo_permisos;
    } else {
        $mensaje = "❌ Error al guardar los permisos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de permisos</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; vertical-align: top; text-align: left; }
        label { display: inline-block; margin-right: 8px; }
    </style>
</head>
<body>

<h2>🔐 Gestión de permisos de usuarios</h2>
<?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>

<form method="post">
    <table>
        <tr>
            <th>Usuario</th>
            <th>Permisos asignados</th>
        </tr>

        <?php foreach ($usuarios as $user): ?>
            <tr>
                <td><strong><?= htmlspecialchars($user) ?></strong></td>
                <td>
                    <?php if (($usuarios_validos[$user]['rol'] ?? '') === 'admin'): ?>
                        Acceso total (<code>*</code>)
                    <?php else: ?>
                        <?php foreach ($acciones_disponibles as $accion): ?>
                            <label>
                                <input type="checkbox" name="acciones_<?= $user ?>[]" value="<?= $accion ?>"
                                <?= in_array($accion, $permisos[$user] ?? []) ? 'checked' : '' ?>>
                                <?= $accion ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <button type="submit" name="guardar">💾 Guardar cambios</button>
</form>

<form action="dashboard.php" method="get">
    <button style="margin-top: 20px;">⬅️ Volver al panel</button>
</form>

</body>
</html>
