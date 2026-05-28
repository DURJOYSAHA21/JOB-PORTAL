<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { 
    header("Location: ../recruiter-login-view.php"); exit(); 
}

require_once "../../../model/recruiter/recruiter-candidate-search-model.php";
require_once "../../../model/recruiter/recruiter-client-model.php";
require_once "../../../model/job-model.php";

$recruiter_user_id = (int)$_SESSION['user']['id'];
$recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
$seeker_id = (int)($_GET['seeker_id'] ?? 0);
$seeker = $seeker_id > 0 ? getSeekerPublicProfile($seeker_id) : null;
$jobs = getRecruiterJobs($recruiterProfileId);
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;
unset($_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Outreach - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/outreach.css">
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
    <a href="outreach-list-view.php">Outreach</a>
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
                <h1>Send Outreach Message</h1>
                <a href="outreach-list-view.php" class="btn-back">View All Outreach</a>
            </div>

            <?php if($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
            <?php if(!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach($errors as $e) echo htmlspecialchars($e).'<br>'; ?>
                </div>
            <?php endif; ?>

            <div class="form-card">
                <form method="POST" action="../../../controller/recruiter/recruiter-outreach-send-controller.php">
                    <?php if($seeker): ?>
                        <div class="form-group">
                            <label>To</label>
                            <input type="text" value="<?php echo htmlspecialchars($seeker['name'] . ' - ' . ($seeker['headline'] ?? 'N/A')); ?>" disabled>
                            <input type="hidden" name="seeker_id" value="<?php echo $seeker_id; ?>">
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label>Select Seeker</label>
                            <select name="seeker_id" required>
                                <option value="">-- Search and select --</option>
                                <?php $candidates = getAllCandidates(); 
                                foreach($candidates as $c): ?>
                                    <option value="<?php echo $c['user_id']; ?>">
                                        <?php echo htmlspecialchars($c['name'] . ' - ' . ($c['headline'] ?? 'N/A')); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Job Opportunity</label>
                        <select name="job_id" required>
                            <option value="">-- Select Job --</option>
                            <?php foreach($jobs as $job): ?>
                                <option value="<?php echo $job['id']; ?>">
                                    <?php echo htmlspecialchars($job['title'] . ' (' . ($job['client_name'] ?? 'N/A') . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="6" placeholder="Write your outreach message to the candidate about this job opportunity..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Send Outreach</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>