<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { 
    header("Location: ../recruiter-login-view.php"); exit(); 
}

require_once "../../../model/recruiter/recruiter-placement-model.php";
require_once "../../../model/recruiter/recruiter-client-model.php";

$recruiter_user_id = (int)$_SESSION['user']['id'];
$recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
$placements = getPlacementHistory($recruiterProfileId);
$totalPlacements = count($placements);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placement History - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/placement.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
<nav>
    <a href="../recruiter-dashboard-view.php">Dashboard</a>
    <a href="../recruiter-profile-view.php">Agency Profile</a>
    <a href="../client/manage-clients-view.php">Client Companies</a>
    <a href="../job/post-job-view.php">Post a Job</a>
    <a href="../job/manage-jobs-view.php">Manage Jobs</a>
    <a href="../job/all-jobs-view.php">All Jobs</a>
    <a href="../candidate/candidate-search-view.php">Search Candidates</a>
    <a href="../outreach/outreach-list-view.php">Outreach</a>
    <a href="../application/view-applications-view.php">Applications</a>
    <a href="../candidate/candidate-pipeline-view.php">Pipeline</a>
    <a href="placement-history-view.php">Placements</a>
    <a href="../analytics/recruiter-analytics-view.php">Analytics</a>
    <a href="../report/client-report-view.php">Reports</a>
    <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
</nav>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1>Placement History</h1>
                    <p>Track all candidates who were hired through your agency</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon">P</div>
                    <div class="stat-number"><?php echo $totalPlacements; ?></div>
                    <div class="stat-label">Total Placements</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($placements, fn($p) => $p['status'] === 'hired')); ?></div>
                    <div class="stat-label">Hired</div>
                </div>
            </div>

            <!-- Placement Table -->
            <div class="placement-card">
                <div class="placement-header">
                    <h2>All Placements</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job Title</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                            <th>Hired Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($placements)): ?>
                            <tr class="empty-row"><td colspan="6">No placements recorded yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($placements as $p): ?>
                                <tr>
                                    <td>
                                        <div class="candidate-name"><?php echo htmlspecialchars($p['candidate_name']); ?></div>
                                        <div class="candidate-email"><?php echo htmlspecialchars($p['candidate_email']); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($p['job_title']); ?></td>
                                    <td><?php echo htmlspecialchars($p['client_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="placement-badge placement-<?php echo $p['status']; ?>">
                                            <?php echo ucfirst($p['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($p['applied_at'])); ?></td>
                                    <td><?php echo $p['hired_date'] ? date('M d, Y', strtotime($p['hired_date'])) : '--'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>