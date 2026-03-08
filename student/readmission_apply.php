<?php
require_once '../includes/db.php';
require_once '../includes/auth_student.php';

require_student();

$user_id = $_SESSION['user_id'];

// Fetch student
$stmt = $pdo->prepare("SELECT readmission_status FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Block if already applied
if ($user['readmission_status'] !== 'none') {
    header("Location: dashboard.php");
    exit;
}

// Submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $last_course = trim($_POST['last_course']);
    $last_level  = $_POST['last_level'];
    $last_year   = (int)$_POST['last_year'];
    $reason      = trim($_POST['reason']);

    if ($last_course && $last_level && $last_year && $reason) {

        $uploadDir = __DIR__ . "/../uploads/readmission_docs/";

        // Create folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Allowed file types
        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
        $maxSize = 5 * 1024 * 1024; // 5MB limit

        // Validate Result Slip
        $resultSlipExt = strtolower(pathinfo($_FILES['result_slip']['name'], PATHINFO_EXTENSION));
        if (!in_array($resultSlipExt, $allowedTypes)) {
            die("Invalid Result Slip file type.");
        }

        if ($_FILES['result_slip']['size'] > $maxSize) {
            die("Result Slip file is too large (Max 5MB).");
        }

        // Validate Fee Statement
        $feeStatementExt = strtolower(pathinfo($_FILES['fee_statement']['name'], PATHINFO_EXTENSION));
        if (!in_array($feeStatementExt, $allowedTypes)) {
            die("Invalid Fee Statement file type.");
        }

        if ($_FILES['fee_statement']['size'] > $maxSize) {
            die("Fee Statement file is too large (Max 5MB).");
        }

        // Generate unique file names
        $resultSlipName = time() . "_result_" . uniqid() . "." . $resultSlipExt;
        $feeStatementName = time() . "_fee_" . uniqid() . "." . $feeStatementExt;

        $resultSlipPath = $uploadDir . $resultSlipName;
        $feeStatementPath = $uploadDir . $feeStatementName;

        // Move files
        if (
            move_uploaded_file($_FILES['result_slip']['tmp_name'], $resultSlipPath) &&
            move_uploaded_file($_FILES['fee_statement']['tmp_name'], $feeStatementPath)
        ) {

            // Insert readmission record
            $stmt = $pdo->prepare("
                INSERT INTO readmissions
                (user_id, last_course, last_level, last_year, reason, result_slip, fee_statement, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");

            $stmt->execute([
                $user_id,
                $last_course,
                $last_level,
                $last_year,
                $reason,
                $resultSlipName,
                $feeStatementName
            ]);

            // Update user status
            $pdo->prepare("
                UPDATE users 
                SET readmission_status='pending', 
                    readmission_applied_at=NOW()
                WHERE id=?
            ")->execute([$user_id]);

            header("Location: dashboard.php");
            exit;

        } else {
            die("File upload failed. Please try again.");
        }
    }
}
?>

<!doctype html>
<html>
<head>
    <title>Apply for Readmission</title>
    <link rel="stylesheet" href="../css/readmission_apply.css">
</head>
<body>

<h2>Readmission Application</h2>

<form method="post" enctype="multipart/form-data">

    <label>Last Course Attended</label>
    <input type="text" name="last_course" required>

    <label>Last Level</label>
    <select name="last_level" required>
        <option value="">-- Select --</option>
        <option>Artisan</option>
        <option>Certificate</option>
        <option>Diploma</option>
    </select>

    <label>Last Year / Module</label>
    <select name="last_year" required>
        <option value="">-- Select --</option>
        <option value="1">Module 1</option>
        <option value="2">Module 2</option>
        <option value="3">Module 3</option>
    </select>

    <label>Reason for Readmission</label>
    <textarea name="reason" required></textarea>

    <label>Upload last module Result Slip (PDF/JPG/PNG, Max 5MB)</label>
    <input type="file" name="result_slip" accept=".pdf,.jpg,.jpeg,.png" required>

    <label>Upload Fee Statement (PDF/JPG/PNG, Max 5MB)</label>
    <input type="file" name="fee_statement" accept=".pdf,.jpg,.jpeg,.png" required>

    <br><br>
    <button type="submit">Submit Application</button>

</form>

</body>
</html>