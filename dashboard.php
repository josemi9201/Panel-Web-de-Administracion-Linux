<?php
include_once 'inc/auth.php';
include_once 'templates/header.php';
?>

<h2>Panel de administración</h2>
<p>Bienvenido, <?= $_SESSION['usuario'] ?> | Rol: <?= $_SESSION['rol'] ?> | <a href="logout.php">Cerrar sesión</a></p>

<?php
if (isset($_SESSION['output'])) {
    echo "<pre>" . htmlspecialchars($_SESSION['output']) . "</pre>";
    unset($_SESSION['output']);
}
?>
<?php if ($_SESSION['usuario'] === 'admin'): ?>
    <h3>⚙️ Administración del panel</h3>
    <form method="get" action="gestionar_usuarios.php">
        <button>👤 Gestionar usuarios del panel</button>
    </form>
<?php endif; ?>


<?php if ($_SESSION['usuario'] === 'admin'): ?>
    <form action="gestionar_permisos.php" method="get">
        <button>🔐 Gestionar permisos de usuarios</button>
    </form>
<?php endif; ?>


<!-- 🛠️ SISTEMA Y MANTENIMIENTO -->
<h3>🛠️ Sistema y mantenimiento</h3>
<form method="post" action="acciones.php">
    <button name="accion" value="uso_sistema">Uso de CPU/RAM/DISCO</button>
    <button name="accion" value="ver_procesos">Procesos activos</button>
    <button name="accion" value="actualizar_sistema" onclick="return confirm('⚠️ Esto puede tomar un tiempo, ¿Esta seguro?')">Actualizar sistema</button>
    <button name="accion" value="limpiar_tmp">Limpiar /tmp</button>
    <button name="accion" value="reiniciar_apache">Reiniciar Apache</button>
    <button name="accion" value="reiniciar_sistema" onclick="return confirm('⚠️ ¿Seguro que deseas reiniciar el servidor?')">Reiniciar  sistema</button>
    <button name="accion" value="estado_servicios">Estado de Apache y SSH</button>
    <button name="accion" value="ver_uptime">⏱️ Ver uptime del servidor</button>
    <button name="accion" value="limpieza_avanzada" onclick="return confirm('¿Seguro que desea realizar una limpieza profunda del sistema, logs mayores de 7 dias, cache apt, journald y tmp?')">Ejecutar limpieza avanzada</button>
</form>
<form method="post" action="acciones.php" onsubmit="return confirm('¿Estás seguro de que deseas terminar este proceso?')">
    <input type="number" name="pid" placeholder="PID del proceso" required>
    <button name="accion" value="kill_pid">Finalizar proceso</button>
</form>
<form method="get" action="uso_grafico_simple.php">
    <button>📈 Ver uso del sistema en gráfico</button>
</form>

<!-- 👥 USUARIOS Y SEGURIDAD -->
<h3>👥 Usuarios y seguridad</h3>

<!-- Seguridad básica -->
<form method="post" action="acciones.php">
    <button name="accion" value="intentos_fallidos">Intentos fallidos de login</button>
    <button name="accion" value="usuarios_conectados">Usuarios conectados</button>
    <button name="accion" value="verificar_integridad">Verificar integridad de archivos clave</button>
</form>

<!-- Gestión de usuarios -->
<form method="post" action="acciones.php">
    <label>➕ Nuevo usuario:</label><br>
    <input type="text" name="nuevo_usuario" placeholder="Nombre de usuario" required>
    <input type="password" name="password_usuario" placeholder="Contraseña" required>
    <button name="accion" value="crear_usuario">Crear usuario</button>
</form>

<form method="post" action="acciones.php">
    <button name="accion" value="listar_usuarios">📋 Listar usuarios</button>
</form>

<form method="post" action="acciones.php">
    <label>🗑️ Eliminar usuario:</label><br>
    <input type="text" name="usuario_borrar" placeholder="Nombre de usuario" required>
    <button name="accion" value="eliminar_usuario">Eliminar</button>
</form>

<form method="post" action="acciones.php">
    <label>🔐 Cambiar contraseña:</label><br>
    <input type="text" name="usuario_cambiar" placeholder="Usuario" required>
    <input type="password" name="nueva_contrasena" placeholder="Nueva contraseña" required>
    <button name="accion" value="cambiar_password">Cambiar contraseña</button>
</form>

<!-- Firewall -->
<h4>🛡️ Firewall (UFW)</h4>
<form method="post" action="acciones.php">
    <button name="accion" value="ufw_estado">Estado del firewall</button>
    <button name="accion" value="puertos_abiertos">Puertos abiertos</button>
    <button name="accion" value="ufw_on">Activar</button>
    <button name="accion" value="ufw_off">Desactivar</button>
</form>

<form method="post" action="acciones.php">
    <label>➕ Agregar regla:</label>
    <input type="number" name="puerto" placeholder="Puerto">
    <select name="protocolo">
        <option value="tcp">TCP</option>
        <option value="udp">UDP</option>
    </select>
    <button name="accion" value="agregar_regla">Agregar</button>
</form>

<form method="post" action="acciones.php">
    <label>➖ Eliminar regla:</label>
    <input type="text" name="regla" placeholder="Ej: 8080/tcp">
    <button name="accion" value="eliminar_regla">Eliminar</button>
</form>

<!-- 📁 BACKUPS, PAQUETES Y CRON -->
<h3>📁 Backups, paquetes y cron</h3>

<!-- Backups -->
<form method="post" action="acciones.php">
    <button name="accion" value="hacer_backup">Crear backup</button>
    <button name="accion" value="eliminar_backups">Eliminar backups antiguos</button>
    <button name="accion" value="listar_backups">Listar backups</button>
    <button name="accion" value="descargar_backup">⬇️ Descargar último backup</button>
</form>
<form method="post" action="acciones.php" onsubmit="return confirm('⚠️ ¿Seguro que deseas eliminar TODOS los backups?')">
    <button name="accion" value="eliminar_todos_los_backups">🗑️ Eliminar TODOS</button>
</form>

<!-- Paquetes -->
<form method="post" action="acciones.php">
    <input type="text" name="paquete" placeholder="Nombre del paquete">
    <button name="accion" value="instalar_paquete">📦 Instalar</button>
</form>

<!-- Cronjobs -->
<form method="post" action="acciones.php">
    <button name="accion" value="ver_crontab_usuario">Crontab de usuario</button>
    <button name="accion" value="ver_crontab_sistema">Crontab del sistema</button>
</form>

<!-- 📡 RED Y DIAGNÓSTICO -->
<h3>📡 Red y diagnóstico</h3>
<form method="post" action="acciones.php">
    <button name="accion" value="ver_conexiones">Conexiones activas</button>
    <button name="accion" value="ver_logs">Ver syslog</button>
    <button name="accion" value="ver_logs_apache">Ver logs de Apache</button>
    <button name="accion" value="diagnostico_red" onclick="return confirm('⚠️Esta accion puede llegar a tardar minutos, ¿Esta seguro?')">Diagnóstico completo</button>
</form>

<?php include_once 'templates/footer.php'; ?>
