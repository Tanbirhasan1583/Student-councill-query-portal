<?php
require 'auth_check.php';
require 'db.php';
require_role('counselor');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qid = intval($_POST['query_id'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    $counselor_user_id = $_SESSION['user_id'];

    if (!$qid || !$message) {
        $_SESSION['error'] = "Message required.";
        header("Location: respond_query.php?id=$qid");
        exit;
    }

    // Transaction শুরু
    $pdo->beginTransaction();

    // Response insert
    $stmt = $pdo->prepare("
        INSERT INTO responses (query_id, counselor_user_id, message) 
        VALUES (:qid, :cid, :msg)
    ");
    $stmt->execute(['qid'=>$qid, 'cid'=>$counselor_user_id, 'msg'=>$message]);

    // Query status update
    $ustmt = $pdo->prepare("
        UPDATE queries 
        SET status = 'in_progress', 
            assigned_counselor_user_id = :cid, 
            updated_at = NOW() 
        WHERE id = :qid
    ");
    $ustmt->execute(['cid'=>$counselor_user_id, 'qid'=>$qid]);

    $pdo->commit();

    // ✅ Response পাঠানোর পর Counselor Dashboard এ redirect করো
    $_SESSION['success'] = "Response posted successfully.";
    header("Location: counselor_dashboard.php");
    exit;
}

// GET request হলে form দেখাও
$qid = intval($_GET['id'] ?? 0);
if (!$qid) { echo "Invalid id"; exit; }
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Respond</title>
<link rel="stylesheet" href="../css/styles.css"></head>
<body class="centered">
  <form method="post" class="card form-card">
    <h2>Respond to Query</h2>
    <input type="hidden" name="query_id" value="<?=$qid?>">
    <label>Message<textarea name="message" rows="6" required></textarea></label>
    <button class="btn primary" type="submit">Send</button>
    <a class="btn ghost" href="counselor_dashboard.php">Cancel</a>
  </form>
</body>
</html>
