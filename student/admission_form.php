<?php
require_once '../includes/db.php';
require_once '../includes/auth_student.php';

require_student();

$user_id = $_SESSION['user_id'];

// Ensure readmission approved
$stmt = $pdo->prepare("SELECT readmission_status FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user['readmission_status'] !== 'approved') {
    die("Readmission not approved.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        INSERT INTO admission_forms (
            user_id, surname, other_names, id_passport, county,
            date_of_birth, email, phone, gender,
            course_applied, mode_of_study, intake, kcse_grade,
            previous_school1, previous_school2
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $user_id,
        $_POST['surname'],
        $_POST['other_names'],
        $_POST['id_passport'],
        $_POST['county'],
        $_POST['dob'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['gender'],
        $_POST['course'],
        $_POST['mode'],
        $_POST['intake'],
        $_POST['kcse'],
        $_POST['school1'],
        $_POST['school2']
    ]);

        // ✅ THIS WAS MISSING
    $pdo->prepare("
        UPDATE users
        SET admission_status = 'pending'
        WHERE id = ?
    ")->execute([$user_id]);

    header("Location: dashboard.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Application Form for Admission</title>
<link rel="stylesheet" href="../css/style.css">

<style>
body { background:#f4f4f4; }
.form-wrapper {
    max-width:900px;
    margin:30px auto;
    background:#fff;
    padding:30px;
    font-family: "Times New Roman", serif;
    color:#000;
}
.header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    border-bottom:2px solid #000;
    padding-bottom:10px;
}
.header img { height:80px; }
.header-center { text-align:center; flex:1; }
.header-center h1 { margin:0; font-size:22px; }
.header-center p { margin:2px 0; font-size:13px; }
.serial-box {
    border:1px solid #000;
    padding:5px;
    font-size:12px;
    text-align:center;
    width:120px;
    height:70px;
}
h2 { text-align:center; margin:15px 0; }
.section {
    border:1px solid #000;
    padding:15px;
    margin-bottom:20px;
}
.section h3 { margin-top:0; font-size:16px; }
.row {
    display:flex;
    gap:15px;
    margin-bottom:10px;
    width: 100%;
    height: auto;
}
.row label {
    flex:1;
    font-size:14px;
}
input, select, textarea {
    width:100%;
    padding:5px;
    border:1px solid #000;
}
.checkbox-group {
    display:flex;
    gap:20px;
    margin-top:5px;
}
table {
    width:100%;
    border-collapse:collapse;
}
table, th, td {
    border:1px solid #000;
}
th, td {
    padding:6px;
    font-size:14px;
}
.small-text {
    font-size:13px;
    margin-top:10px;
}
.signature-row {
    display:flex;
    justify-content:space-between;
    margin-top:20px;
    width: 100%;
    height: auto;
}
.submit-btn {
    padding:10px 25px;
    font-size:16px;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="form-wrapper">

<!-- HEADER -->
<div class="header">
    <img src="../uploads/school logo.png" alt="TNNP Logo">

    <div class="header-center">
        <h1>THE NAIROBI NATIONAL POLYTECHNIC</h1>
        <p>P.O. Box 30039 – 00100 Nairobi</p>
        <p>Email: info@nairobinationalpoly.ac.ke | Website: www.nairobipoly.ac.ke</p>
        <h2>APPLICATION FORM FOR ADMISSION</h2>
    </div>

    <div class="serial-box">
        <strong>For official use only</strong><br>
        Serial No:<br>
       <!--<input style="border:none;width:100%;text-align:center"> -->
    </div>
</div>

<form method="post">

<!-- SECTION A -->
<div class="section">
<h3>SECTION A: PERSONAL DATA</h3>

<div class="row">
    <label>Surname
        <input name="surname" required>
    </label>
    <label>Other Names
        <input name="other_names" required>
    </label>
</div>

<div class="row">
    <label>ID / Passport No
        <input name="id_passport" required>
    </label>
    <label>County
        <input name="county" required>
    </label>
</div>

<div class="row">
    <label>Date of Birth
        <input type="date" name="dob" required>
    </label>
    <label>Telephone No
        <input name="phone" required>
    </label>
</div>

<div class="row">
    <label>Email
        <input type="email" name="email" required>
    </label>
</div>

<label>Gender (Tick appropriate)</label>
<div class="checkbox-group">
    <label><input type="radio" name="gender" value="Male" required> Male</label>
    <label><input type="radio" name="gender" value="Female"> Female</label>
</div>

<div class="row">
    <label>Course Applied For
        <input name="course" required>
    </label>
    <label>Module / Level
        <input name="module">
    </label>
</div>

<div class="row">
    <label>KCSE Mean Grade
        <input name="kcse" required>
    </label>
</div>

<label>Mode of Study (Tick appropriate)</label>
<div class="checkbox-group">
    <label><input type="radio" name="mode" value="Full-time" required> Full-time</label>
    <label><input type="radio" name="mode" value="Part-time"> Part-time</label>
    <label><input type="radio" name="mode" value="Evening"> Evening</label>
</div>

<label>Intake Period (Tick appropriate)</label>
<div class="checkbox-group">
    <label><input type="radio" name="intake" value="January" required> January</label>
    <label><input type="radio" name="intake" value="May"> May</label>
    <label><input type="radio" name="intake" value="September"> September</label>
</div>
</div>

<!-- SECTION B -->
<div class="section">
<h3>SECTION B: ACADEMIC PROFILE</h3>

<table>
<tr>
    <th>Institution / School Name</th>
    <th>From (Month/Year)</th>
    <th>To (Month/Year)</th>
</tr>
<tr>
    <td><input name="school1"></td>
    <td><input></td>
    <td><input></td>
</tr>
<tr>
    <td><input name="school2"></td>
    <td><input></td>
    <td><input></td>
</tr>
</table>
</div>

<!-- SECTION C -->
<div class="section">
<h3>SECTION C: FOR CONTINUING STUDENTS ONLY</h3>

<div class="row">
    <label>C.R.N.M Status:
      <!--  <input> -->
    </label>
</div>

<div class="signature-row">
    <label>HOD Signature:
     <!--  <input> -->
    </label>
    <label>Date & Stamp:
    <!--    <input> -->
    </label>
</div>
</div>

<!-- FOOTER TEXT -->
<div class="small-text">
<ol>
<li>Attach photocopies of KCSE Certificate, School Leaving Certificate, National ID Card and Birth Certificate.</li>
<li>For Continuing Students, attach previous Module Results Slip / Certificate.</li>
<li>Pay non-refundable application fee of Ksh. 500.00 via LIPA NA MPESA – PAY BILL: 625625, ACCOUNT: NTT# Your Admission Number.</li>
<li>Send or hand deliver this application form with attached documents to:<br>
<strong>The Principal, The Nairobi National Polytechnic, P.O. Box 30039-00100, Nairobi.</strong></li>
</ol>
</div>

<button class="submit-btn" type="submit">Submit Application</button>

</form>
</div>

</body>
</html>

