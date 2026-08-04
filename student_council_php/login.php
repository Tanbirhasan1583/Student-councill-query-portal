<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!$email || !$password) {
        $_SESSION['error'] = "Email and password are required.";
        header("Location: login.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :email LIMIT 1");
    $stmt->execute(['email'=>$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Session set
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['name'];

        // Role অনুযায়ী redirect
        if ($user['role'] === 'student') {
            header("Location: student_dashboard.php");
        } elseif ($user['role'] === 'counselor') {
            header("Location: counselor_dashboard.php");
        } elseif ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            $_SESSION['error'] = "Invalid role.";
            header("Location: login.php");
        }
        exit;
    } else {
        $_SESSION['error'] = "Invalid credentials.";
        header("Location: login.php");
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="centered">
  <form method="post" class="card form-card">
    <h2>Login</h2>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert danger"><?=htmlspecialchars($_SESSION['error']); unset($_SESSION['error']);?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert success"><?=htmlspecialchars($_SESSION['success']); unset($_SESSION['success']);?></div>
    <?php endif; ?>

    <label>Email
      <input type="email" name="email" required>
    </label>
    <label>Password
      <input type="password" name="password" required>
    </label>

    <button class="btn primary" type="submit">Login</button>
    <a class="btn ghost" href="register.php">Create an account</a>
  </form>
</body>
</html>
