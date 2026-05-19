<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/complaint-model.php";

$user_id = (int)$_SESSION['user']['id'];
$complaints = getEmployerComplaints($user_id);
$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Complaints - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/complaints.css">
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
                <a href="recruiter-relationships-view.php">Recruiters</a>
                <a href="my-complaints-view.php" class="active">Complaints</a>
                <a href="../../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>My Complaints</h1>
                <a href="submit-complaint-view.php" class="btn btn-primary">+ New Complaint</a>
            </div>

            <?php if($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h2>All Complaints (<?php echo count($complaints); ?>)</h2>
                </div>

                <?php if(empty($complaints)): ?>
                    <div class="empty-state">
                        <p>No complaints submitted yet.</p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Subject</th>
                                <th>Role</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Admin Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($complaints as $complaint): ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($complaint['created_at'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($complaint['subject_name']); ?></strong></td>
                                    <td><?php echo ucfirst($complaint['subject_role']); ?></td>
                                    <td><div class="complaint-desc" title="<?php echo htmlspecialchars($complaint['description']); ?>"><?php echo htmlspecialchars(substr($complaint['description'], 0, 50)); ?>...</div></td>
                                    <td><span class="status-badge status-<?php echo $complaint['status']; ?>"><?php echo ucfirst($complaint['status']); ?></span></td>
                                    <td><?php echo !empty($complaint['admin_note']) ? htmlspecialchars($complaint['admin_note']) : '--'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>