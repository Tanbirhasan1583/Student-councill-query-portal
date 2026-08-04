<?php
require 'auth_check.php';
require 'db.php';
require_role('student');

$student_user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
  SELECT q.*, s.name as counselor_name
  FROM queries q
  LEFT JOIN students s ON q.assigned_counselor_user_id = s.user_id
  WHERE q.student_user_id = :sid
  ORDER BY q.created_at DESC
");
$stmt->execute(['sid'=>$student_user_id]);
$queries = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Student Dashboard</title>
<link rel="stylesheet" href="../css/styles.css"> 
</head>
<body>
  <header class="topbar">
    <div>Welcome, <?=htmlspecialchars($_SESSION['user_name'])?></div>
    <nav><a href="submit_query.php" class="btn">New Query</a> <a href="logout.php" class="btn ghost">Logout</a></nav>
  </header>
  <main class="container">
    <h1>Your Queries</h1>
    <?php if (!$queries): ?>
      <p class="muted">You have not submitted any queries yet.</p>
    <?php else: ?>
      <div class="list">
        <?php foreach ($queries as $q): ?>
          <div class="list-item">
            <h3><?=htmlspecialchars($q['title'])?> <span class="tag"><?=htmlspecialchars($q['status'])?></span></h3>
            <p><?=nl2br(htmlspecialchars(substr($q['message'],0,300)))?></p>
            <small>Assigned to: <?=htmlspecialchars($q['counselor_name'] ?? 'Not assigned')?></small>
            <p><a href="view_query.php?id=<?=$q['id']?>" class="btn small">View</a></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
