<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");

/*
    Normal way: this page should be opened through
    ../controller/admin-dashboard-controller.php.

    This fallback also loads database data if someone opens
    admin-dashboard-view.php directly from the browser.
*/
if (!isset($stats)) {
    require_once __DIR__ . "/../model/admin-model.php";
    $model = new AdminModel();
    $stats = $model->dashboardStats();
}

$stats = $stats ?? [
    "users_by_role" => [
        "admin" => 0,
        "seeker" => 0,
        "employer" => 0,
        "recruiter" => 0
    ],
    "total_active_jobs" => 0,
    "applications_today" => 0,
    "pending_verifications" => 0,
    "pending_employers" => 0
];

$adminName = $_SESSION["name"] ?? $_SESSION["user_name"] ?? "Admin";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../view/assets/admin.css">
</head>
<body>
<?php require_once __DIR__ . "/admin-menu.php"; ?>

<div class="welcome-box">
    <div>
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($adminName); ?>. Here you can manage users, jobs, complaints, policies and reports.</p>
    </div>
    <img src="../view/assets/admin-picture.png" alt="Admin picture">
</div>

<div class="card-grid">
    <div class="card">Admins<strong><?php echo $stats["users_by_role"]["admin"] ?? 0; ?></strong></div>
    <div class="card">Job Seekers<strong><?php echo $stats["users_by_role"]["seeker"] ?? 0; ?></strong></div>
    <div class="card">Employers<strong><?php echo $stats["users_by_role"]["employer"] ?? 0; ?></strong></div>
    <div class="card">Recruiters<strong><?php echo $stats["users_by_role"]["recruiter"] ?? 0; ?></strong></div>
    <div class="card">Active Jobs<strong><?php echo $stats["total_active_jobs"] ?? 0; ?></strong></div>
    <div class="card">Applications Today<strong><?php echo $stats["applications_today"] ?? 0; ?></strong></div>
    <div class="card">Pending Verifications<strong><?php echo $stats["pending_verifications"] ?? 0; ?></strong></div>
    <div class="card">Pending Employer<strong><?php echo $stats["pending_employers"] ?? 0; ?></strong></div>
</div>

</div>
</body>
</html>
