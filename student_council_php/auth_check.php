<?php
// auth_check.php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
function require_role($role) {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
        http_response_code(403);
        echo "Access denied.";
        exit;
    }
}
