<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/application-model.php";

$user_id = (int)$_SESSION['user']['id'];
$jobs = getEmployerJobTitles($user_id);
$applications = getApplicationsByEmployer($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applications - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/applications.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
                <a href="../dashboard-view.php">Dashboard</a>
                <a href="../company-profile-view.php">Company Profile</a>
                <a href="post-job-view.php">Post a Job</a>
                <a href="manage-jobs-view.php">Manage Jobs</a>
                <a href="view-applications-view.php" class="active">Applications</a>
                <a href="shortlisted-view.php">Shortlisted</a>
                <a href="../company-analytics-view.php">Analytics</a>
                <a href="recruiter-relationships-view.php">Recruiters</a>
                <a href="submit-complaint-view.php">Complaints</a>
                <a href="../../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Applications</h1>
            </div>

            <div class="filter-card">
                <h3>Filter Applications</h3>
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Job Posting</label>
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
                    <div class="filter-group">
                        <label>Experience Level</label>
                        <select id="filter-experience" onchange="applyFilters()">
                            <option value="">All Levels</option>
                            <option value="entry">Entry</option>
                            <option value="mid">Mid</option>
                            <option value="senior">Senior</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Date From</label>
                        <input type="date" id="filter-date-from" onchange="applyFilters()">
                    </div>
                    <div class="filter-group">
                        <label>Date To</label>
                        <input type="date" id="filter-date-to" onchange="applyFilters()">
                    </div>
                    <div class="filter-group">
                        <button type="button" class="btn btn-reset" onclick="resetFilters()">Reset</button>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-header">
                    <h2>All Applications</h2>
                    <span class="result-count" id="result-count"><?php echo count($applications); ?> results</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Job Title</th>
                            <th>Headline</th>
                            <th>Experience</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                            <th>Resume</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="applications-table-body">
                        <?php if(empty($applications)): ?>
                            <tr class="empty-row">
                                <td colspan="8">No applications received yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($applications as $app): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($app['applicant_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                                    <td><?php echo htmlspecialchars($app['headline'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($app['years_experience'] ?? 'N/A'); ?> yrs</td>
                                    <td><span class="status-badge status-<?php echo strtolower($app['status']); ?>"><?php echo ucfirst($app['status']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                    <td>
                                        <?php $resumeFile = $app['resume_path'] ?: ($app['seeker_resume'] ?? ''); ?>
                                        <?php if($resumeFile): ?>
                                            <a href="../../uploads/resumes/<?php echo htmlspecialchars($resumeFile); ?>" target="_blank" class="btn btn-download">Resume</a>
                                        <?php else: ?>
                                            <span style="color: #a0aec0;">No resume</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><a href="applicant-detail-view.php?app_id=<?php echo $app['id']; ?>" class="btn btn-view">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../../controller/api/applications.js"></script>
</body>
</html>