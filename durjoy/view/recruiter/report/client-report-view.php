<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { 
    header("Location: ../recruiter-login-view.php"); exit(); 
}

require_once "../../../model/recruiter/recruiter-report-model.php";
require_once "../../../model/recruiter/recruiter-client-model.php";

$recruiter_user_id = (int)$_SESSION['user']['id'];
$recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
$clients = getRecruiterClients($recruiter_user_id);

$selectedClientId = (int)($_GET['client_id'] ?? 0);
$report = null;
if($selectedClientId > 0) {
    $report = getClientReport($recruiterProfileId, $selectedClientId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Report - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/report.css">
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
    <a href="../placement/placement-history-view.php">Placements</a>
    <a href="../analytics/recruiter-analytics-view.php">Analytics</a>
    <a href="client-report-view.php">Reports</a>
    <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
</nav>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <h1>Client Report</h1>
                <p>View detailed performance report for a specific client</p>
            </div>

            <!-- Client Selector -->
            <div class="selector-card">
                <form method="GET" action="client-report-view.php">
                    <div class="selector-row">
                        <div class="form-group">
                            <label>Select Client</label>
                            <select name="client_id" onchange="this.form.submit()">
                                <option value="">-- Choose a Client --</option>
                                <?php foreach($clients as $client): ?>
                                    <option value="<?php echo $client['id']; ?>" <?php echo $selectedClientId == $client['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($client['company_name']); ?>
                                        (<?php echo $client['is_registered'] ? 'Registered' : 'Standalone'; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <?php if($report): ?>
                <!-- Report Content -->
                <div class="kpi-row">
                    <div class="kpi-card">
                        <div class="kpi-number"><?php echo $report['total_jobs']; ?></div>
                        <div class="kpi-label">Total Jobs Posted</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-number"><?php echo $report['active_jobs']; ?></div>
                        <div class="kpi-label">Active Jobs</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-number"><?php echo $report['total_applications']; ?></div>
                        <div class="kpi-label">Total Applications</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-number"><?php echo $report['hired_count']; ?></div>
                        <div class="kpi-label">Hired</div>
                    </div>
                </div>

                <div class="report-grid">
                    <!-- Applications Per Job -->
                    <div class="report-card">
                        <h2>Applications Per Job</h2>
                        <?php if(empty($report['jobs'])): ?>
                            <p class="empty-text">No jobs posted for this client.</p>
                        <?php else: ?>
                            <table>
                                <thead><tr><th>Job Title</th><th>Status</th><th>Applications</th></tr></thead>
                                <tbody>
                                    <?php foreach($report['jobs'] as $job): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($job['title']); ?></strong></td>
                                            <td><span class="status-badge status-<?php echo $job['status']; ?>"><?php echo ucfirst($job['status']); ?></span></td>
                                            <td><?php echo $job['app_count']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>

                    <!-- Pipeline Stage Counts -->
                    <div class="report-card">
                        <h2>Pipeline Stage Counts</h2>
                        <?php if($report['total_applications'] == 0): ?>
                            <p class="empty-text">No applications yet.</p>
                        <?php else: ?>
                            <div class="stage-list">
                                <?php 
                                $stages = [
                                    ['label' => 'Submitted', 'count' => $report['stage_submitted'], 'color' => '#4299e1'],
                                    ['label' => 'Reviewed', 'count' => $report['stage_reviewed'], 'color' => '#ed8936'],
                                    ['label' => 'Shortlisted', 'count' => $report['stage_shortlisted'], 'color' => '#48bb78'],
                                    ['label' => 'Interview', 'count' => $report['stage_interview'], 'color' => '#9f7aea'],
                                    ['label' => 'Rejected', 'count' => $report['stage_rejected'], 'color' => '#fc8181'],
                                    ['label' => 'Hired', 'count' => $report['stage_hired'], 'color' => '#38a169'],
                                ];
                                $maxCount = max(array_column($stages, 'count'), 1);
                                foreach($stages as $s): 
                                    $w = ($s['count'] / $maxCount) * 100;
                                ?>
                                    <div class="stage-row">
                                        <span class="stage-label"><?php echo $s['label']; ?></span>
                                        <div class="stage-bar-wrapper">
                                            <div class="stage-bar" style="width: <?php echo $w; ?>%; background: <?php echo $s['color']; ?>;"><?php echo $s['count']; ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif($selectedClientId > 0): ?>
                <p class="empty-text">No data available for this client.</p>
            <?php else: ?>
                <div class="empty-state">
                    <p>Select a client above to generate their report.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>