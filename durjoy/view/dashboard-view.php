<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: login-view.php");
    exit();
}

require_once "../model/company-info-model.php";
require_once "../model/job-model.php";

$userId = $_SESSION['user']['id'];
$companyInfo = getCompanyInfo($userId);

require_once "../db.php";
$conn = connect();

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM jobs WHERE employer_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$jobCount = $result->fetch_assoc()['count'];

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM jobs WHERE employer_id = ? AND status = 'active'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$activeJobs = $result->fetch_assoc()['count'];

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.employer_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$appCount = $result->fetch_assoc()['count'];

$recentJobs = getEmployerJobs($userId);
$recentJobs = array_slice($recentJobs, 0, 5);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - HireHub</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
                <a href="dashboard-view.php" class="active">Dashboard</a>
                <a href="company-profile-view.php">Company Profile</a>
                <a href="jobs/post-job-view.php">Post a Job</a>
                <a href="jobs/manage-jobs-view.php">Manage Jobs</a>
                <a href="jobs/view-applications-view.php">Applications</a>
                <a href="jobs/shortlisted-view.php">Shortlisted</a>
                <a href="company-analytics-view.php">Analytics</a>
                <a href="jobs/recruiter-relationships-view.php">Recruiters</a>
                <a href="jobs/submit-complaint-view.php">Complaints</a>
                <a href="../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php if(!$companyInfo || !$companyInfo['company_name']): ?>
            <div class="profile-incomplete">
                <p>Your company profile is incomplete. Complete it to start posting jobs.</p>
                <a href="company-profile-view.php">Complete Profile</a>
            </div>
            <?php endif; ?>

            <div class="welcome-banner">
                <h1>Welcome back, <?php echo htmlspecialchars($companyInfo['company_name'] ?? $_SESSION['user']['name']); ?>!</h1>
                <p>Manage your job postings and applications from your dashboard</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $jobCount; ?></div>
                    <div class="stat-label">Total Jobs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $activeJobs; ?></div>
                    <div class="stat-label">Active Jobs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $appCount; ?></div>
                    <div class="stat-label">Total Applications</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $companyInfo ? 'OK' : '--'; ?></div>
                    <div class="stat-label">Profile Status</div>
                </div>
            </div>

            <div class="recent-jobs">
                <div class="section-header">
                    <h2>Recent Job Postings</h2>
                    <a href="jobs/manage-jobs-view.php">View All Jobs</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Applications</th>
                            <th>Deadline</th>
                            <th>Days Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($recentJobs)): ?>
                            <tr class="empty-row">
                                <td colspan="5">No jobs posted yet. <a href="jobs/post-job-view.php">Post your first job!</a></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($recentJobs as $job): 
                                $daysLeft = ceil((strtotime($job['deadline']) - time()) / (60 * 60 * 24));
                            ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($job['title']); ?></strong></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $job['status']; ?>">
                                            <?php echo ucfirst($job['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $job['application_count']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($job['deadline'])); ?></td>
                                    <td>
                                        <?php if($daysLeft > 0): ?>
                                            <span style="color: <?php echo $daysLeft <= 3 ? '#e53e3e' : ($daysLeft <= 7 ? '#dd6b20' : '#38a169'); ?>; font-weight: 600;">
                                                <?php echo $daysLeft; ?> days
                                            </span>
                                        <?php else: ?>
                                            <span style="color: #e53e3e; font-weight: 600;">Expired</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="company-profile-view.php" class="action-btn">Edit Company Profile</a>
                    <a href="jobs/post-job-view.php" class="action-btn">Post New Job</a>
                    <a href="jobs/view-applications-view.php" class="action-btn">View Applications</a>
                    <a href="jobs/manage-jobs-view.php" class="action-btn">Manage Jobs</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>