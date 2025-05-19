<?php
include_once 'inc/auth.php';
include_once 'inc/roles.php';
include_once 'templates/header.php';
?>

<h2>Panel de administración</h2>
<p>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> | Rol: <?= htmlspecialchars($_SESSION['rol']) ?> | <a href="logout.php">Cerrar sesión</a></p>

<?php
if (isset($_SESSION['output'])) {
    echo "<pre>" . htmlspecialchars($_SESSION['output']) . "</pre>";
    unset($_SESSION['output']);
}
?>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="admin-panel" id="admin-panel-button">
        ⚙️ Administración del panel
        <svg class="accordion-icon" viewBox="0 0 24 24">
          <path d="M8 5v14l11-7z"/>
        </svg>
    </button>
    <div class="accordion-content" id="admin-panel" role="region" aria-labelledby="admin-panel-button">
        <?php if ($_SESSION['rol'] === 'admin' || (isset($roles[$_SESSION['rol']]) && in_array('*', $roles[$_SESSION['rol']]))): ?>
            <form method="get" action="gestionar_usuarios.php">
                <button>👤 Gestionar usuarios del panel</button>
            </form>
            <form method="get" action="gestionar_roles.php">
                <button>🔐 Gestionar roles y permisos</button>
            </form>
        <?php else: ?>
            <p>❌ No tienes permisos para esta sección.</p>
        <?php endif; ?>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="sistema" id="sistema-button">
        🛠️ Sistema y mantenimiento
        <svg class="accordion-icon" viewBox="0 0 24 24">
          <path d="M8 5v14l11-7z"/>
        </svg>
    </button>
    <div class="accordion-content" id="sistema" role="region" aria-labelledby="sistema-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('uso_sistema')): ?><button name="accion" value="uso_sistema">Uso de sistema</button><?php endif; ?>
            <?php if (tiene_permiso('ver_procesos')): ?><button name="accion" value="ver_procesos">Procesos activos</button><?php endif; ?>
            <?php if (tiene_permiso('actualizar_sistema')): ?><button name="accion" value="actualizar_sistema">Actualizar sistema</button><?php endif; ?>
            <?php if (tiene_permiso('limpiar_tmp')): ?><button name="accion" value="limpiar_tmp">Limpiar /tmp</button><?php endif; ?>
            <?php if (tiene_permiso('reiniciar_apache')): ?><button name="accion" value="reiniciar_apache">Reiniciar Apache</button><?php endif; ?>
            <?php if (tiene_permiso('reiniciar_sistema')): ?><button name="accion" value="reiniciar_sistema" onclick="return confirm('⚠️ ¿Seguro que deseas reiniciar el sistema?')">Reiniciar sistema</button><?php endif; ?>
            <?php if (tiene_permiso('estado_servicios')): ?><button name="accion" value="estado_servicios">Estado Apache y SSH</button><?php endif; ?>
            <?php if (tiene_permiso('ver_uptime')): ?><button name="accion" value="ver_uptime">⏱️ Ver uptime</button><?php endif; ?>
            <?php if (tiene_permiso('limpieza_avanzada')): ?><button name="accion" value="limpieza_avanzada">Limpieza avanzada</button><?php endif; ?>
        </form>

        <form method="post" action="acciones.php" onsubmit="return confirm('¿Seguro que deseas terminar este proceso?')">
            <input type="number" name="pid" placeholder="PID del proceso" required>
            <?php if (tiene_permiso('kill_pid')): ?>
                <button name="accion" value="kill_pid">Finalizar proceso</button>
            <?php endif; ?>
        </form>

        <form method="get" action="uso_grafico_simple.php">
            <button>📈 Ver uso del sistema en gráfico</button>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="backups" id="backups-button">
        📁 Backups
        <svg class="accordion-icon" viewBox="0 0 24 24">
          <path d="M8 5v14l11-7z"/>
        </svg>
    </button>
    <div class="accordion-content" id="backups" role="region" aria-labelledby="backups-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('hacer_backup')): ?><button name="accion" value="hacer_backup">Crear backup</button><?php endif; ?>
            <?php if (tiene_permiso('listar_backups')): ?><button name="accion" value="listar_backups">Listar backups</button><?php endif; ?>
            <?php if (tiene_permiso('eliminar_backups')): ?><button name="accion" value="eliminar_backups">Eliminar backups antiguos</button><?php endif; ?>
            <?php if (tiene_permiso('eliminar_todos_los_backups')): ?><button name="accion" value="eliminar_todos_los_backups">Eliminar TODOS los backups</button><?php endif; ?>
            <?php if (tiene_permiso('descargar_backup')): ?><button name="accion" value="descargar_backup">Descargar último backup</button><?php endif; ?>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="red" id="red-button">
        📡 Red y diagnósticos
        <svg class="accordion-icon" viewBox="0 0 24 24">
          <path d="M8 5v14l11-7z"/>
        </svg>
    </button>
    <div class="accordion-content" id="red" role="region" aria-labelledby="red-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('ver_conexiones')): ?><button name="accion" value="ver_conexiones">Ver conexiones activas</button><?php endif; ?>
            <?php if (tiene_permiso('ver_logs')): ?><button name="accion" value="ver_logs">Ver syslog</button><?php endif; ?>
            <?php if (tiene_permiso('ver_logs_apache')): ?><button name="accion" value="ver_logs_apache">Ver logs Apache</button><?php endif; ?>
            <?php if (tiene_permiso('diagnostico_red')): ?><button name="accion" value="diagnostico_red" onclick="return confirm('Esta acción puede tardar minutos, ¿estás seguro?')">Diagnóstico completo</button><?php endif; ?>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="seguridad" id="seguridad-button">
        🔒 Seguridad
        <svg class="accordion-icon" viewBox="0 0 24 24">
          <path d="M8 5v14l11-7z"/>
        </svg>
    </button>
    <div class="accordion-content" id="seguridad" role="region" aria-labelledby="seguridad-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('intentos_fallidos')): ?><button name="accion" value="intentos_fallidos">Intentos fallidos</button><?php endif; ?>
            <?php if (tiene_permiso('usuarios_conectados')): ?><button name="accion" value="usuarios_conectados">Usuarios conectados</button><?php endif; ?>
            <?php if (tiene_permiso('verificar_integridad')): ?><button name="accion" value="verificar_integridad">Verificar integridad de archivos</button><?php endif; ?>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="usuarios" id="usuarios-button">
        👥 Usuarios y seguridad
        <svg class="accordion-icon" viewBox="0 0 24 24">
          <path d="M8 5v14l11-7z"/>
        </svg>
    </button>
    <div class="accordion-content" id="usuarios" role="region" aria-labelledby="usuarios-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('crear_usuario')): ?>
                <label>➕ Nuevo usuario:</label><br>
                <input type="text" name="nuevo_usuario" placeholder="Nombre de usuario" required>
                <input type="password" name="password_usuario" placeholder="Contraseña" required>
                <button name="accion" value="crear_usuario">Crear usuario</button>
            <?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('listar_usuarios')): ?>
                <button name="accion" value="listar_usuarios">📋 Listar usuarios</button>
            <?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('eliminar_usuario')): ?>
                <label>🗑️ Eliminar usuario:</label><br>
                <input type="text" name="usuario_borrar" placeholder="Nombre de usuario" required>
                <button name="accion" value="eliminar_usuario">Eliminar</button>
            <?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('cambiar_password')): ?>
                <label>🔐 Cambiar contraseña:</label><br>
                <input type="text" name="usuario_cambiar" placeholder="Usuario" required>
                <input type="password" name="nueva_contrasena" placeholder="Nueva contraseña" required>
                <button name="accion" value="cambiar_password">Cambiar contraseña</button>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="firewall" id="firewall-button">
        🛡️ Firewall (UFW)
        <svg class="accordion-icon" viewBox="0 0 24 24">
          <path d="M8 5v14l11-7z"/>
        </svg>
    </button>
    <div class="accordion-content" id="firewall" role="region" aria-labelledby="firewall-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('ufw_estado')): ?><button name="accion" value="ufw_estado">Estado del firewall</button><?php endif; ?>
            <?php if (tiene_permiso('puertos_abiertos')): ?><button name="accion" value="puertos_abiertos">Puertos abiertos</button><?php endif; ?>
            <?php if (tiene_permiso('ufw_on')): ?><button name="accion" value="ufw_on">Activar firewall</button><?php endif; ?>
            <?php if (tiene_permiso('ufw_off')): ?><button name="accion" value="ufw_off">Desactivar firewall</button><?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('agregar_regla')): ?>
                <label>➕ Agregar regla:</label>
                <input type="number" name="puerto" placeholder="Puerto">
                <select name="protocolo">
                    <option value="tcp">TCP</option>
                    <option value="udp">UDP</option>
                </select>
                <button name="accion" value="agregar_regla">Agregar</button>
            <?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('eliminar_regla')): ?>
                <label>➖ Eliminar regla:</label>
                <input type="text" name="regla" placeholder="Ej: 8080/tcp">
                <button name="accion" value="eliminar_regla">Eliminar</button>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.accordion-button').forEach(button => {
    button.addEventListener('click', () => {
        const expanded = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', !expanded);
        const content = document.getElementById(button.getAttribute('aria-controls'));
        if (!expanded) {
            content.classList.add('show');
        } else {
            content.classList.remove('show');
        }
    });
});
</script>

<?php include_once 'templates/footer.php'; ?>
