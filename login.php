<?php
session_start();
include_once 'inc/usuarios.php';
include_once 'inc/telegram.php';

if (isset($_POST['login'])) {
    $usuario = $_POST['user'] ?? '';
    $clave = $_POST['pass'] ?? '';

    if (isset($usuarios_validos[$usuario]) && password_verify($clave, $usuarios_validos[$usuario]['hash'])) {
        $_SESSION['autenticado'] = true;
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $usuarios_validos[$usuario]['rol'];
        $mensaje = "🔐 Login de $usuario";
        enviarMensajeTelegram($mensaje);

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Credenciales incorrectas.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-container">
    <h2>Acceso al Panel</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="user" placeholder="Usuario" required>
        <input type="password" name="pass" placeholder="Contraseña" required>
        <button type="submit" name="login">Entrar</button>
    </form>
</div>

</body>
</html>
