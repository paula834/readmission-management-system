<?php
require_once '../includes/db.php';
require_once '../includes/auth_admin.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if (!$id || !in_array($action, ['approve', 'reject'])) {
    header("Location: readmission_requests.php");
    exit;
}

/* 🔥 USE CORRECT TABLE NAME */
$stmt = $pdo->prepare("SELECT user_id FROM readmissions WHERE id = ?");
$stmt->execute([$id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    header("Location: readmission_requests.php");
    exit;
}

$status = ($action === 'approve') ? 'approved' : 'rejected';

$pdo->beginTransaction();

try {
    /* Update readmission request */
    $pdo->prepare("
        UPDATE readmissions
        SET status = ?, reviewed_at = NOW()
        WHERE id = ?
    ")->execute([$status, $id]);

    /* Update users table */
    $pdo->prepare("
        UPDATE users
        SET readmission_status = ?
        WHERE id = ?
    ")->execute([$status, $request['user_id']]);

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    die("Error processing request");
}

header("Location: readmission_requests.php");
exit;
