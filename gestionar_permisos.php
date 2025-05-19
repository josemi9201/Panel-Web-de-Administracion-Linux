<?php
include_once 'inc/auth.php';
include_once 'inc/roles.php';

if ($_SESSION['rol'] !== 'admin') {
    exit("❌ Solo el administrador puede gestionar roles.");
}

$archivo_roles = __DIR__ . '/inc/roles.php';

$acciones_disponibles = [
    "hacer_backup", "listar_backups", "eliminar_backups", "eliminar_todos_los_backups", "descargar_backup",
    "uso_sistema", "ver_procesos", "ver_conexiones", "ver_logs", "ver_logs_apache", "ver_uptime",
    "usuarios_conectados", "intentos_fallidos", "actualizar_sistema", "reiniciar_sistema"
];

// Procesar eliminación
if (isset($_POST['eliminar_rol']) && $_POST['eliminar_rol'] !== 'admin') {
    $rol_a_eliminar = $_POST['eliminar_rol'];
    unset($roles[$rol_a_eliminar]);
}

// Procesar cambios
if (isset($_POST['guardar'])) {
    $nuevos_roles = $roles;

    foreach ($_POST as $key => $acciones) {
        if (str_starts_with($key, 'acciones_')) {
            $rol = substr($key, 9);
            $nuevos_roles[$rol] = $acciones;
        }
    }

    // Crear nuevo rol
    if (!empty($_POST['nuevo_rol'])) {
        $nuevo_rol = trim($_POST['nuevo_rol']);
        if (preg_match('/^[a-zA-Z0-9_-]+$/', $nuevo_rol) && !isset($nuevos_roles[$nuevo_rol])) {
            $nuevos_roles[$nuevo_rol] = [];
        }
    }

    // Asegurar admin
    $nuevos_roles['admin'] = ['*'];

    // Guardar roles
    $contenido = "<?php\n\n";
    $contenido .= "\$roles = " . var_export($nuevos_roles, true) . ";\n\n";
    $contenido .= "function tiene_permiso(\$accion) {\n";
    $contenido .= "    global \$roles;\n";
    $contenido .= "    \$rol = \$_SESSION['rol'] ?? null;\n";
    $contenido .= "    if (!\$rol || !isset(\$roles[\$rol])) return false;\n";
    $contenido .= "    return in_array('*', \$roles[\$rol]) || in_array(\$accion, \$roles[\$rol]);\n";
    $contenido .= "}\n";

    file_put_contents($archivo_roles, $contenido);
    include $archivo_roles;
    header("Location: gestionar_roles.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de roles</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; vertical-align: top; text-align: left; }
        label { display: inline-block; margin-right: 8px; margin-bottom: 4px; }
        .acciones-form { display: inline-block; margin-left: 12px; }
        button[type="submit"] { cursor: pointer; }
    </style>
</head>
<body>

<h2>🔐 Gestión de permisos por rol</h2>

<form method="post">
    <table>
        <tr>
            <th>Rol</th>
            <th>Permisos asignados</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($roles as $rol => $acciones): ?>
            <tr>
                <td><strong><?= htmlspecialchars($rol) ?></strong></td>
                <td>
                    <?php if ($rol === 'admin'): ?>
                        Acceso total (<code>*</code>)
                    <?php else: ?>
                        <?php foreach ($acciones_disponibles as $accion): ?>
                            <label>
                                <input type="checkbox" name="acciones_<?= $rol ?>[]" value="<?= $accion ?>"
                                <?= in_array($accion, $acciones) ? 'checked' : '' ?>>
                                <?= $accion ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($rol !== 'admin'): ?>
                        <button type="submit" name="eliminar_rol" value="<?= htmlspecialchars($rol) ?>"
                            formaction="gestionar_roles.php"
                            onclick="return confirm('¿Eliminar el rol <?= $rol ?>?')">🗑 Eliminar</button>
                    <?php else: ?>
                        🔒 Protegido
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>➕ Crear nuevo rol</h3>
    <input type="text" name="nuevo_rol" placeholder="Nombre del nuevo rol">
    <br><br>

    <button type="submit" name="guardar">💾 Guardar cambios</button>
</form>

<form method="get" action="dashboard.php">
    <button style="margin-top: 20px;">⬅️ Volver al panel</button>
</form>

</body>
</html>
