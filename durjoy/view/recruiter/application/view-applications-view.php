<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { 
    header("Location: ../recruiter-login-view.php"); exit(); 
}

require_once "../../../model/recruiter/recruiter-application-model.php";
require_once "../../../model/recruiter/recruiter-client-model.php";
require_once "../../../model/job-model.php";

$recruiter_user_id = (int)$_SESSION['user']['id'];
$recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
$jobs = getRecruiterJobs($recruiterProfileId);
$applications = getRecruiterApplications($recruiterProfileId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applications - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/applications.css">
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
    <a href="view-applications-view.php">Applications</a>
    <a href="../candidate/candidate-pipeline-view.php">Pipeline</a>
    <a href="../placement/placement-history-view.php">Placements</a>
    <a href="../analytics/recruiter-analytics-view.php">Analytics</a>
    <a href="../report/client-report-view.php">Reports</a>
    <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
</nav>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <h1>Applications</h1>
                <p>View all applications for your clients' jobs</p>
            </div>

            <!-- Filters -->
            <div class="filter-card">
                <h3>Filter Applications</h3>
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Job</label>
                        <select id="filter-job" onchange="applyFilters()">
                            <option value="">All Jobs</option>
                            <?php foreach($jobs as $job): ?>
                                <option value="<?php echo $job['id']; ?>"><?php echo htmlspecialchars($job['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select id="filter-status" onchange="applyFilters()">
                            <option value="">All Statuses</option>
                            <option value="submitted">Submitted</option>
                            <option value="reviewed">Reviewed</option>
                            <option value="shortlisted">Shortlisted</option>
                            <option value="interview">Interview</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="filter-group" style="justify-content: flex-end;">
                        <button type="button" class="btn btn-reset" onclick="resetFilters()">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="table-card">
                <div class="table-header">
                    <h2>Results</h2>
                    <span class="result-count" id="result-count"><?php echo count($applications); ?> applications</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Job Title</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="applications-table-body">
                        <?php if(empty($applications)): ?>
                            <tr class="empty-row"><td colspan="6">No applications yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($applications as $app): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($app['applicant_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                                    <td><?php echo htmlspecialchars($app['client_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span>
                                        <select class="status-dropdown" onchange="updateStatus(<?php echo $app['id']; ?>, this)">
                                            <option value="submitted" <?php echo $app['status']=='submitted'?'selected':''; ?>>Submitted</option>
                                            <option value="reviewed" <?php echo $app['status']=='reviewed'?'selected':''; ?>>Reviewed</option>
                                            <option value="shortlisted" <?php echo $app['status']=='shortlisted'?'selected':''; ?>>Shortlisted</option>
                                            <option value="interview" <?php echo $app['status']=='interview'?'selected':''; ?>>Interview</option>
                                            <option value="rejected" <?php echo $app['status']=='rejected'?'selected':''; ?>>Rejected</option>
                                        </select>
                                        <span class="status-msg" id="msg-<?php echo $app['id']; ?>"></span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                    <td><a href="applicant-detail-view.php?app_id=<?php echo $app['id']; ?>" class="btn btn-view">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="../../../controller/api/recruiter/recruiter-applications.js"></script>
</body>
</html>