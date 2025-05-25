<?php
session_start();
include_once 'inc/auth.php';
include_once 'inc/roles.php';

if (!tiene_permiso('configurar_remoto')) {
    exit("❌ No tienes permiso para ejecutar esta acción.");
}

$servidor_remoto = $_SESSION['remoto'] ?? null;

if (!$servidor_remoto) {
    exit("❌ No hay servidor remoto configurado. Activa la conexión remota primero.");
}

$carpeta_local = '/usr/local/bin/';
$carpeta_remota = '/usr/local/bin/';

$archivos = [
    'hacer_backup.sh',
    'limpieza_avanzada.sh',
    'estado_servicios.sh',
    'diagnostico_red.sh',
    'verificar_integridad.sh'
];

$host = escapeshellarg($servidor_remoto['host']);
$usuario = escapeshellarg($servidor_remoto['usuario']);
$clave = escapeshellarg($servidor_remoto['clave']);

$salida = "";

foreach ($archivos as $archivo) {
    $ruta_local = escapeshellarg($carpeta_local . $archivo);
    $ruta_remota = escapeshellarg(trim($usuario, "'") . '@' . trim($servidor_remoto['host']) . ':' . $carpeta_remota);

    $salida .= "Copiando $archivo... ";

    // Comando para copiar el archivo
    $cmd_copia = "sshpass -p $clave scp -o StrictHostKeyChecking=no $ruta_local $ruta_remota";
    exec($cmd_copia, $output_copia, $ret_copia);

    if ($ret_copia === 0) {
        $salida .= "✅ Copiado correctamente. ";

        // Cambiar permisos para hacerlo ejecutable en remoto
        $cmd_perm = "sshpass -p $clave ssh -o StrictHostKeyChecking=no $usuario@$host sudo chmod +x " . escapeshellarg($carpeta_remota . $archivo);
        exec($cmd_perm, $output_perm, $ret_perm);

        if ($ret_perm === 0) {
            $salida .= "Permisos establecidos.\n";
        } else {
            $salida .= "⚠️ Error estableciendo permisos.\n";
        }
    } else {
        $salida .= "❌ Error al copiar.\n";
    }
}

echo nl2br(htmlspecialchars($salida));
