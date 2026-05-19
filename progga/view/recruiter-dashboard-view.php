<?php
session_start();
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("recruiter");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recruiter Dashboard</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>

<div class="role-card">
    <img src="assets/role-picture.svg" alt="Dashboard picture">
    <h1>Recruiter Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>.</p>
    <p>This page confirms recruiter login is working after admin approval.</p>
    <a class="button-link" href="../controller/logout-controller.php">Logout</a>
</div>

</body>
</html>
