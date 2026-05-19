<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/recruiter-model.php";

$user_id = (int)$_SESSION['user']['id'];
$recruiters = getEmployerRecruiters($user_id);

$selectedRecruiterId = (int)($_GET['recruiter_id'] ?? 0);
$recruiterJobs = [];
$selectedRecruiter = null;

if($selectedRecruiterId > 0) {
    $recruiterJobs = getRecruiterJobs($user_id, $selectedRecruiterId);
    foreach($recruiters as $r) {
        if($r['recruiter_id'] == $selectedRecruiterId) {
            $selectedRecruiter = $r;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recruiter Relationships - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/recruiters.css">
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
                <a href="view-applications-view.php">Applications</a>
                <a href="shortlisted-view.php">Shortlisted</a>
                <a href="../company-analytics-view.php">Analytics</a>
                <a href="recruiter-relationships-view.php" class="active">Recruiters</a>
                <a href="submit-complaint-view.php">Complaints</a>
                <a href="../../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Recruiter Relationships</h1>
                <p>View agencies and recruiters posting jobs on your behalf</p>
            </div>

            <?php if(empty($recruiters)): ?>
                <div class="card full-width">
                    <div class="empty-state">
                        <p>No recruiters are managing jobs for your company yet.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="content-grid">
                    <div class="card">
                        <div class="card-header">
                            <h2>Recruiter Agencies (<?php echo count($recruiters); ?>)</h2>
                        </div>
                        <div class="card-body">
                            <div class="recruiter-list">
                                <?php foreach($recruiters as $rec): ?>
                                    <div class="recruiter-item <?php echo ($selectedRecruiterId == $rec['recruiter_id']) ? 'active' : ''; ?>">
                                        <div class="recruiter-header">
                                            <div>
                                                <div class="recruiter-name"><?php echo htmlspecialchars($rec['recruiter_name']); ?></div>
                                                <div class="recruiter-agency"><?php echo htmlspecialchars($rec['agency_name']); ?></div>
                                            </div>
                                            <div class="recruiter-actions">
                                                <a href="?recruiter_id=<?php echo $rec['recruiter_id']; ?>" class="btn btn-view">View Jobs</a>
                                                <a href="submit-complaint-view.php?subject_id=<?php echo $rec['recruiter_id']; ?>" class="btn btn-complain">Report</a>
                                            </div>
                                        </div>
                                        <div class="recruiter-stats">
                                            <div class="recruiter-stat">
                                                <div class="stat-num"><?php echo $rec['jobs_posted']; ?></div>
                                                <div class="stat-lbl">Total Jobs</div>
                                            </div>
                                            <div class="recruiter-stat">
                                                <div class="stat-num"><?php echo $rec['active_jobs']; ?></div>
                                                <div class="stat-lbl">Active</div>
                                            </div>
                                        </div>
                                        <?php if(!empty($rec['specialization'])): ?>
                                            <div class="specialization-tag"><?php echo htmlspecialchars($rec['specialization']); ?></div>
                                        <?php endif; ?>
                                        <div class="recruiter-contact">
                                            Email: <?php echo htmlspecialchars($rec['recruiter_email']); ?>
                                            <?php if(!empty($rec['recruiter_phone'])): ?>
                                                | Phone: <?php echo htmlspecialchars($rec['recruiter_phone']); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h2>
                                <?php if($selectedRecruiter): ?>
                                    Jobs by <?php echo htmlspecialchars($selectedRecruiter['agency_name']); ?>
                                <?php else: ?>
                                    Recruiter Jobs
                                <?php endif; ?>
                            </h2>
                        </div>
                        <div class="card-body">
                            <?php if(!$selectedRecruiter): ?>
                                <div class="empty-state">
                                    <p>Select a recruiter to view their jobs</p>
                                </div>
                            <?php elseif(empty($recruiterJobs)): ?>
                                <div class="empty-state">
                                    <p>No jobs posted by this recruiter yet.</p>
                                </div>
                            <?php else: ?>
                                <div class="job-list">
                                    <?php foreach($recruiterJobs as $job): ?>
                                        <div class="job-item">
                                            <div>
                                                <div class="job-title"><?php echo htmlspecialchars($job['title']); ?></div>
                                                <div class="job-meta">
                                                    <?php echo htmlspecialchars($job['category_name'] ?? 'N/A'); ?> | 
                                                    <?php echo ucfirst($job['job_type']); ?> | 
                                                    <?php echo $job['application_count']; ?> apps
                                                </div>
                                            </div>
                                            <span class="status-badge status-<?php echo $job['status']; ?>"><?php echo ucfirst($job['status']); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>