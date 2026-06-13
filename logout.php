<?php
if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
// Redirect ke login di root project
$script = $_SERVER['SCRIPT_NAME'];
$base   = rtrim(dirname($script), '/');
header("Location: $base/login.php");
exit();
