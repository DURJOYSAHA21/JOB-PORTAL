<?php
$user = $viewData['user'] ?? null;
$profile = $viewData['profile'] ?? null;
$profileComplete = $viewData['profileComplete'] ?? false;
$totalActiveJobs = $viewData['totalActiveJobs'] ?? 0;
$successMessage = $viewData['success'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Job Portal</title>
    <link rel="stylesheet" href="../View/css/seeker_dashboard.css">
</head>
<body>
    <div class="container">
        
        <nav class="top-nav">
            <div class="nav-brand">💼 Job Portal</div>
            <div class="nav-links">
                <a href="../Controller/browse_jobs_controller.php">Browse Jobs</a>
                <a href="../Controller/seeker_profile_controller.php">My Profile</a>
                <a href="../Controller/my_applications_controller.php">My Applications</a>
                <a href="../Controller/saved_jobs_controller.php">Saved Jobs</a>
                <a href="../Controller/logout_controller.php">Logout</a>
            </div>
            <div class="nav-user">👤 <?php echo htmlspecialchars($user['name'] ?? 'User'); ?></div>
        </nav>
        
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($user['name'] ?? 'User'); ?>! 👋</h1>
            <p><?php echo $totalActiveJobs; ?> active jobs available</p>
        </div>
        
        <?php if (!$profileComplete): ?>
            <div class="alert alert-warning">
                <div class="alert-icon">⚠️</div>
                <div class="alert-content">
                    <h3>Complete Your Profile</h3>
                    <p>A complete profile increases your chances of getting hired.</p>
                    <a href="../Controller/seeker_profile_controller.php" class="btn btn-primary">Complete Profile</a>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                <div class="alert-icon">✅</div>
                <div class="alert-content">
                    <h3>Profile Complete</h3>
                    <p>Your profile is ready. Employers can find you!</p>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="actions-grid">
            <a href="../Controller/browse_jobs_controller.php" class="action-card">
                <div class="action-icon">🔍</div>
                <h3>Browse Jobs</h3>
                <p>Search and find jobs matching your skills</p>
            </a>
            <a href="../Controller/seeker_profile_controller.php" class="action-card">
                <div class="action-icon">👤</div>
                <h3>My Profile</h3>
                <p>Update your profile and upload resume</p>
            </a>
            <a href="../Controller/my_applications_controller.php" class="action-card">
                <div class="action-icon">📋</div>
                <h3>My Applications</h3>
                <p>Track your submitted applications</p>
            </a>
            <a href="../Controller/saved_jobs_controller.php" class="action-card">
                <div class="action-icon">📑</div>
                <h3>Saved Jobs</h3>
                <p>View your bookmarked jobs</p>
            </a>
            <a href="../Controller/alerts_controller.php" class="action-card">
                <div class="action-icon">🔔</div>
                <h3>Job Alerts</h3>
                <p>Set up alerts for matching jobs</p>
            </a>
            <a href="../Controller/messages_controller.php" class="action-card">
                <div class="action-icon">💬</div>
                <h3>Messages</h3>
                <p>Chat with employers and recruiters</p>
            </a>
            <a href="../Controller/complaint_controller.php" class="action-card">
                <div class="action-icon">📝</div>
                <h3>My Complaints</h3>
            </a>
        </div>
        
    </div>
</body>
</html>