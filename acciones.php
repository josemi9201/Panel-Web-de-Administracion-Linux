<?php
include_once 'inc/auth.php';
include_once 'inc/permisos.php';

function tiene_permiso($accion) {
    $usuario = $_SESSION['usuario'] ?? '';
    global $permisos;
    if (!isset($permisos[$usuario])) return false;
    return in_array($accion, $permisos[$usuario]) || in_array("*", $permisos[$usuario]);
}

function ejecutar($comando) {
    $resultado = shell_exec($comando . ' 2>&1');
    return $resultado ?: "No se produjo salida del comando.";
}

function validar_post($clave) {
    return isset($_POST[$clave]) && trim($_POST[$clave]) !== '';
}

// 🔧 ESTA LÍNEA DEBE IR PRIMERO
$accion = $_POST['accion'] ?? '';

// ✅ Comprobar permisos después de definir $accion
if (!tiene_permiso($accion)) {
    $_SESSION['output'] = "❌ No tienes permiso para esta acción.";
    header("Location: dashboard.php");
    exit;
}

$output = "Acción no reconocida.";

switch ($accion) {

    // 📁 Backups
    case 'hacer_backup':
        $output = ejecutar("sudo /usr/local/bin/hacer_backup.sh");
        break;
    case 'eliminar_backups';
        $output = ejecutar("sudo find /var/backups/ -type f -name '*.tar.gz' -mtime +7 -delete && echo 'Backups antiguos eliminados.'");
        break;
    case 'listar_backups':
        $output = ejecutar("ls -lh /var/backups/");
        break;
    case 'eliminar_todos_los_backups':
        $output = ejecutar("sudo find /var/backups/ -type f -name '*.tar.gz' -delete && echo '🗑️ Todos los backups eliminados.'");
        break;

    case 'descargar_backup':
    $archivo = trim(shell_exec("ls -t /var/backups/*.tar.gz 2>/dev/null | head -n 1"));
    if (file_exists($archivo)) {
        header('Content-Type: application/gzip');
        header('Content-Disposition: attachment; filename="' . basename($archivo) . '"');
        readfile($archivo);
        exit;
    } else {
        $output = "❌ No se encontró ningún archivo de backup.";
    }
    break;


    // 📊 Sistema

    case 'uso_sistema':
    $top = ejecutar("top -bn1 | head -5");
    $ram = ejecutar("free -h | grep Mem:");
    $disco = ejecutar("df -h --output=source,size,used,avail,pcent,target | grep -v tmpfs | column -t");

    $output = "🔧 USO DEL SISTEMA\n";
    $output .= "\n🖥️  CPU:\n$top";

    $output .= "\n💾 RAM:\n";
    $output .= "Total / Usada / Libre:\n";
    if (preg_match('/Mem:\s+(\S+)\s+(\S+)\s+(\S+)/', $ram, $m)) {
        $output .= "{$m[1]} / {$m[2]} / {$m[3]}\n";
    } else {
        $output .= "$ram\n";
    }

    $output .= "\n📂 DISCO (particiones principales):\n";
    $output .= "$disco";
    break;

    case 'ver_procesos':
    $output = "📋 TOP 10 PROCESOS ACTIVOS (Ordenados por RAM)\n";
    $output .= str_repeat("─", 60) . "\n";
    $output .= sprintf("%-12s %-6s %-6s %-6s %s\n", "Usuario", "PID", "CPU%", "RAM%", "Comando");
    $output .= str_repeat("─", 60) . "\n";

    $datos = ejecutar("ps -eo user,pid,pcpu,pmem,comm --sort=-%mem | head -n 11");

    $lineas = explode("\n", trim($datos));
    array_shift($lineas); // quitar cabecera

    foreach ($lineas as $linea) {
        $cols = preg_split('/\s+/', trim($linea), 5);
        if (count($cols) === 5) {
            list($user, $pid, $cpu, $mem, $cmd) = $cols;
            $output .= sprintf("%-12s %-6s %-6s %-6s %s\n", $user, $pid, $cpu, $mem, $cmd);
        }
    }
    break;

    case 'kill_pid':
    if (!empty($_POST['pid']) && is_numeric($_POST['pid'])) {
        $pid = intval($_POST['pid']);

        // Verificar si el proceso existe
        $check = trim(ejecutar("ps -p $pid -o pid="));
        if ($check === "") {
            $output = "❌ No existe un proceso con PID $pid.";
        } else {
            // Intentar terminar el proceso
            $output = ejecutar("sudo kill -9 $pid && echo '✅ Proceso $pid terminado correctamente.'") ?: "❌ Error al intentar finalizar el proceso.";
        }
    } else {
        $output = "⚠️ Debes ingresar un PID válido.";
    }
    break;

    case 'reiniciar_apache':
        $output = ejecutar("sudo systemctl restart apache2 && echo 'Apache reiniciado correctamente.'");
        break;
    case 'actualizar_sistema':
        $output = ejecutar("sudo apt update && sudo apt upgrade -y");
        break;
    case 'limpiar_tmp':
        $output = ejecutar("sudo rm -rf /tmp/* && echo 'Archivos temporales eliminados.'");
        break;
    case 'reiniciar_sistema':
        $output = "Reiniciando sistema...";
        ejecutar("sudo shutdown -r now");
        break;
    case 'limpieza_avanzada':
        $output = ejecutar("sudo /usr/local/bin/limpieza_avanzada.sh");
        break;

    // 🔐 Seguridad
    case 'intentos_fallidos':
        $output = ejecutar("sudo grep 'invalid' /var/log/auth.log | tail -n 20");
        break;
    case 'usuarios_conectados':
    $output = "👥 USUARIOS CONECTADOS\n";
    $output .= str_repeat("─", 40) . "\n";

    $lineas = explode("\n", trim(ejecutar("who")));
    if (count($lineas) <= 1 && empty($lineas[0])) {
        $output .= "No hay usuarios conectados actualmente.\n";
    } else {
        foreach ($lineas as $linea) {
            if (preg_match('/^(\S+)\s+(\S+)\s+(\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2})\s+\(?([^)]+)?\)?/', $linea, $m)) {
                $usuario = $m[1];
                $terminal = $m[2];
                $hora = $m[3];
                $ip = isset($m[4]) ? $m[4] : 'local';
                $output .= "🔹 Usuario: $usuario | TTY: $terminal\n   🕒 $hora | 🌍 IP: $ip\n\n";
            } else {
                $output .= "ℹ️ $linea\n";
            }
        }
    }
    break;

    case 'verificar_integridad':
        $output = ejecutar("sudo /usr/local/bin/verificar_integridad.sh");
        break;
    // 👥 Usuarios
case 'crear_usuario':
    if (!empty($_POST['nuevo_usuario']) && !empty($_POST['password_usuario'])) {
        $user = escapeshellarg($_POST['nuevo_usuario']);
        $raw_user = $_POST['nuevo_usuario']; // Sin escapar para validación
        $pass = escapeshellarg($_POST['password_usuario']);

        // Verificar si el usuario ya existe
        $check = trim(shell_exec("getent passwd $raw_user 2>/dev/null"));
        if ($check !== "") {
            $output = "⚠️ El usuario '$raw_user' ya existe en el sistema.";
        } else {
            // Intentar crear el usuario
            $create_user_command = "sudo useradd -m $user && echo '$user:$pass' | sudo chpasswd";
            $create_user_output = shell_exec($create_user_command);

            // Revisar si la creación del usuario fue exitosa
            if ($create_user_output === null) {
                // Verificar si el usuario realmente existe
                $check_new_user = trim(shell_exec("getent passwd $raw_user 2>/dev/null"));
                if ($check_new_user !== "") {
                    $output = "✅ Usuario '$raw_user' creado correctamente.";
                } else {
                    $output = "❌ El usuario '$raw_user' no se creó correctamente. Verifique los permisos.";
                }
            } else {
                $output = "❌ Hubo un error al crear el usuario: $create_user_output";
            }
        }
    } else {
        $output = "❗ Debe ingresar un nombre de usuario y una contraseña.";
    }
    break;

    case 'eliminar_usuario':
    if (!empty($_POST['usuario_borrar'])) {
        $user = escapeshellarg($_POST['usuario_borrar']);

        // Verificar si el usuario existe
        $check = trim(ejecutar("id -u $user 2>/dev/null"));
        if ($check === "") {
            $output = "⚠️ El usuario '$user' no existe.";
        } else {
            // Verificar si el spool de correo existe
            $mail_spool = "/var/mail/$user";
            if (file_exists($mail_spool)) {
                // Eliminar el spool de correo si existe
                ejecutar("sudo rm -f $mail_spool");
            }

            // Intentar eliminar el usuario
            $delete_user_command = "sudo userdel -r $user";
            $delete_user_output = ejecutar($delete_user_command);

            // Revisar si hubo salida del comando
            if ($delete_user_output === null || $delete_user_output === "") {
                // Verificar si el usuario realmente fue eliminado
                $check_deleted_user = trim(ejecutar("id -u $user 2>/dev/null"));
                if ($check_deleted_user === "") {
                    $output = "🗑️ Usuario '$user' eliminado correctamente.";
                } else {
                    $output = "❌ Hubo un error al eliminar el usuario '$user'.";
                }
            } else {
                $output = "❌ Error al ejecutar el comando para eliminar el usuario: $delete_user_output";
            }
        }
    } else {
        $output = "⚠️ Debe ingresar el nombre del usuario a eliminar.";
    }
    break;


    case 'cambiar_password':
    if (!empty($_POST['usuario_cambiar']) && !empty($_POST['nueva_contrasena'])) {
        $user = escapeshellarg($_POST['usuario_cambiar']);
        $pass = escapeshellarg($_POST['nueva_contrasena']);

        $check = trim(ejecutar("id -u $user 2>/dev/null"));
        if ($check === "") {
            $output = "⚠️ El usuario no existe.";
        } else {
            $output = ejecutar("echo $user:$pass | sudo chpasswd && echo '🔐 Contraseña de $user actualizada.'");
        }
    } else {
        $output = "⚠️ Debe ingresar usuario y nueva contraseña.";
    }
    break;

    case 'listar_usuarios':
    $output = "👥 Usuarios del sistema:\n\n";
    $usuarios = explode("\n", trim(ejecutar("cut -d: -f1 /etc/passwd")));

    foreach ($usuarios as $u) {
        if ($u === '') continue;
        $info = ejecutar("id $u 2>/dev/null");
        if (strpos($info, 'uid=') !== false) {
            $ultimo_login = trim(ejecutar("lastlog -u $u | tail -n 1"));
            $output .= "🔹 $u\n  $info  Último acceso: $ultimo_login\n\n";
        }
    }
    break;

    // 🛡️ Firewall
    case 'ufw_estado':
    $output = ejecutar("sudo ufw status verbose");
    break;

case 'puertos_abiertos':
    $output = ejecutar("ss -tuln | grep LISTEN");
    break;

case 'agregar_regla':
    if (!empty($_POST['puerto']) && !empty($_POST['protocolo'])) {
        $puerto = intval($_POST['puerto']);
        $protocolo = escapeshellarg($_POST['protocolo']);
        $output = ejecutar("sudo ufw allow $puerto/$protocolo && echo '✅ Regla agregada para $puerto/$protocolo'");
    } else {
        $output = "❗ Debes ingresar el puerto y protocolo (tcp/udp).";
    }
    break;

case 'eliminar_regla':
    if (!empty($_POST['regla'])) {
        $regla = escapeshellarg($_POST['regla']);
        $output = ejecutar("sudo ufw delete allow $regla && echo '🗑️ Regla $regla eliminada.'");
    } else {
        $output = "❗ Debes especificar la regla a eliminar (ej: 8080/tcp).";
    }
    break;

case 'ufw_on':
    $output = ejecutar("sudo ufw enable && echo '🟢 Firewall activado.'");
    break;

case 'ufw_off':
    $output = ejecutar("sudo ufw disable && echo '🔴 Firewall desactivado.'");
    break;

    // 📡 Red
    case 'diagnostico_red':
    $output = ejecutar("sudo /usr/local/bin/diagnostico_red.sh");
    break;

    case 'ver_conexiones':
    $output = ejecutar("ss -tunap | grep ESTAB || echo 'No hay conexiones establecidas.'");
    break;

    // ⏱️ Crontab
    case 'ver_crontab_usuario':
    $output = "📆 CRONTAB DEL USUARIO ACTUAL\n";
    $output .= str_repeat("─", 50) . "\n";
    $output .= ejecutar("crontab -l 2>&1");
    break;

    case 'ver_crontab_sistema':
    $output = "📆 CRONTAB DEL SISTEMA (/etc/crontab)\n";
    $output .= str_repeat("─", 50) . "\n";
    $output .= "Este archivo contiene tareas programadas globales.\n";
    $output .= "Las líneas activas actualmente son:\n\n";
    $output .= "⏰ 17:00  (cada hora)        ➤ Ejecuta /etc/cron.hourly\n";
    $output .= "⏰ 06:25  (diario)           ➤ Ejecuta /etc/cron.daily (si no hay anacron)\n";
    $output .= "⏰ 06:47  (semanal, domingo) ➤ Ejecuta /etc/cron.weekly (si no hay anacron)\n";
    $output .= "⏰ 06:52  (mensual, día 1)   ➤ Ejecuta /etc/cron.monthly (si no hay anacron)\n\n";
    $output .= "🔧 Línea original:\n";
    $output .= ejecutar("grep -v '^#' /etc/crontab | grep -v '^$'");
    break;

    // 📦 Paquetes
    case 'instalar_paquete':
        if (validar_post('paquete')) {
            $pkg = escapeshellarg($_POST['paquete']);
            $output = ejecutar("sudo apt install -y $pkg");
        } else {
            $output = "Por favor, introduce un nombre de paquete.";
        }
        break;

    // 📜 Logs
    case 'ver_logs':
    $output = "📜 ÚLTIMOS 30 EVENTOS DEL SISTEMA (/var/log/syslog)\n";
    $output .= str_repeat("─", 50) . "\n";
    $output .= ejecutar("sudo tail -n 30 /var/log/syslog");
    break;

    case 'ver_logs_apache':
    $output = "🛠️ LOGS DE APACHE (/var/log/apache2/error.log)\n";
    $output .= str_repeat("─", 50) . "\n";
    $output .= ejecutar("sudo tail -n 50 /var/log/apache2/error.log");
    break;

    // Servicios
    case 'estado_servicios':
    $output = ejecutar("sudo /usr/local/bin/estado_servicios.sh");
    break;

    case 'ver_uptime':
    $raw = ejecutar("uptime");
    if (preg_match('/up\s+(.*?),\s+(\d+)\s+users?,\s+load average:\s+(.*)/', $raw, $match)) {
        $uptime = $match[1];
        $usuarios = $match[2];
        $carga = $match[3];
        $output = "⏱️ Tiempo encendido: $uptime\n👥 Usuarios conectados: $usuarios\n📊 Carga promedio: $carga";
    } else {
        $output = "❌ No se pudo interpretar la salida de uptime:\n$raw";
    }
    break;


}

$_SESSION['output'] = $output;
header("Location: dashboard.php");
exit;
