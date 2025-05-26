<?php

$roles = array (
  'supervisor' => 
  array (
    0 => 'ufw_estado',
    1 => 'puertos_abiertos',
  ),
  'redes' => 
  array (
    0 => 'ver_uso_grafico',
    1 => 'ufw_estado',
    2 => 'puertos_abiertos',
    3 => 'agregar_regla',
    4 => 'eliminar_regla',
    5 => 'ufw_on',
    6 => 'ufw_off',
    7 => 'diagnostico_red',
    8 => 'ver_conexiones',
    9 => 'ver_logs_apache',
  ),
  'admin' => 
  array (
    0 => '*',
  ),
);

function tiene_permiso($accion) {
    global $roles;
    $rol = $_SESSION['rol'] ?? null;
    if (!$rol || !isset($roles[$rol])) return false;
    return in_array('*', $roles[$rol]) || in_array($accion, $roles[$rol]);
}
