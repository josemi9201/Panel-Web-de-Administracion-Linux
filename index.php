<?php
session_start();
if (isset($_SESSION['autenticado'])) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}
exit;
