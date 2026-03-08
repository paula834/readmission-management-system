<?php
require_once 'includes/db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $gender    = $_POST['gender'] ?? '';
    $last_year = (int)($_POST['last_year_of_study'] ?? 0);
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';

    // Validation
    if (!$full_name || !$email || !$gender || !$last_year || !$password || !$confirm) {
        $errors[] = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {

        // Check duplicate email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = "Email already registered.";
        } else {

            $token = bin2hex(random_bytes(32));
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $defaultPic = ($gender === "Female") 
                ? "uploads/female.jpeg" 
                : "uploads/male.jpeg";

            // ✅ Correct INSERT
            $stmt = $pdo->prepare("
                INSERT INTO users 
                (full_name, email, password, gender, last_year_of_study, profile_picture, is_verified, verify_token)
                VALUES (?, ?, ?, ?, ?, ?, 0, ?)
            ");

            $stmt->execute([
                $full_name,
                $email,
                $hash,
                $gender,
                $last_year,
                $defaultPic,
                $token
            ]);

            // Send verification email (optional)
            if (file_exists('send_verification.php')) {
                require 'send_verification.php';
                sendVerificationEmail($email, $token);
            }

            $success = true;
        }
    }
}
?>
    
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>TNNP — Create Student Account</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Make text black but keep background */
    .card, .card * {
      color: #000 !important;
    }
  </style>
</head>

<body class="register-page">

<main class="wrap">

  <section class="hero">
    <div class="hero-inner">
      <h1>The Nairobi National Polytechnic</h1>
      <p>Join the student portal — register to manage courses, fees and unit registration.</p>
    </div>
  </section>

  <section class="form-area">

    <div class="card">

      <div class="brand">
        <img src="uploads/school logo.png" alt="school logo">
      </div>

      <h2>Create student account</h2>

      <?php if (!empty($errors)): ?>
        <div class="errors">
          <?= implode("<br>", array_map("htmlspecialchars", $errors)); ?>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="success-box">
          <h3>Account Created!</h3>
          <p>Please check your email to verify your account.</p>
        </div>

        <script>
          setTimeout(() => {
            window.location.href = "login.php";
          }, 3500);
        </script>
      <?php endif; ?>

      <form id="regForm" method="post" action="register.php" novalidate>

        <!-- Progress -->
        <div class="progress">
          <div class="progress-bar" id="progressBar" style="width:33%"></div>
        </div>

        <!-- STEP 1 -->
        <fieldset class="step" data-step="1">
          <legend>Personal</legend>

          <label class="field">
            <span>Full name</span>
            <input type="text" name="full_name" required>
          </label>

          <label class="field">
            <span>Email</span>
            <input type="email" name="email" required>
          </label>

          <label class="field">
            <span>Gender</span>
            <select name="gender" required>
              <option value="">--Select--</option>
              <option>Male</option>
              <option>Female</option>
            </select>
          </label>

          <div class="actions">
            <button type="button" class="btn btn-next" data-next="2">Next</button>
          </div>
        </fieldset>

        <!-- STEP 2 -->
        <fieldset class="step" data-step="2" hidden>
          <legend>Academic</legend>

          <label class="field">
            <span>Last Year / Module Before Discontinuation</span>
            <select name="last_year_of_study" required>
              <option value="">--Select Year--</option>
              <option value="1">module 1</option>
              <option value="2">module 2</option>
              <option value="3">module 3</option>
            </select>
          </label>

          <div class="actions">
            <button type="button" class="btn btn-back" data-back="1">Back</button>
            <button type="button" class="btn btn-next" data-next="3">Next</button>
          </div>
        </fieldset>

        <!-- STEP 3 -->
        <fieldset class="step" data-step="3"  hidden>
          <legend>Security</legend>

          <label class="field">
            <span>Password</span>
            <input type="password" name="password" minlength="6" required>
          </label>

          <label class="field">
            <span>Confirm password</span>
            <input type="password" name="confirm_password" minlength="6" required>
          </label>

          <div class="actions">
            <button type="button" class="btn btn-back" data-back="2">Back</button>
            <button type="submit" class="btn btn-submit">Register</button>
          </div>
        </fieldset>

      </form>

      <p class="login-link">Already have an account? <a href="login.php">Login</a></p>

    </div>

  </section>
</main>
<?php if (isset($_GET['pending_verification'])): ?>
<div class="modal success-modal">
  <div class="modal-box">
      <h2>Registration Successful 🎉</h2>
      <p>Please check your email and click the verification link.</p>
      <button onclick="document.querySelector('.success-modal').style.display='none'">OK</button>
  </div>
</div>
<?php endif; ?>
<script src="js/register.js"></script>
</body>
</html>
