<?php
require 'auth_check.php';
require 'db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) { echo "Invalid id"; exit; }

$stmt = $pdo->prepare("
  SELECT q.*, st.name as student_name, c.name as counselor_name
  FROM queries q
  JOIN students st ON q.student_user_id = st.user_id
  LEFT JOIN students c ON q.assigned_counselor_user_id = c.user_id
  WHERE q.id = :id LIMIT 1
");
$stmt->execute(['id'=>$id]);
$q = $stmt->fetch();
if (!$q) { echo "Not found"; exit; }

$rstmt = $pdo->prepare("
  SELECT r.*, s.name as counselor_name
  FROM responses r
  JOIN students s ON r.counselor_user_id = s.user_id
  WHERE r.query_id = :qid
  ORDER BY r.created_at ASC
");
$rstmt->execute(['qid'=>$id]);
$responses = $rstmt->fetchAll();

?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>View Query</title>
<link rel="stylesheet" href="../css/styles.css"></head>
<body>
  <main class="container">
    <a href="javascript:history.back()" class="btn ghost">Back</a>

    <!-- ✅ Success message দেখাও -->
    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert success">
        <?=htmlspecialchars($_SESSION['success']); unset($_SESSION['success']);?>
      </div>
    <?php endif; ?>

    <h1><?=htmlspecialchars($q['title'])?> <span class="tag"><?=htmlspecialchars($q['status'])?></span></h1>
    <p><?=nl2br(htmlspecialchars($q['message']))?></p>
    <p><strong>Student:</strong> <?=htmlspecialchars($q['student_name'])?> | <strong>Submitted:</strong> <?=$q['created_at']?></p>

    <section>
      <h2>Responses</h2>
      <?php if (!$responses): ?>
        <p class="muted">No responses yet.</p>
      <?php else: foreach ($responses as $res): ?>
        <div class="response">
          <div class="meta"><?=htmlspecialchars($res['counselor_name'])?> — <?=$res['created_at']?></div>
          <p><?=nl2br(htmlspecialchars($res['message']))?></p>
        </div>
      <?php endforeach; endif; ?>
    </section>

    <?php if ($_SESSION['user_role'] === 'counselor'): ?>
      <a href="respond_query.php?id=<?=$q['id']?>" class="btn primary">Respond</a>
    <?php endif; ?>
  </main>
</body>
</html>
