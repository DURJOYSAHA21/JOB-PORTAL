<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { header("Location: recruiter-login-view.php"); exit(); }
require_once "../../model/recruiter/recruiter-profile-model.php";
$userId = $_SESSION['user']['id'];
$profile = getRecruiterProfile($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recruiter Dashboard - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/recruiter/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
                <a href="recruiter-dashboard-view.php" class="active">Dashboard</a>
                <a href="recruiter-profile-view.php">Agency Profile</a>
                <a href="client/manage-clients-view.php">Client Companies</a>
                <a href="job/post-job-view.php">Post a Job</a>
                <a href="job/manage-jobs-view.php">Manage Jobs</a>
                <a href="candidate/candidate-search-view.php">Search Candidates</a>
                <a href="all-jobs-view.php">All Jobs</a>
                <a href="../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="welcome-banner">
                <h1>Welcome, <?php echo htmlspecialchars($profile['agency_name'] ?? $_SESSION['user']['name']); ?>!</h1>
                <p>Manage your clients, jobs, and candidates from your dashboard</p>
            </div>
            <div class="stats-grid">
                <div class="stat-card"><div class="stat-number">0</div><div class="stat-label">Active Jobs</div></div>
                <div class="stat-card"><div class="stat-number">0</div><div class="stat-label">Client Companies</div></div>
                <div class="stat-card"><div class="stat-number">0</div><div class="stat-label">Candidates Placed</div></div>
            </div>
            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="recruiter-profile-view.php" class="action-btn">Edit Agency Profile</a>
                    <a href="job/post-job-view.php" class="action-btn">Post New Job</a>
                    <a href="client/manage-clients-view.php" class="action-btn">Manage Clients</a>
                    <a href="candidate/candidate-search-view.php" class="action-btn">Search Candidates</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>