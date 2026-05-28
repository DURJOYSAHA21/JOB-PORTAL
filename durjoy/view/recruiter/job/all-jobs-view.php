<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { header("Location: ../recruiter-login-view.php"); exit(); }

require_once "../../../model/job-model.php";
require_once "../../../model/recruiter/recruiter-client-model.php";

$recruiter_user_id = (int)$_SESSION['user']['id'];
$recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
$clients = getRecruiterClients($recruiter_user_id);
$jobs = getRecruiterJobs($recruiterProfileId);
$categories = getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Jobs - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/all-jobs.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
    <a href="../recruiter-dashboard-view.php">Dashboard</a>
    <a href="../recruiter-profile-view.php">Agency Profile</a>
    <a href="../client/manage-clients-view.php">Client Companies</a>
    <a href="post-job-view.php">Post a Job</a>
    <a href="manage-jobs-view.php">Manage Jobs</a>
    <a href="all-jobs-view.php">All Jobs</a>
    <a href="../candidate/candidate-search-view.php">Search Candidates</a>
    <a href="../outreach/outreach-list-view.php">Outreach</a>
    <a href="../application/view-applications-view.php">Applications</a>
    <a href="../candidate/candidate-pipeline-view.php">Pipeline</a>
    <a href="../placement/placement-history-view.php">Placements</a>
    <a href="../analytics/recruiter-analytics-view.php">Analytics</a>
    <a href="../report/client-report-view.php">Reports</a>
    <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
</nav>
        </aside>
        <main class="main-content">
            <div class="page-header"><h1>All Jobs Across Clients</h1></div>

            <!-- Filters -->
            <div class="filter-card">
                <h3>Filter Jobs</h3>
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Client</label>
                        <select id="filter-client" onchange="applyFilters()">
                            <option value="">All Clients</option>
                            <?php foreach($clients as $client): ?>
                                <option value="<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['company_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select id="filter-status" onchange="applyFilters()">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="closed">Closed</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Category</label>
                        <select id="filter-category" onchange="applyFilters()">
                            <option value="">All</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button type="button" class="btn btn-reset" onclick="resetFilters()">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="table-card">
                <div class="table-header">
                    <h2>Jobs</h2>
                    <span class="result-count" id="result-count"><?php echo count($jobs); ?> results</span>
                </div>
                <table>
                    <thead><tr><th>Title</th><th>Client</th><th>Category</th><th>Status</th><th>Apps</th><th>Deadline</th></tr></thead>
                    <tbody id="jobs-table-body">
                        <?php foreach($jobs as $job): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($job['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($job['client_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($job['category_name'] ?? 'N/A'); ?></td>
                                <td><span class="status-badge status-<?php echo $job['status']; ?>"><?php echo ucfirst($job['status']); ?></span></td>
                                <td><?php echo $job['application_count']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($job['deadline'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="../../../controller/api/recruiter/job-filter.js"></script>
</body>
</html>