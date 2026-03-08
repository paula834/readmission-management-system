<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_student.php';

require_student();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        af.serial_number,
        af.course_applied,
        af.intake,
        af.letter_generated_at,
        u.full_name
    FROM admission_forms af
    JOIN users u ON af.user_id = u.id
    WHERE af.user_id = ?
      AND af.status = 'approved'
");
$stmt->execute([$user_id]);
$letter = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$letter) {
    die("Admission letter not available.");
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admission Letter</title>
<link rel="stylesheet" href="../css/style.css">
<div style="text-align:center; margin-bottom:20px;">
    <a href="download_admission_letter.php" class="btn">
    Download Admission Letter
</a>
</div>
<style>
.letter {
    width: 800px;
    margin: auto;
    background: #fff;
    padding: 40px;
    border: 1px solid #000;
}
.header {
    text-align: center;
}
.header img {
    width: 100px;
}

.btn {
    display: inline-block;
    padding: 10px 18px;
    background-color: #2c3e50;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: 0.3s;
}

.btn:hover {
    background-color: #1a252f;
}

@media print {
    body {
        background: #fff;
    }

    .btn {
        display: none;
    }

    .letter {
        border: none;
        width: 100%;
        margin: 0;
        padding: 0;
    }
}

@page {
    size: A4;
    margin: 20mm;
}

.footer {
    margin-top: 60px;
}
</style>
</head>
<body>

<div class="letter">

<div class="header">
    <img src="../uploads/school logo.png">
    <h2>THE NAIROBI NATIONAL POLYTECHNIC</h2>
    <p><strong>OFFICIAL ADMISSION LETTER</strong></p>
</div>

<p><strong>Serial No:</strong> <?= htmlspecialchars($letter['serial_number']) ?></p>
<p><strong>Date:</strong> <?= date('d M Y', strtotime($letter['letter_generated_at'])) ?></p>

<p>Dear <strong><?= htmlspecialchars($letter['full_name']) ?></strong>,</p>

<p>
We are pleased to inform you that you have been offered admission at
<strong>The Nairobi National Polytechnic</strong>.
</p>

<p>
<strong>Course:</strong> <?= htmlspecialchars($letter['course_applied']) ?><br>
<strong>Intake:</strong> <?= htmlspecialchars($letter['intake']) ?>
</p>

<p>
Please report to the institution with this letter for registration and issuance
of your student ID.
</p>

<div class="footer">
<p>
_________________________<br>
Registrar Academics
</p>
</div>

</div>

</body>
</html>
