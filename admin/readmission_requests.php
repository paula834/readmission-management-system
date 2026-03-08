<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_admin.php';

require_admin();

// Fetch pending readmission requests INCLUDING documents
$stmt = $pdo->query("
    SELECT 
        r.id,
        r.last_year,
        r.reason,
        r.created_at,
        r.result_slip,
        r.fee_statement,
        u.full_name,
        u.email
    FROM readmissions r
    JOIN users u ON r.user_id = u.id
    WHERE r.status = 'pending'
    ORDER BY r.created_at ASC
");

$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Readmission Requests</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="../css/readmission_requests.css">

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

<h2>Pending Readmission Applications</h2>

<?php if (!$requests): ?>
    <p>No pending applications.</p>
<?php else: ?>

<table>
<tr>
    <th>Student</th>
    <th>Email</th>
    <th>Last Year</th>
    <th>Reason</th>
    <th>Documents</th>
    <th>Applied On</th>
    <th>Action</th>
</tr>

<?php foreach ($requests as $r): ?>
<tr>
    <td><?= htmlspecialchars($r['full_name']) ?></td>
    <td><?= htmlspecialchars($r['email']) ?></td>
    <td>Module <?= htmlspecialchars($r['last_year']) ?></td>
    <td><?= nl2br(htmlspecialchars($r['reason'])) ?></td>

    <td>
        <?php if (!empty($r['result_slip'])): ?>
            <a class="doc-link"
               href="../uploads/readmission_docs/<?= htmlspecialchars($r['result_slip']) ?>"
               target="_blank">
               📄 View Result Slip
            </a><br>
        <?php else: ?>
            No Result Slip<br>
        <?php endif; ?>

        <?php if (!empty($r['fee_statement'])): ?>
            <a class="doc-link"
               href="../uploads/readmission_docs/<?= htmlspecialchars($r['fee_statement']) ?>"
               target="_blank">
               💰 View Fee Statement
            </a>
        <?php else: ?>
            No Fee Statement
        <?php endif; ?>
    </td>

    <td><?= date('d M Y', strtotime($r['created_at'])) ?></td>

    <td>
        <a class="approve"
           href="approve_readmission.php?id=<?= $r['id'] ?>&action=approve"
           onclick="return confirm('Approve this application?')">
           Approve
        </a>
        |
        <a class="reject"
           href="approve_readmission.php?id=<?= $r['id'] ?>&action=reject"
           onclick="return confirm('Reject this application?')">
           Reject
        </a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>

</body>
</html>