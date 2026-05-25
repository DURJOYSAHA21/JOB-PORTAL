<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../../../model/recruiter/recruiter-client-model.php";
$employers = getAvailableEmployers();
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { header("Location: ../recruiter-login-view.php"); exit(); }
require_once "../../../model/recruiter/recruiter-client-model.php";
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_input']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Client - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/clients.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
<nav>
    <a href="../recruiter-dashboard-view.php">Dashboard</a>
    <a href="../recruiter-profile-view.php">Agency Profile</a>
    <a href="manage-clients-view.php">Client Companies</a>
    <a href="../job/post-job-view.php">Post a Job</a>
    <a href="../job/manage-jobs-view.php">Manage Jobs</a>
    <a href="../job/all-jobs-view.php">All Jobs</a>
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
            <div class="page-header">
                <h1>Add Client Company</h1>
                <a href="manage-clients-view.php" class="btn-back">Back</a>
            </div>
            <div class="form-card">
                <form method="POST" action="../../../controller/recruiter/recruiter-client-controller.php">
                    <div class="form-group">
                        <label>Client Type</label>
                        <select name="client_type" id="client_type" onchange="toggleClientType()">
                            <option value="registered">Registered Employer</option>
                            <option value="standalone">Standalone Company (Not Registered)</option>
                        </select>
                    </div>
                    <div class="form-group" id="registered-section">
                        <label>Select Employer</label>
                        <select name="employer_id">
                            <option value="">-- Select Employer --</option>
                            <?php foreach($employers as $emp): ?>
                                <option value="<?php echo $emp['id']; ?>">
                                    <?php echo htmlspecialchars($emp['company_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group hidden" id="standalone-section">
                        <label>Company Name *</label>
                        <input type="text" name="company_name_override" placeholder="Enter company name" value="<?php echo htmlspecialchars($old['company_name_override'] ?? ''); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Client</button>
                </form>
            </div>
        </main>
    </div>
    <script>
        function toggleClientType() {
            var type = document.getElementById('client_type').value;
            document.getElementById('registered-section').style.display = type === 'registered' ? 'block' : 'none';
            document.getElementById('standalone-section').style.display = type === 'standalone' ? 'block' : 'none';
        }
        toggleClientType();
    </script>
</body>
</html>