<?php
require 'auth_check.php';
require 'db.php';
require_role('admin');

$qid = intval($_GET['id'] ?? 0);
if ($qid) {
    $stmt = $pdo->prepare("UPDATE queries SET assigned_counselor_user_id=NULL, status='pending' WHERE id=:id");
    $stmt->execute(['id'=>$qid]);
}
header("Location: admin_dashboard.php");
exit;
