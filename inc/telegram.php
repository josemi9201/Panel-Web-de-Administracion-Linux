<?php
include_once 'config.php';

function enviarMensajeTelegram($mensaje) {
    global $token, $chat_id;
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($mensaje);
    file_get_contents($url);
}
