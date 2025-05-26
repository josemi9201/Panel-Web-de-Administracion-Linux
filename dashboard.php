<?php
include_once 'inc/auth.php';
include_once 'inc/roles.php';
include_once 'templates/header.php';

// Cargar servidores remotos desde archivo JSON
$archivoRemotos = __DIR__ . '/remotos.json';
$servidores = [];
if (file_exists($archivoRemotos)) {
    $servidores = json_decode(file_get_contents($archivoRemotos), true);
    if (!is_array($servidores)) $servidores = [];
}

$servidor_remoto_activo = $_SESSION['remoto']['host'] ?? null;
$usuario_remoto_activo = $_SESSION['remoto']['usuario'] ?? null;

// Definir texto para mostrar conexión actual
if ($servidor_remoto_activo && $usuario_remoto_activo) {
    $conexion_actual = htmlspecialchars($usuario_remoto_activo . '@' . $servidor_remoto_activo);
} else {
    $conexion_actual = 'Local';
}
?>

<h2>Panel de administración</h2>
<p>
    Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> | 
    Rol: <?= htmlspecialchars($_SESSION['rol']) ?> | 
    Conectado a: <strong><?= $conexion_actual ?></strong> | 
    <a href="logout.php">Cerrar sesión</a>
</p>

<?php if (isset($_SESSION['output'])): ?>
    <pre><?= htmlspecialchars($_SESSION['output']) ?></pre>
    <?php unset($_SESSION['output']); ?>
<?php endif; ?>



