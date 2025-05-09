<?php
include_once 'inc/auth.php';

$cpu_raw = shell_exec("top -bn1 | grep '%Cpu' | awk '{print 100 - $8}'");
$cpu = floatval(trim($cpu_raw));

$mem = preg_split('/\s+/', trim(shell_exec("free -m | grep Mem:")));
$ram_total = intval($mem[1]);
$ram_usada = intval($mem[2]);

$disk = preg_split('/\s+/', trim(shell_exec("df / | tail -1")));
$disk_total = intval($disk[1]);
$disk_usada = intval($disk[2]);

header('Content-Type: application/json');
echo json_encode([
    "cpu" => $cpu,
    "ram_usada" => $ram_usada,
    "ram_libre" => $ram_total - $ram_usada,
    "disk_usada" => $disk_usada,
    "disk_libre" => $disk_total - $disk_usada
]);
