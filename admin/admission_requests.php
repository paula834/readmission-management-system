<?php
require_once '../includes/db.php';
require_once '../includes/auth_admin.php';

require_admin();

$stmt = $pdo->query("
    SELECT 
        af.id,
        u.full_name,
        u.email,
        af.course_applied,
        af.intake,
        af.created_at
    FROM admission_forms af
    JOIN users u ON af.user_id = u.id
    WHERE af.status = 'pending'
    ORDER BY af.created_at ASC
");
$admissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admission Requests</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/admission_requests.css">

<style>
     .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background: #0056b3;
        }
</style>

</head>
<body>
<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

<h2>Pending Admission Requests</h2>

<?php if (!$admissions): ?>
<p>No pending admission requests.</p>
<?php else: ?>
<table border="1" cellpadding="8">
<tr>
    <th>Student</th>
    <th>Email</th>
    <th>Course</th>
    <th>Intake</th>
    <th>Submitted On</th>
    <th>Action</th>
</tr>

<?php foreach ($admissions as $a): ?>
<tr>
<td><?= htmlspecialchars($a['full_name']) ?></td>
<td><?= htmlspecialchars($a['email']) ?></td>
<td><?= htmlspecialchars($a['course_applied']) ?></td>
<td><?= htmlspecialchars($a['intake']) ?></td>
<td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
<td>
    <a href="approve_admission.php?id=<?= $a['id'] ?>">Approve</a>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

</body>
</html>
