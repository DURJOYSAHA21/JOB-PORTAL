<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { header("Location: ../recruiter-login-view.php"); exit(); }

require_once "../../../model/job-model.php";
require_once "../../../model/recruiter/recruiter-client-model.php";

$recruiter_user_id = (int)$_SESSION['user']['id'];
$recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
$jobs = getRecruiterJobs($recruiterProfileId);
$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Jobs - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/manage-jobs.css">
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
                <a href="manage-jobs-view.php" class="active">Manage Jobs</a>
                <a href="all-jobs-view.php">All Jobs</a>
                <a href="../candidate/candidate-search-view.php">Search Candidates</a>
                <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <h1>Manage Jobs</h1>
                <a href="post-job-view.php" class="btn btn-primary">+ Post New Job</a>
            </div>
            <?php if($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

            <?php if(empty($jobs)): ?>
                <div class="empty-state"><p>No jobs posted yet.</p></div>
            <?php else: ?>
                <table>
                    <thead><tr><th>Title</th><th>Client</th><th>Status</th><th>Applications</th><th>Deadline</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach($jobs as $job): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($job['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($job['client_name'] ?? 'N/A'); ?></td>
                                <td><span class="status-badge status-<?php echo $job['status']; ?>"><?php echo ucfirst($job['status']); ?></span></td>
                                <td><?php echo $job['application_count']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($job['deadline'])); ?></td>
                                <td>
                                    <a href="edit-job-view.php?job_id=<?php echo $job['id']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="../../../controller/recruiter/recruiter-job-delete-controller.php?job_id=<?php echo $job['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>