<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { 
    header("Location: ../recruiter-login-view.php"); exit(); 
}

require_once "../../../model/recruiter/recruiter-outreach-model.php";
require_once "../../../model/recruiter/recruiter-client-model.php";
$recruiter_user_id = (int)$_SESSION['user']['id'];
$recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
$outreachMessages = getRecruiterOutreach($recruiterProfileId);
$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Outreach Messages - HireHub</title>
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
                <h1>Outreach Messages</h1>
                <a href="send-outreach-view.php" class="btn btn-primary">+ New Outreach</a>
            </div>

            <?php if($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

            <?php if(empty($outreachMessages)): ?>
                <div class="empty-state"><p>No outreach messages sent yet.</p></div>
            <?php else: ?>
                <div class="table-card">
                    <table>
                        <thead>
                            <tr>
                                <th>Seeker</th>
                                <th>Job</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Sent Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($outreachMessages as $msg): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($msg['seeker_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($msg['job_title']); ?></td>
                                    <td><div class="msg-preview"><?php echo htmlspecialchars(substr($msg['message'], 0, 80)); ?>...</div></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $msg['status']; ?>">
                                            <?php echo ucfirst($msg['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($msg['sent_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>