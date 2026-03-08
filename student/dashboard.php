<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_student.php';

$user_id = $_SESSION['user_id'];

// Fetch student info
$stmt = $pdo->prepare("
    SELECT full_name, readmission_status, admission_status
    FROM users
    WHERE id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Student record not found.");
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Student Dashboard</title>
<link rel="stylesheet" href="../css/style.css">
<style>
.card { background:#fff; padding:20px; border-radius:8px; margin-bottom:20px; }
.status { font-weight:bold; }
.success { color:green; }
.warning { color:orange; }
.error { color:red; }
.btn { padding:10px 15px; background:#0056b3; color:#fff; text-decoration:none; border-radius:4px; display:inline-block; }
.btn.disabled { background:#aaa; pointer-events:none; }
</style>
</head>

<body>

<h2>Welcome, <?= htmlspecialchars($user['full_name']) ?></h2>

<div class="card">
    <h3>Readmission Status</h3>

    <?php if ($user['readmission_status'] === 'none'): ?>
        <p class="status warning">You have not applied for readmission.</p>
        <a class="btn" href="readmission_apply.php">Apply for Readmission</a>

    <?php elseif ($user['readmission_status'] === 'pending'): ?>
        <p class="status warning">Your readmission request is under review.</p>
        <a class="btn disabled">Awaiting Approval</a>

    <?php elseif ($user['readmission_status'] === 'approved'): ?>
        <p class="status success">Readmission approved.</p>

    <?php elseif ($user['readmission_status'] === 'rejected'): ?>
        <p class="status error">Readmission rejected. Contact administration.</p>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Admission Status</h3>

    <?php if ($user['readmission_status'] !== 'approved'): ?>
        <p class="status warning">Complete readmission first.</p>
        <a class="btn disabled">Admission Locked</a>

    <?php elseif ($user['admission_status'] === 'locked'): ?>
        <p class="status warning">Admission form not submitted.</p>
        <a class="btn" href="admission_form.php">Fill Admission Form</a>

    <?php elseif ($user['admission_status'] === 'pending'): ?>
        <p class="status warning">Admission form under review.</p>
        <a class="btn disabled">Awaiting Approval</a>

    <?php elseif ($user['admission_status'] === 'approved'): ?>
        <p class="status success">Admission approved.</p>
        <a class="btn" href="admission_letter.php">Download Admission Letter</a>
    <?php endif; ?>
</div>

<div class="card">
    <h3>logout</h3>
    <a class="btn" href="../logout.php">Logout</a>
</div>

</body>
</html>
