<?php
session_start();
require_once 'includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // fetch student user (adjust query if your users table differs)
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] =  $user['role'];
        
           if ($user['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: student/dashboard.php");
    }
    exit;
    } else {
        $errors[] = 'Invalid login credentials.';
    }
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>TNNP Student Login</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <!-- only the new login stylesheet -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-v1">
  <div class="page-wrap">

  <!-- left blue marketing block -->
  <div class="login-left" aria-hidden="true">
    <div class="left-inner">
      <h1>The Nairobi National Polytechnic</h1>
      <p>Readmission Portal — access your courses, register units and manage your profile.</p>
    </div>
  </div>

  <!-- right: login card -->
  <div class="login-right">
    <div class="login-card">

      <!-- logo inside card -->
      <div class="login-logo">
        <!-- use your real logo file or rename as needed -->
        <img src="uploads/school logo.png" alt="school logo">
      </div>

      <h2>Student Login</h2>

      <?php if (!empty($errors)): ?>
        <div class="errors"><?php echo implode('<br>', $errors); ?></div>
      <?php endif; ?>

      <form method="post" action="">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <button type="submit">Login</button>

        <p class="small">Don't have an account? <a class="link" href="register.php">Register</a></p>
      </form>

    </div>
  </div>

</div>
</body>
</html>
