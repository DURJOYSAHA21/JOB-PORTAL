<?php
session_start();
if(!isset($_SESSION["user"])) { header("Location: ../recruiter-login-view.php"); exit(); }
if(isset($_SESSION["is_verified"]) && $_SESSION["is_verified"] == 1) { header("Location: ../recruiter-dashboard-view.php"); exit(); }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pending Verification - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/recruiter/waiting.css">
</head>
<body>
    <div class="box">
        <div class="icon">P</div>
        <h1>Account Pending Verification</h1>
        <span class="badge">Status: Awaiting Admin Approval</span>
        <p>Thank you for registering as a recruiter on HireHub. Your account is currently under review.</p>
        <div class="info-box">
            <p><strong>What happens next?</strong><br><br>
            Our admin team will review your agency details and verify your account. This usually takes 1-2 business days.</p>
        </div>
        <p class="status-msg">Logged in as: <strong><?php echo htmlspecialchars($_SESSION["user"]["email"]); ?></strong></p>
        <p id="checkMsg" class="status-msg">Checking verification status...</p>
        <a class="logout" href="../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
    </div>
    <script>var userId = <?php echo (int)$_SESSION["user"]["id"]; ?>;</script>
    <script src="../../../controller/api/recruiter/waiting.js"></script>
</body>
</html>