<?php
require 'auth_check.php';
require 'db.php';
require_role('student');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $student_user_id = $_SESSION['user_id'];

    if (!$title || !$message) {
        $_SESSION['error'] = "Title and message required.";
        header('Location: submit_query.php');
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO queries (student_user_id, title, message) VALUES (:sid, :title, :message)");
    $stmt->execute(['sid'=>$student_user_id, 'title'=>$title, 'message'=>$message]);
    $_SESSION['success'] = "Query submitted.";
    header('Location: student_dashboard.php');
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Submit Query</title>
<link rel="stylesheet" href="../css/styles.css"></head>
<body class="centered">
  <form method="post" class="card form-card">
    <h2>Submit a Query</h2>
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert error"><?=htmlspecialchars($_SESSION['error']); unset($_SESSION['error']);?></div>
    <?php endif; ?>
    <label>Title<input type="text" name="title" required></label>
    <label>Message<textarea name="message" rows="6" required></textarea></label>
    <button class="btn primary" type="submit">Submit</button>
    <a class="btn ghost" href="student_dashboard.php">Back</a>
  </form>
</body>
</html>