<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="scripts" id="scripts-button">
        📤 Subir scripts al servidor remoto
        <svg class="accordion-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="accordion-content" id="scripts" role="region" aria-labelledby="scripts-button">
        <form method="get" action="subir_scripts_remotos.php" target="_blank" style="margin-top: 10px;">
            <button title="Envía todos los scripts necesarios a un servidor remoto para habilitar funciones del panel">Ejecutar subida de scripts</button>
            <p class="descripcion-boton">Envía scripts esenciales al servidor remoto seleccionado para que las funciones del panel funcionen correctamente.</p>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="conexion-remota" id="conexion-remota-button">
        🌐 Conexión remota
        <svg class="accordion-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="accordion-content" id="conexion-remota" role="region" aria-labelledby="conexion-remota-button">
        <form method="post" action="acciones.php" style="margin-bottom: 20px;">
            <label>Seleccionar servidor remoto activo:</label>
            <select name="servidor_remoto_seleccionado" required>
                <option value="local" <?= $servidor_remoto_activo === null ? 'selected' : '' ?>>-- Ninguno (Local) --</option>
                <?php foreach ($servidores as $host => $data): ?>
                    <option value="<?= htmlspecialchars($host) ?>" <?= ($host === $servidor_remoto_activo) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($host . " (Usuario: " . $data['usuario'] . ")") ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" title="Selecciona el servidor remoto para ejecutar las acciones del panel">Activar</button>
            <p class="descripcion-boton">Activa o desactiva la conexión remota para ejecutar comandos en otro servidor.</p>
        </form>

        <form method="post" action="acciones.php" style="margin-bottom: 20px;">
            <h4>Añadir servidor remoto</h4>
            <input type="text" name="nuevo_host" placeholder="Host o IP" required title="Dirección IP o nombre del servidor remoto">
            <input type="text" name="nuevo_usuario" placeholder="Usuario SSH" required title="Nombre de usuario para conexión SSH">
            <input type="password" name="nuevo_clave" placeholder="Contraseña SSH" required title="Contraseña del usuario SSH">
            <button type="submit" title="Agregar un nuevo servidor remoto para conexión SSH">Agregar servidor</button>
            <p class="descripcion-boton">Añade un nuevo servidor remoto para conectar y ejecutar comandos desde el panel.</p>
        </form>

        <?php if ($servidores): ?>
            <h4>Servidores remotos guardados</h4>
            <form method="post" action="acciones.php">
                <select name="eliminar_servidor" required title="Selecciona un servidor para eliminarlo">
                    <option value="">-- Selecciona para eliminar --</option>
                    <?php foreach ($servidores as $host => $data): ?>
                        <option value="<?= htmlspecialchars($host) ?>">
                            <?= htmlspecialchars($host . " (Usuario: " . $data['usuario'] . ")") ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" onclick="return confirm('¿Seguro que quieres eliminar este servidor?')" title="Eliminar servidor remoto guardado">Eliminar servidor</button>
                <p class="descripcion-boton">Elimina un servidor remoto de la lista guardada.</p>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="admin-panel" id="admin-panel-button">
        ⚙️ Administración del panel
        <svg class="accordion-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="accordion-content" id="admin-panel" role="region" aria-labelledby="admin-panel-button">
        <?php if ($_SESSION['rol'] === 'admin' || (isset($roles[$_SESSION['rol']]) && in_array('*', $roles[$_SESSION['rol']]))): ?>
            <form method="get" action="gestionar_usuarios.php">
                <button title="Gestiona los usuarios que pueden acceder al panel">👤 Gestionar usuarios del panel</button>
                <p class="descripcion-boton">Añade, modifica o elimina usuarios del panel de administración.</p>
            </form>
            <form method="get" action="gestionar_roles.php">
                <button title="Gestiona los roles y permisos de usuarios">🔐 Gestionar roles y permisos</button>
                <p class="descripcion-boton">Crea y asigna roles para controlar accesos y permisos.</p>
            </form>
            <form method="get" action="ver_logs_acciones.php">
                <button title="Consulta el registro de acciones realizadas en el panel">📜 Ver log de acciones</button>
                <p class="descripcion-boton">Revisa el historial de acciones para auditoría y control.</p>
            </form>
        <?php else: ?>
            <p>❌ No tienes permisos para esta sección.</p>
        <?php endif; ?>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="sistema" id="sistema-button">
        🛠️ Sistema y mantenimiento
        <svg class="accordion-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="accordion-content" id="sistema" role="region" aria-labelledby="sistema-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('uso_sistema')): ?>
                <button name="accion" value="uso_sistema" title="Muestra información básica del uso de CPU, RAM y disco">Uso de sistema</button>
                <p class="descripcion-boton">Visualiza el uso actual de CPU, memoria RAM y espacio en disco.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('ver_procesos')): ?>
                <button name="accion" value="ver_procesos" title="Lista los 10 procesos que más memoria consumen">Procesos activos</button>
                <p class="descripcion-boton">Muestra los procesos más intensivos en memoria RAM.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('actualizar_sistema')): ?>
                <button name="accion" value="actualizar_sistema" title="Actualiza el sistema operativo y sus paquetes">Actualizar sistema</button>
                <p class="descripcion-boton">Realiza la actualización completa del sistema.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('limpiar_tmp')): ?>
                <button name="accion" value="limpiar_tmp" title="Elimina archivos temporales para liberar espacio">Limpiar /tmp</button>
                <p class="descripcion-boton">Borra los archivos temporales que ya no son necesarios.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('reiniciar_apache')): ?>
                <button name="accion" value="reiniciar_apache" title="Reinicia el servidor web Apache">Reiniciar Apache</button>
                <p class="descripcion-boton">Reinicia el servicio Apache para aplicar cambios.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('reiniciar_sistema')): ?>
                <button name="accion" value="reiniciar_sistema" onclick="return confirm('⚠️ ¿Seguro que deseas reiniciar el sistema?')" title="Reinicia todo el sistema operativo">Reiniciar sistema</button>
                <p class="descripcion-boton">Reinicia el servidor. ¡Ten cuidado al usar esta opción!</p>
            <?php endif; ?>
            <?php if (tiene_permiso('estado_servicios')): ?>
                <button name="accion" value="estado_servicios" title="Muestra el estado de Apache y SSH">Estado Apache y SSH</button>
                <p class="descripcion-boton">Verifica si los servicios Apache y SSH están activos.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('ver_uptime')): ?>
                <button name="accion" value="ver_uptime" title="Muestra el tiempo que lleva encendido el sistema">⏱️ Ver uptime</button>
                <p class="descripcion-boton">Consulta el tiempo de actividad del sistema.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('limpieza_avanzada')): ?>
                <button name="accion" value="limpieza_avanzada" title="Ejecuta tareas avanzadas de limpieza del sistema">Limpieza avanzada</button>
                <p class="descripcion-boton">Ejecuta scripts para una limpieza profunda y mantenimiento.</p>
            <?php endif; ?>
        </form>

        <?php if (tiene_permiso('kill_pid')): ?>
            <form method="post" action="acciones.php" onsubmit="return confirm('¿Seguro que deseas terminar este proceso?')">
                <input type="number" name="pid" placeholder="PID del proceso" required title="Número de proceso a finalizar">
                <button name="accion" value="kill_pid" title="Finaliza un proceso por su PID">Finalizar proceso</button>
                <p class="descripcion-boton">Termina un proceso que esté consumiendo recursos.</p>
            </form>
        <?php endif; ?>

        <?php if (tiene_permiso('ver_uso_grafico')): ?>
            <form method="get" action="uso_grafico_simple.php">
                <button title="Muestra gráficos del uso del sistema">📈 Ver uso del sistema en gráfico</button>
                <p class="descripcion-boton">Visualiza el uso de CPU, memoria y disco en gráficos.</p>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="backups" id="backups-button">
        📁 Backups
        <svg class="accordion-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="accordion-content" id="backups" role="region" aria-labelledby="backups-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('hacer_backup')): ?>
                <button name="accion" value="hacer_backup" title="Crea una copia de seguridad del sistema">Crear backup</button>
                <p class="descripcion-boton">Genera un backup comprimido de carpetas esenciales.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('listar_backups')): ?>
                <button name="accion" value="listar_backups" title="Lista los backups existentes">Listar backups</button>
                <p class="descripcion-boton">Muestra los archivos de backup guardados.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('eliminar_backups')): ?>
                <button name="accion" value="eliminar_backups" title="Elimina backups antiguos">Eliminar backups antiguos</button>
                <p class="descripcion-boton">Borra backups con más de 7 días para liberar espacio.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('eliminar_todos_los_backups')): ?>
                <button name="accion" value="eliminar_todos_los_backups" title="Elimina todos los backups">Eliminar TODOS los backups</button>
                <p class="descripcion-boton">Elimina completamente todos los archivos de backup.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('descargar_backup')): ?>
                <button name="accion" value="descargar_backup" title="Descarga el último backup creado">Descargar último backup</button>
                <p class="descripcion-boton">Permite descargar el backup más reciente.</p>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="red" id="red-button">
        📡 Red y diagnósticos
        <svg class="accordion-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="accordion-content" id="red" role="region" aria-labelledby="red-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('ver_conexiones')): ?>
                <button name="accion" value="ver_conexiones" title="Muestra conexiones de red activas">Ver conexiones activas</button>
                <p class="descripcion-boton">Lista las conexiones establecidas actualmente en el sistema.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('ver_logs')): ?>
                <button name="accion" value="ver_logs" title="Muestra los últimos eventos del sistema">Ver syslog</button>
                <p class="descripcion-boton">Consulta los últimos mensajes del sistema para diagnóstico.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('ver_logs_apache')): ?>
                <button name="accion" value="ver_logs_apache" title="Muestra los logs de error de Apache">Ver logs Apache</button>
                <p class="descripcion-boton">Revisa errores recientes del servidor web Apache.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('diagnostico_red')): ?>
                <button name="accion" value="diagnostico_red" onclick="return confirm('Esta acción puede tardar minutos, ¿estás seguro?')" title="Ejecuta diagnóstico completo de red">Diagnóstico completo</button>
                <p class="descripcion-boton">Realiza un análisis exhaustivo de la red, puede tardar varios minutos.</p>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="seguridad" id="seguridad-button">
        🔒 Seguridad
        <svg class="accordion-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="accordion-content" id="seguridad" role="region" aria-labelledby="seguridad-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('intentos_fallidos')): ?>
                <button name="accion" value="intentos_fallidos" title="Muestra intentos fallidos de acceso">Intentos fallidos</button>
                <p class="descripcion-boton">Consulta los últimos intentos fallidos de acceso al sistema.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('usuarios_conectados')): ?>
                <button name="accion" value="usuarios_conectados" title="Muestra usuarios conectados actualmente">Usuarios conectados</button>
                <p class="descripcion-boton">Lista los usuarios que están actualmente conectados al sistema.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('verificar_integridad')): ?>
                <button name="accion" value="verificar_integridad" title="Verifica integridad de archivos importantes">Verificar integridad</button>
                <p class="descripcion-boton">Comprueba que los archivos esenciales del sistema no hayan sido modificados.</p>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="usuarios" id="usuarios-button">
        👥 Usuarios y seguridad
        <svg class="accordion-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="accordion-content" id="usuarios" role="region" aria-labelledby="usuarios-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('crear_usuario')): ?>
                <label>➕ Nuevo usuario:</label><br>
                <input type="text" name="nuevo_usuario" placeholder="Nombre de usuario" required title="Nombre para el nuevo usuario">
                <input type="password" name="password_usuario" placeholder="Contraseña" required title="Contraseña para el nuevo usuario">
                <button name="accion" value="crear_usuario" title="Crea un nuevo usuario en el sistema">Crear usuario</button>
                <p class="descripcion-boton">Agrega un nuevo usuario con su contraseña para acceder al sistema.</p>
            <?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('listar_usuarios')): ?>
                <button name="accion" value="listar_usuarios" title="Lista todos los usuarios del sistema">📋 Listar usuarios</button>
                <p class="descripcion-boton">Muestra información básica y últimos accesos de todos los usuarios.</p>
            <?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('eliminar_usuario')): ?>
                <label>🗑️ Eliminar usuario:</label><br>
                <input type="text" name="usuario_borrar" placeholder="Nombre de usuario" required title="Nombre del usuario a eliminar">
                <button name="accion" value="eliminar_usuario" title="Elimina un usuario del sistema">Eliminar</button>
                <p class="descripcion-boton">Elimina un usuario y sus archivos relacionados del sistema.</p>
            <?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('cambiar_password')): ?>
                <label>🔐 Cambiar contraseña:</label><br>
                <input type="text" name="usuario_cambiar" placeholder="Usuario" required title="Nombre del usuario">
                <input type="password" name="nueva_contrasena" placeholder="Nueva contraseña" required title="Nueva contraseña del usuario">
                <button name="accion" value="cambiar_password" title="Cambia la contraseña de un usuario">Cambiar contraseña</button>
                <p class="descripcion-boton">Actualiza la contraseña de un usuario existente.</p>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="accordion">
    <button class="accordion-button" aria-expanded="false" aria-controls="firewall" id="firewall-button">
        🛡️ Firewall (UFW)
        <svg class="accordion-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="accordion-content" id="firewall" role="region" aria-labelledby="firewall-button">
        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('ufw_estado')): ?>
                <button name="accion" value="ufw_estado" title="Muestra el estado actual del firewall">Estado del firewall</button>
                <p class="descripcion-boton">Verifica si el firewall está activo y su configuración.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('puertos_abiertos')): ?>
                <button name="accion" value="puertos_abiertos" title="Muestra los puertos abiertos y servicios escuchando">Puertos abiertos</button>
                <p class="descripcion-boton">Lista puertos que están aceptando conexiones.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('ufw_on')): ?>
                <button name="accion" value="ufw_on" title="Activa el firewall">Activar firewall</button>
                <p class="descripcion-boton">Enciende el firewall para proteger el sistema.</p>
            <?php endif; ?>
            <?php if (tiene_permiso('ufw_off')): ?>
                <button name="accion" value="ufw_off" title="Desactiva el firewall">Desactivar firewall</button>
                <p class="descripcion-boton">Apaga el firewall y permite todas las conexiones.</p>
            <?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('agregar_regla')): ?>
                <label>➕ Agregar regla:</label>
                <input type="number" name="puerto" placeholder="Puerto" title="Número de puerto">
                <select name="protocolo" title="Protocolo TCP o UDP">
                    <option value="tcp">TCP</option>
                    <option value="udp">UDP</option>
                </select>
                <button name="accion" value="agregar_regla" title="Agrega una regla al firewall">Agregar</button>
                <p class="descripcion-boton">Permite conexiones en el puerto y protocolo especificado.</p>
            <?php endif; ?>
        </form>

        <form method="post" action="acciones.php">
            <?php if (tiene_permiso('eliminar_regla')): ?>
                <label>➖ Eliminar regla:</label>
                <input type="text" name="regla" placeholder="Ej: 8080/tcp" title="Regla a eliminar">
                <button name="accion" value="eliminar_regla" title="Elimina una regla del firewall">Eliminar</button>
                <p class="descripcion-boton">Quita reglas existentes del firewall.</p>
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

<style>
.descripcion-boton {
    font-size: 0.85em;
    color: #555;
    margin: 3px 0 12px 5px;
    font-style: italic;
}
</style>

<?php include_once 'templates/footer.php'; ?>
