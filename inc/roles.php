<?php

$roles = array (
  'supervisor' =>
  array (
    0 => 'ufw_estado',
    1 => 'puertos_abiertos',
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
