[200~<?php
include_once 'inc/auth.php';
if ($_SESSION['usuario'] !== 'admin') {
    exit("❌ Solo el usuario admin puede gestionar usuarios del panel.");
}

$archivo_usuarios = 'inc/usuarios.php';
$archivo_permisos = 'inc/permisos.php';

include_once $archivo_usuarios;
include_once $archivo_permisos;

// Definir permisos por rol
$permisos_por_rol = [
    "admin" => ["*"],
    "backup" => ["hacer_backup", "listar_backups", "eliminar_backups", "eliminar_todos_los_backups", "descargar_backup"],
    "sistema" => ["uso_sistema", "ver_procesos", "ver_conexiones", "ver_logs", "ver_logs_apache", "ver_uptime", "usuarios_conectados"]
];

$usuarios = $usuarios_validos;

// Añadir usuario
if (isset($_POST['nuevo_usuario'], $_POST['nueva_contra'], $_POST['nuevo_rol'])) {
    $nuevo = $_POST['nuevo_usuario'];
    $contra = $_POST['nueva_contra'];
    $rol = $_POST['nuevo_rol'];

    if (!isset($usuarios[$nuevo])) {
        $usuarios[$nuevo] = [
            'hash' => password_hash($contra, PASSWORD_DEFAULT),
            'rol' => $rol
        ];

        // Asignar permisos según el rol
        $permisos[$nuevo] = $permisos_por_rol[$rol] ?? [];

        // Guardar archivo de permisos
        file_put_contents($archivo_permisos, "<?php\n\$permisos = " . var_export($permisos, true) . ";\n");

        $mensaje = "✅ Usuario '$nuevo' añadido con rol '$rol' y permisos por defecto.";
    } else {
        $mensaje = "⚠️ El usuario ya existe.";
    }
}

// Eliminar usuario
if (isset($_POST['borrar_usuario']) && $_POST['borrar_usuario'] !== 'admin') {
    $borrar = $_POST['borrar_usuario'];
    unset($usuarios[$borrar]);
    unset($permisos[$borrar]);

    file_put_contents($archivo_permisos, "<?php\n\$permisos = " . var_export($permisos, true) . ";\n");

    $mensaje = "🗑️ Usuario '$borrar' eliminado.";
}

// Cambiar contraseña
if (isset($_POST['usuario_cambiar'], $_POST['nueva_pass'])) {
    $u = $_POST['usuario_cambiar'];
    if (isset($usuarios[$u])) {
        $usuarios[$u]['hash'] = password_hash($_POST['nueva_pass'], PASSWORD_DEFAULT);
        $mensaje = "🔐 Contraseña de '$u' actualizada.";
    }
}

// Guardar archivo actualizado de usuarios
$exportar = "<?php\n\$usuarios_validos = " . var_export($usuarios, true) . ";\n";
file_put_contents($archivo_usuarios, $exportar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de usuarios del panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>👤 Gestión de usuarios del panel</h2>
<?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>

<!-- Crear nuevo usuario -->
<h3>➕ Crear nuevo usuario</h3>
<form method="post">
    <input type="text" name="nuevo_usuario" placeholder="Usuario" required>
    <input type="password" name="nueva_contra" placeholder="Contraseña" required>
    <select name="nuevo_rol" required>
        <option value="admin">admin</option>
        <option value="backup">backup</option>
        <option value="sistema">sistema</option>
    </select>
    <button type="submit">Crear usuario</button>
</form>

<!-- Lista de usuarios -->
<h3>📋 Usuarios actuales</h3>
<table border="1" cellpadding="8">
    <tr><th>Usuario</th><th>Rol</th><th>Acciones</th></tr>
    <?php foreach ($usuarios as $u => $info): ?>
        <tr>
            <td><?= htmlspecialchars($u) ?></td>
            <td><?= htmlspecialchars($info['rol']) ?></td>
            <td>
                <?php if ($u !== 'admin'): ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="borrar_usuario" value="<?= $u ?>">
                        <button onclick="return confirm('¿Borrar usuario <?= $u ?>?')">Eliminar</button>
                    </form>
                <?php endif; ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="usuario_cambiar" value="<?= $u ?>">
                    <input type="password" name="nueva_pass" placeholder="Nueva contraseña" required>
                    <button>Cambiar contraseña</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<form method="get" action="dashboard.php">
    <button style="margin-top: 20px;">⬅️ Volver al panel</button>
</form>
</body>
</html>
