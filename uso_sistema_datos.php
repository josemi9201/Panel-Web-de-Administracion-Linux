<?php
include_once 'inc/auth.php';

// Obtener datos del sistema para CPU, RAM y Disco

// CPU usage: obtener porcentaje usado (ejemplo simple con top)
$cpu_raw = shell_exec("top -bn1 | grep '%Cpu(s)'");
preg_match('/(\d+\.\d+)\s*id/', $cpu_raw, $matches);
$cpu_usada = isset($matches[1]) ? 100 - floatval($matches[1]) : 0;
$cpu_usada = round($cpu_usada, 1);

// RAM usage: obtener memoria total y libre en MB
$meminfo = file_get_contents("/proc/meminfo");
preg_match('/MemTotal:\s+(\d+) kB/', $meminfo, $total_match);
preg_match('/MemAvailable:\s+(\d+) kB/', $meminfo, $avail_match);

$mem_total = isset($total_match[1]) ? intval($total_match[1]) / 1024 : 0; // MB
$mem_libre = isset($avail_match[1]) ? intval($avail_match[1]) / 1024 : 0; // MB
$mem_usada = $mem_total - $mem_libre;

// Disco usage: usar df para la partición root
$df_raw = shell_exec("df --output=size,used,avail -BM / | tail -n 1");
$df_parts = preg_split('/\s+/', trim($df_raw));

$disk_total = isset($df_parts[0]) ? intval(rtrim($df_parts[0], 'M')) : 0;
$disk_usada = isset($df_parts[1]) ? intval(rtrim($df_parts[1], 'M')) : 0;
$disk_libre = isset($df_parts[2]) ? intval(rtrim($df_parts[2], 'M')) : 0;

header('Content-Type: application/json');
echo json_encode([
    'cpu' => $cpu_usada,
    'ram_usada' => round($mem_usada, 1),
    'ram_libre' => round($mem_libre, 1),
    'disk_usada' => $disk_usada,
    'disk_libre' => $disk_libre,
]);
