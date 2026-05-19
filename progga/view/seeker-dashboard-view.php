<?php
session_start();
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("seeker");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Job Seeker Dashboard</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>

<div class="role-card">
    <img src="assets/role-picture.svg" alt="Dashboard picture">
    <h1>Job Seeker Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>.</p>
    <p>This page confirms seeker login is working.</p>
    <a class="button-link" href="../controller/logout-controller.php">Logout</a>
</div>

</body>
</html>
