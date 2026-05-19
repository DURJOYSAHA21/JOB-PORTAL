<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function showAdminFlash()
{
    if (isset($_SESSION["admin_success"])) {
        echo '<div class="message-success">' . htmlspecialchars($_SESSION["admin_success"]) . '</div>';
        unset($_SESSION["admin_success"]);
    }

    if (isset($_SESSION["admin_error"])) {
        echo '<div class="message-error">' . htmlspecialchars($_SESSION["admin_error"]) . '</div>';
        unset($_SESSION["admin_error"]);
    }
}

$adminName = $_SESSION["name"] ?? $_SESSION["user_name"] ?? "Admin";
?>
<div class="header">
    <div><strong>Job Portal Admin</strong></div>
    <div>
        <?php echo htmlspecialchars($adminName); ?>
        <a href="../controller/logout-controller.php">Logout</a>
    </div>
</div>

<div class="container">
    <div class="nav">
        <a href="../controller/admin-dashboard-controller.php">Dashboard</a>
        <a href="../controller/admin-users-controller.php?role=employer">Employers</a>
        <a href="../controller/admin-users-controller.php?role=recruiter">Recruiters</a>
        <a href="../controller/admin-users-controller.php?role=seeker">Seekers</a>
        <a href="../controller/admin-categories-controller.php">Categories</a>
        <a href="../controller/admin-jobs-controller.php">Jobs</a>
        <a href="../controller/admin-complaints-controller.php">Complaints</a>
        <a href="../controller/admin-policies-controller.php">Policies</a>
        <a href="../controller/admin-analytics-controller.php">Analytics</a>
        <a href="../controller/admin-announcements-controller.php">Announcements</a>
        <a href="../controller/admin-report-controller.php">Monthly Report</a>
    </div>

    <?php showAdminFlash(); ?>
