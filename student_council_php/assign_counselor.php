<?php
require 'auth_check.php';
require 'db.php';
require_role('admin');

$qid = intval($_GET['id'] ?? 0);
if (!$qid) { echo "Invalid query id"; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cid = intval($_POST['counselor_id']);
    $stmt = $pdo->prepare("
        UPDATE queries 
        SET assigned_counselor_user_id = :cid, 
            status = 'in_progress', 
            updated_at = NOW() 
        WHERE id = :qid
    ");
    $stmt->execute(['cid'=>$cid, 'qid'=>$qid]);

    $_SESSION['success'] = "Counselor assigned successfully.";
    header("Location: admin_dashboard.php");
    exit;
}

// Query details
$query = $pdo->prepare("SELECT * FROM queries WHERE id = :id");
$query->execute(['id'=>$qid]);
$q = $query->fetch();

// Counselor list
$counselors = $pdo->query("SELECT user_id, name FROM students WHERE role='counselor' ORDER BY name")->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Assign Counselor</title>
<link rel="stylesheet" href="../css/styles.css">
</head>
<body class="centered">
  <form method="post" class="card form-card">
    <h2>Assign Counselor</h2>
    <input type="hidden" name="query_id" value="<?=$q['id']?>">
    <p><strong>Query:</strong> <?=htmlspecialchars($q['title'])?></p>
    <label>Counselor
      <select name="counselor_id" required>
        <?php foreach ($counselors as $c): ?>
          <option value="<?=$c['user_id']?>"><?=htmlspecialchars($c['name'])?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="btn primary" type="submit">Assign</button>
    <a class="btn ghost" href="admin_dashboard.php">Cancel</a>
  </form>
</body>
</html>
