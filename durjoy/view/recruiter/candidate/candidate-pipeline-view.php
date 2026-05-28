<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { 
    header("Location: ../recruiter-login-view.php"); exit(); 
}

require_once "../../../model/recruiter/recruiter-pipeline-model.php";
require_once "../../../model/recruiter/recruiter-client-model.php";

$recruiter_user_id = (int)$_SESSION['user']['id'];
$recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
$pipeline = getCandidatePipeline($recruiterProfileId);

// Count by stage
$stageCounts = [
    'submitted' => 0, 'reviewed' => 0, 'shortlisted' => 0, 
    'interview' => 0, 'rejected' => 0
];
foreach($pipeline as $c) {
    if(isset($stageCounts[$c['status']])) $stageCounts[$c['status']]++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Candidate Pipeline - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/pipeline.css">
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
    <a href="candidate-search-view.php">Search Candidates</a>
    <a href="../outreach/outreach-list-view.php">Outreach</a>
    <a href="../application/view-applications-view.php">Applications</a>
    <a href="candidate-pipeline-view.php">Pipeline</a>
    <a href="../placement/placement-history-view.php">Placements</a>
    <a href="../analytics/recruiter-analytics-view.php">Analytics</a>
    <a href="../report/client-report-view.php">Reports</a>
    <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
</nav>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <h1>Candidate Pipeline</h1>
                <p>Unified view of all candidates across all clients and their current stage</p>
            </div>

            <!-- Stage Summary Cards -->
            <div class="pipeline-stages">
                <div class="stage-card stage-submitted">
                    <div class="stage-count"><?php echo $stageCounts['submitted']; ?></div>
                    <div class="stage-label">Submitted</div>
                </div>
                <div class="stage-card stage-reviewed">
                    <div class="stage-count"><?php echo $stageCounts['reviewed']; ?></div>
                    <div class="stage-label">Reviewed</div>
                </div>
                <div class="stage-card stage-shortlisted">
                    <div class="stage-count"><?php echo $stageCounts['shortlisted']; ?></div>
                    <div class="stage-label">Shortlisted</div>
                </div>
                <div class="stage-card stage-interview">
                    <div class="stage-count"><?php echo $stageCounts['interview']; ?></div>
                    <div class="stage-label">Interview</div>
                </div>
                <div class="stage-card stage-rejected">
                    <div class="stage-count"><?php echo $stageCounts['rejected']; ?></div>
                    <div class="stage-label">Rejected</div>
                </div>
            </div>

            <!-- Pipeline Table -->
            <div class="pipeline-card">
                <div class="pipeline-header">
                    <h2>All Candidates (<?php echo count($pipeline); ?>)</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job Title</th>
                            <th>Client</th>
                            <th>Stage</th>
                            <th>Experience</th>
                            <th>Applied Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($pipeline)): ?>
                            <tr class="empty-row"><td colspan="6">No candidates in pipeline yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($pipeline as $c): ?>
                                <tr>
                                    <td>
                                        <div class="candidate-name"><?php echo htmlspecialchars($c['candidate_name']); ?></div>
                                        <div class="candidate-email"><?php echo htmlspecialchars($c['candidate_email']); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($c['job_title']); ?></td>
                                    <td><?php echo htmlspecialchars($c['client_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="stage-badge stage-<?php echo $c['status']; ?>">
                                            <?php echo ucfirst($c['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($c['years_experience'] ?? 'N/A'); ?> yrs</td>
                                    <td><?php echo date('M d, Y', strtotime($c['applied_at'])); ?></td>
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