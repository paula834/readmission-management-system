<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_admin.php';

require_admin();

if (!isset($_GET['id'])) {
    die('Invalid request');
}

$form_id = (int)$_GET['id'];

/* Generate serial number */
$serial = 'TNNP/ADM/' . date('Y') . '/' . str_pad($form_id, 4, '0', STR_PAD_LEFT);

/* Approve admission */
$stmt = $pdo->prepare("
    UPDATE admission_forms
    SET 
        status = 'approved',
        serial_number = ?,
        registrar_name = 'Registrar Academics',
        letter_generated_at = NOW()
    WHERE id = ?
");
$stmt->execute([$serial, $form_id]);

/* Update user admission status */
$pdo->prepare("
    UPDATE users u
    JOIN admission_forms af ON af.user_id = u.id
    SET u.admission_status = 'approved'
    WHERE af.id = ?
")->execute([$form_id]);

header("Location: admission_requests.php");
exit;
