<?php
require 'auth_check.php';
require 'db.php';
require_role('counselor');

$counselor_user_id = $_SESSION['user_id'];

// Assigned queries
$assigned = $pdo->prepare("
  SELECT q.*, s.name as student_name 
  FROM queries q 
  JOIN students s ON q.student_user_id = s.user_id 
  WHERE q.assigned_counselor_user_id = :cid 
  ORDER BY q.created_at DESC
");
$assigned->execute(['cid'=>$counselor_user_id]);
$assigned_list = $assigned->fetchAll();

// Unassigned queries
$unassigned = $pdo->query("
  SELECT q.*, s.name as student_name 
  FROM queries q 
  JOIN students s ON q.student_user_id = s.user_id 
  WHERE q.assigned_counselor_user_id IS NULL 
  ORDER BY q.created_at DESC
")->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Counselor Dashboard</title>
<link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <header class="topbar">
    <div>Welcome, <?=htmlspecialchars($_SESSION['user_name'])?></div>
    <nav><a href="logout.php" class="btn ghost">Logout</a></nav>
  </header>
  <main class="container">
    <h1>Assigned to you</h1>
    <?php foreach ($assigned_list as $q): ?>
      <div class="list-item">
        <h3><?=htmlspecialchars($q['title'])?> <span class="tag"><?=htmlspecialchars($q['status'])?></span></h3>
        <p><?=htmlspecialchars(substr($q['message'],0,200))?></p>
        <a href="view_query.php?id=<?=$q['id']?>" class="btn small">Open</a>
      </div>
    <?php endforeach; ?>

    <h2>Unassigned queries</h2>
    <?php foreach ($unassigned as $q): ?>
      <div class="list-item">
        <h3><?=htmlspecialchars($q['title'])?></h3>
        <p><?=htmlspecialchars(substr($q['message'],0,200))?></p>
        <a href="view_query.php?id=<?=$q['id']?>" class="btn small">Open</a>
      </div>
    <?php endforeach; ?>
  </main>
</body>
</html>
