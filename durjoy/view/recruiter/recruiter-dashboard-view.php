<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { header("Location: recruiter-login-view.php"); exit(); }

require_once "../../model/recruiter/recruiter-profile-model.php";
require_once "../../model/recruiter/recruiter-client-model.php";

$userId = $_SESSION['user']['id'];
$profile = getRecruiterProfile($userId);
$recruiterProfileId = getRecruiterProfileId($userId);

// Get real stats
require_once "../../db.php";
$conn = connect();

// Active Jobs
$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM jobs WHERE recruiter_id = ? AND status = 'active'");
$stmt->bind_param("i", $recruiterProfileId);
$stmt->execute();
$activeJobs = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// Client Companies
$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM recruiter_clients WHERE recruiter_id = ?");
$stmt->bind_param("i", $recruiterProfileId);
$stmt->execute();
$clientCount = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// Candidates Placed (hired)
$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.recruiter_id = ? AND a.status = 'hired'");
$stmt->bind_param("i", $recruiterProfileId);
$stmt->execute();
$placedCount = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

$conn->close();
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
    <a href="recruiter-dashboard-view.php">Dashboard</a>
    <a href="recruiter-profile-view.php">Agency Profile</a>
    <a href="client/manage-clients-view.php">Client Companies</a>
    <a href="job/post-job-view.php">Post a Job</a>
    <a href="job/manage-jobs-view.php">Manage Jobs</a>
    <a href="job/all-jobs-view.php">All Jobs</a>
    <a href="candidate/candidate-search-view.php">Search Candidates</a>
    <a href="outreach/outreach-list-view.php">Outreach</a>
    <a href="application/view-applications-view.php">Applications</a>
    <a href="candidate/candidate-pipeline-view.php">Pipeline</a>
    <a href="placement/placement-history-view.php">Placements</a>
    <a href="analytics/recruiter-analytics-view.php">Analytics</a>
    <a href="report/client-report-view.php">Reports</a>
    <a href="../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
</nav>
        </aside>
        <main class="main-content">
            <div class="welcome-banner">
                <h1>Welcome, <?php echo htmlspecialchars($profile['agency_name'] ?? $_SESSION['user']['name']); ?>!</h1>
                <p>Manage your clients, jobs, and candidates from your dashboard</p>
            </div>
            <div class="stats-grid">
    <div class="stat-card"><div class="stat-number"><?php echo $activeJobs; ?></div><div class="stat-label">Active Jobs</div></div>
    <div class="stat-card"><div class="stat-number"><?php echo $clientCount; ?></div><div class="stat-label">Client Companies</div></div>
    <div class="stat-card"><div class="stat-number"><?php echo $placedCount; ?></div><div class="stat-label">Candidates Placed</div></div>
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