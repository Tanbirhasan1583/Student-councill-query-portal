<?php
require 'auth_check.php';
require 'db.php';
require_role('admin');

// সব query দেখাও
$stmt = $pdo->query("
    SELECT q.*, s.name as student_name, c.name as counselor_name
    FROM queries q
    JOIN students s ON q.student_user_id = s.user_id
    LEFT JOIN students c ON q.assigned_counselor_user_id = c.user_id
    ORDER BY q.created_at DESC
");
$queries = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <header class="topbar">
    <h1>Admin Dashboard</h1>
    <nav>
      <a href="submit_query.php" class="btn primary">Ask New Query</a>
      <!-- ✅ Logout button -->
      <a href="logout.php" class="btn danger">Logout</a>
    </nav>
  </header>

  <main>
    <table class="table">
      <tr><th>ID</th><th>Title</th><th>Student</th><th>Status</th><th>Counselor</th><th>Actions</th></tr>
      <?php foreach ($queries as $q): ?>
        <tr>
          <td><?=$q['id']?></td>
          <td><?=htmlspecialchars($q['title'])?></td>
          <td><?=htmlspecialchars($q['student_name'])?></td>
          <td><?=$q['status']?></td>
          <td><?=htmlspecialchars($q['counselor_name'])?></td>
          <td>
            <a href="respond_query.php?id=<?=$q['id']?>" class="btn small">Answer</a>
            <a href="assign_counselor.php?id=<?=$q['id']?>" class="btn small">Assign Counselor</a>
            <a href="remove_counselor.php?id=<?=$q['id']?>" class="btn small danger">Remove Counselor</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </main>
</body>
</html>
