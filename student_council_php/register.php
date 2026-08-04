<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $name       = trim($_POST['name']);
    $email      = trim($_POST['email']);
    $password   = trim($_POST['password']);
    $role       = $_POST['role'];

    if (!$name || !$email || !$password || !$role) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: register.php");
        exit;
    }

    // Password hash (bcrypt)
    $hashed = password_hash($password, PASSWORD_BCRYPT);

    try {
        if ($role === 'student') {
            if (!$student_id || !$department) {
                $_SESSION['error'] = "Student ID and Department are required for students.";
                header("Location: register.php");
                exit;
            }

            $stmt = $pdo->prepare("
                INSERT INTO students (student_id, department, name, email, password, role, created_at) 
                VALUES (:sid, :dept, :name, :email, :pass, :role, NOW())
            ");
            $stmt->execute([
                'sid'   => $student_id,
                'dept'  => $department,
                'name'  => $name,
                'email' => $email,
                'pass'  => $hashed,
                'role'  => $role
            ]);
        } else {
            // Counselor/Admin insert (student_id, department বাদ)
            $stmt = $pdo->prepare("
                INSERT INTO students (name, email, password, role, created_at) 
                VALUES (:name, :email, :pass, :role, NOW())
            ");
            $stmt->execute([
                'name'  => $name,
                'email' => $email,
                'pass'  => $hashed,
                'role'  => $role
            ]);
        }

        $_SESSION['success'] = "Registration successful. Please login.";
        header("Location: login.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Registration failed: " . $e->getMessage();
        header("Location: register.php");
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="centered">
  <form method="post" class="card form-card">
    <h2>Create Account</h2>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert danger"><?=htmlspecialchars($_SESSION['error']); unset($_SESSION['error']);?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert success"><?=htmlspecialchars($_SESSION['success']); unset($_SESSION['success']);?></div>
    <?php endif; ?>

    <label>Role
      <select name="role" id="role" onchange="toggleStudentFields()" required>
        <option value="student">Student</option>
        <option value="counselor">Counselor</option>
        <option value="admin">Admin</option>
      </select>
    </label>

    <div id="studentFields">
      <label>Student ID
        <input type="text" name="student_id">
      </label>
      <label>Department
        <input type="text" name="department">
      </label>
    </div>

    <label>Name
      <input type="text" name="name" required>
    </label>
    <label>Email
      <input type="email" name="email" required>
    </label>
    <label>Password
      <input type="password" name="password" required>
    </label>

    <button class="btn primary" type="submit">Register</button>
    <a class="btn ghost" href="login.php">Already have an account? Login</a>
  </form>

  <script>
    function toggleStudentFields() {
      const role = document.getElementById('role').value;
      const studentFields = document.getElementById('studentFields');
      studentFields.style.display = (role === 'student') ? 'block' : 'none';
    }
    toggleStudentFields();
  </script>
</body>
</html>
