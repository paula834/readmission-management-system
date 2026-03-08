<?php
require_once __DIR__ . '/../includes/auth_admin.php';
require_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../css/admin.css">
</head>

<body>
<div class="container">

<header>
    <h1>The Nairobi National Polytechnic</h1>
    <p>Admin Dashboard</p>
</header>

<div class="cards">

    <a class="card" href="readmission_requests.php">
        <h3>Readmission Requests</h3>
        <p>Review & approve student readmissions</p>
    </a>

    <a class="card" href="admission_requests.php">
        <h3>Admission Requests</h3>
        <p>Approve submitted admission forms</p>
    </a>

    <a class="card" href="students.php">
        <h3>Registered Students</h3>
        <p>View all registered students</p>
    </a>

    <a class="card logout" href="../logout.php">
        <h3>Logout</h3>
        <p>Exit admin session</p>
    </a>

</div>

</div>
</body>
</html>
