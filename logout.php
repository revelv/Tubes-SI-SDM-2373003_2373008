<?php
require_once 'auth.php';

// Hapus semua data session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke login
header("Location: login.php");
exit();
?>