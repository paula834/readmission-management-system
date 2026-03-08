<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_student.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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
$logoPath = $_SERVER['DOCUMENT_ROOT'] . '/Readmission/uploads/school_logo.png';

if (!$logoPath) {
    die("Logo file not found. Check file name and path.");
}
/* HTML Design */
$html = '
<!doctype html>
<html>
<head>
<style>
body {
    font-family: DejaVu Sans, sans-serif;
    line-height: 1.6;
}
.letter {
    width: 100%;
    padding: 30px;
}
.header {
    text-align: center;
}
.logo {
    width: 100px;
}
.footer {
    margin-top: 60px;
}
</style>
</head>
<body>

<div class="letter">

<div class="header">
    <img src="file://'.$logoPath.'" class="logo">
    <h2>THE NAIROBI NATIONAL POLYTECHNIC</h2>
    <strong>OFFICIAL ADMISSION LETTER</strong>
</div>

<br>

<p><strong>Serial No:</strong> '.$letter['serial_number'].'</p>
<p><strong>Date:</strong> '.date('d M Y', strtotime($letter['letter_generated_at'])).'</p>

<p>Dear <strong>'.$letter['full_name'].'</strong>,</p>

<p>
We are pleased to inform you that you have been offered admission at
The Nairobi National Polytechnic.
</p>

<p>
<strong>Course:</strong> '.$letter['course_applied'].'<br>
<strong>Intake:</strong> '.$letter['intake'].'
</p>

<p>
Please report to the institution with this letter for registration and issuance of your student ID.
</p>

<div class="footer">
_________________________<br>
Registrar Academics
</div>

</div>

</body>
</html>
';

/* Dompdf Setup */
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* Download PDF */
$dompdf->stream(
    "Admission_Letter_" . $letter['serial_number'] . ".pdf",
    ["Attachment" => true]
);