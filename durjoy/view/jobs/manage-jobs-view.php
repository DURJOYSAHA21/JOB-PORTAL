<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/job-model.php";
$employer_id = (int)$_SESSION['user']['id'];
$jobs = getEmployerJobs($employer_id);

$success = $_SESSION['success'] ?? null;
$errors = $_SESSION['errors'] ?? null;
unset($_SESSION['success'], $_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Jobs - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/manage-jobs.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
                <a href="../dashboard-view.php">Dashboard</a>
                <a href="../company-profile-view.php">Company Profile</a>
                <a href="post-job-view.php">Post a Job</a>
                <a href="manage-jobs-view.php" class="active">Manage Jobs</a>
                <a href="view-applications-view.php">Applications</a>
                <a href="shortlisted-view.php">Shortlisted</a>
                <a href="../company-analytics-view.php">Analytics</a>
                <a href="recruiter-relationships-view.php">Recruiters</a>
                <a href="submit-complaint-view.php">Complaints</a>
                <a href="../../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Manage Jobs</h1>
                <a href="post-job-view.php" class="btn btn-primary">+ Post New Job</a>
            </div>

            <?php if($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if($errors): ?>
                <div class="alert alert-error">
                    <?php foreach($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if(empty($jobs)): ?>
                <div class="empty-state">
                    <p>No jobs posted yet.</p>
                    <a href="post-job-view.php" class="btn btn-primary">Post Your First Job</a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Applications</th>
                            <th>Deadline</th>
                            <th>Toggle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($jobs as $job): 
                            $days_left = ceil((strtotime($job['deadline']) - time()) / (60 * 60 * 24));
                            $days_class = ($days_left <= 3) ? 'days-urgent' : (($days_left <= 7) ? 'days-warning' : 'days-good');
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($job['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($job['category_name'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $job['status']; ?>"><?php echo ucfirst($job['status']); ?></span>
                                </td>
                                <td><?php echo $job['application_count']; ?></td>
                                <td>
                                    <?php echo date('M d, Y', strtotime($job['deadline'])); ?>
                                    <br>
                                    <span class="days-remaining <?php echo $days_class; ?>"><?php echo ($days_left > 0) ? "$days_left days left" : 'Expired'; ?></span>
                                </td>
                                <td>
                                    <?php if($job['status'] === 'active' || $job['status'] === 'closed'): ?>
                                        <div class="toggle-wrapper">
                                            <label class="toggle-switch">
                                                <input type="checkbox" <?php echo ($job['status'] === 'active') ? 'checked' : ''; ?> onchange="toggleJobStatus(<?php echo $job['id']; ?>, this)">
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label toggle-status-text-<?php echo $job['id']; ?>"><?php echo ucfirst($job['status']); ?></span>
                                        </div>
                                        <span class="toggle-message" id="toggle-msg-<?php echo $job['id']; ?>"></span>
                                    <?php else: ?>
                                        <span style="color: #757575; font-size: 12px;">Publish to toggle</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <a href="edit-job-view.php?job_id=<?php echo $job['id']; ?>" class="btn btn-edit">Edit</a>
                                        <?php if($job['status'] === 'closed'): ?>
                                            <form method="POST" action="../../controller/job-repost-controller.php" style="display:inline;" onsubmit="return confirm('Repost this job?')">
                                                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                                <button type="submit" class="btn btn-repost">Repost</button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="POST" action="../../controller/job-delete-controller.php" style="display:inline;" onsubmit="return confirm('Delete this job?')">
                                            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                            <button type="submit" class="btn btn-delete">Delete</button>
                                        </form>
                                        <a href="job-analytics-view.php?job_id=<?php echo $job['id']; ?>" class="btn btn-analytics">Analytics</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </div>

    <script src="../../controller/api/manage-jobs.js"></script>
</body>
</html>