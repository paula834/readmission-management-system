<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_admin.php';

require_admin(); // Make sure only admin can access

/* Fetch Approved Registered Students */
$stmt = $pdo->prepare("
    SELECT 
        u.full_name,
        u.email,
        af.serial_number,
        af.course_applied,
        af.intake,
        af.letter_generated_at
    FROM admission_forms af
    JOIN users u ON af.user_id = u.id
    WHERE af.status = 'approved'
    ORDER BY af.letter_generated_at DESC
");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        tr:hover {
            background: #e9f2ff;
        }

        .no-data {
            text-align: center;
            padding: 20px;
        }

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

<h2>Registered Students</h2>

<table>
    <tr>
        <th>Serial Number</th>
        <th>Student Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Intake</th>
        <th>Date Approved</th>
    </tr>

    <?php if (count($students) > 0): ?>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo htmlspecialchars($student['serial_number']); ?></td>
                <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
                <td><?php echo htmlspecialchars($student['course_applied']); ?></td>
                <td><?php echo htmlspecialchars($student['intake']); ?></td>
                <td>
                    <?php 
                        echo date('d M Y', strtotime($student['letter_generated_at'])); 
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="no-data">
                No registered students found.
            </td>
        </tr>
    <?php endif; ?>

</table>

</body>
</html>