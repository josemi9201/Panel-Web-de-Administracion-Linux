<?php
include_once 'inc/auth.php';
include_once 'inc/roles.php';
include_once 'inc/usuarios.php';

if ($_SESSION['rol'] !== 'admin') {
    exit("❌ Solo el administrador puede gestionar roles.");
}

$archivo_roles = __DIR__ . '/inc/roles.php';

$acciones_disponibles = [
    // Backups
    "hacer_backup", "listar_backups", "eliminar_backups", "eliminar_todos_los_backups", "descargar_backup",
    // Sistema
    "uso_sistema", "ver_procesos", "kill_pid", "reiniciar_apache", "actualizar_sistema",
    "limpiar_tmp", "reiniciar_sistema", "limpieza_avanzada", "estado_servicios", "ver_uptime", "ver_uso_grafico",
    // Seguridad
    "intentos_fallidos", "usuarios_conectados", "verificar_integridad",
    // Usuarios
    "crear_usuario", "eliminar_usuario", "cambiar_password", "listar_usuarios",
    // Firewall
    "ufw_estado", "puertos_abiertos", "agregar_regla", "eliminar_regla", "ufw_on", "ufw_off",
    // Red
    "diagnostico_red", "ver_conexiones",
    // Crontab
    "ver_crontab_usuario", "ver_crontab_sistema",
    // Logs
    "ver_logs", "ver_logs_apache"
];

// --- Eliminar rol ---
if (isset($_POST['borrar_rol'])) {
    $rol_a_borrar = $_POST['borrar_rol'];

    // Comprobar que no hay usuarios con ese rol asignado
    $usuarios_con_rol = array_filter($usuarios_validos, fn($u) => ($u['rol'] ?? '') === $rol_a_borrar);

    if (count($usuarios_con_rol) > 0) {
        $mensaje = "⚠️ No se puede eliminar el rol '$rol_a_borrar' porque hay usuarios asignados a él.";
    } else {
        if (isset($roles[$rol_a_borrar])) {
            unset($roles[$rol_a_borrar]);

            // Guardar archivo actualizado
            $contenido = "<?php\n\n";
            $contenido .= "\$roles = " . var_export($roles, true) . ";\n\n";
            $contenido .= "function tiene_permiso(\$accion) {\n";
            $contenido .= "    global \$roles;\n";
            $contenido .= "    \$rol = \$_SESSION['rol'] ?? null;\n";
            $contenido .= "    if (!\$rol || !isset(\$roles[\$rol])) return false;\n";
            $contenido .= "    return in_array('*', \$roles[\$rol]) || in_array(\$accion, \$roles[\$rol]);\n";
            $contenido .= "}\n";

            if (file_put_contents($archivo_roles, $contenido) !== false) {
                $mensaje = "✅ Rol '$rol_a_borrar' eliminado correctamente.";
            } else {
                $mensaje = "❌ Error al eliminar el rol.";
            }
        } else {
            $mensaje = "⚠️ El rol '$rol_a_borrar' no existe.";
        }
    }
}

// --- Crear nuevo rol ---
if (isset($_POST['nuevo_rol']) && !isset($roles[$_POST['nuevo_rol']])) {
    $nuevo_rol = trim($_POST['nuevo_rol']);
    if ($nuevo_rol !== '' && preg_match('/^[a-zA-Z0-9_-]+$/', $nuevo_rol)) {
        if (!isset($roles[$nuevo_rol])) {
            $roles[$nuevo_rol] = [];
            $mensaje = "✅ Rol '$nuevo_rol' creado.";
        } else {
            $mensaje = "⚠️ El rol '$nuevo_rol' ya existe.";
        }
    } else {
        $mensaje = "❌ Nombre de rol inválido.";
    }
}

// --- Guardar permisos modificados ---
if (isset($_POST['guardar'])) {
    $nuevos_roles = [];

    foreach ($_POST as $key => $acciones) {
        if (str_starts_with($key, 'acciones_')) {
            $rol = substr($key, 9);
            $nuevos_roles[$rol] = is_array($acciones) ? $acciones : [];
        }
    }

    // Asegurar que los roles existentes que no se enviaron no se pierdan (opcional)
    foreach ($roles as $rol_existente => $perms) {
        if (!isset($nuevos_roles[$rol_existente])) {
            $nuevos_roles[$rol_existente] = $perms;
        }
    }

    $roles = $nuevos_roles;

    $contenido = "<?php\n\n";
    $contenido .= "\$roles = " . var_export($roles, true) . ";\n\n";
    $contenido .= "function tiene_permiso(\$accion) {\n";
    $contenido .= "    global \$roles;\n";
    $contenido .= "    \$rol = \$_SESSION['rol'] ?? null;\n";
    $contenido .= "    if (!\$rol || !isset(\$roles[\$rol])) return false;\n";
    $contenido .= "    return in_array('*', \$roles[\$rol]) || in_array(\$accion, \$roles[\$rol]);\n";
    $contenido .= "}\n";

    if (file_put_contents($archivo_roles, $contenido) !== false) {
        $mensaje = "✅ Permisos guardados correctamente.";
    } else {
        $mensaje = "❌ Error al guardar los permisos.";
    }
}

// --- HTML y formulario ---

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
        label { display: inline-block; margin-right: 8px; }
        form.inline { display: inline-block; margin: 0; }
    </style>
</head>
<body>

<h2>🔐 Gestión de permisos por rol</h2>

<?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>

<!-- Crear nuevo rol -->
<form method="post" style="margin-bottom: 1em;">
    <input type="text" name="nuevo_rol" placeholder="Nombre del nuevo rol" required pattern="[a-zA-Z0-9_-]+"
           title="Solo letras, números, guiones y guiones bajos">
    <button>➕ Crear nuevo rol</button>
</form>

<form method="post">
    <table>
        <thead>
        <tr>
            <th>Rol</th>
            <th>Permisos asignados</th>
            <th>Eliminar rol</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($roles as $rol => $acciones): ?>
            <tr>
                <td><strong><?= htmlspecialchars($rol) ?></strong></td>
                <td>
                    <?php if ($rol === 'admin'): ?>
                        Acceso total (<code>*</code>)
                    <?php else: ?>
                        <?php foreach ($acciones_disponibles as $accion): ?>
                            <label>
                                <input type="checkbox" name="acciones_<?= htmlspecialchars($rol) ?>[]" value="<?= htmlspecialchars($accion) ?>"
                                    <?= in_array($accion, $acciones) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($accion) ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($rol !== 'admin'): ?>
                        <form method="post" class="inline" onsubmit="return confirm('¿Eliminar rol <?= htmlspecialchars($rol) ?>?');">
                            <input type="hidden" name="borrar_rol" value="<?= htmlspecialchars($rol) ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <button type="submit" name="guardar">💾 Guardar cambios</button>
</form>

<form method="get" action="dashboard.php" style="margin-top:1em;">
    <button>⬅️ Volver al panel</button>
</form>

</body>
</html>
