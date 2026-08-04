<?php
session_start();
$_SESSION = [];
session_destroy();

// Login পেজে redirect করো
header("Location: login.php");
exit;
