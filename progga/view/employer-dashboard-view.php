<?php
session_start();
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("employer");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employer Dashboard</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>

<div class="role-card">
    <img src="assets/role-picture.svg" alt="Dashboard picture">
    <h1>Employer Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>.</p>
    <p>This page confirms employer login is working after admin approval.</p>
    <a class="button-link" href="../controller/logout-controller.php">Logout</a>
</div>

</body>
</html>
