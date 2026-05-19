<?php
$savedJobs = $viewData['savedJobs'] ?? [];
$successMsg = $viewData['success'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs - Job Portal</title>
    <link rel="stylesheet" href="../View/css/saved_jobs.css">
</head>
<body>
    <div class="container">
        
        <a href="../Controller/seeker_dashboard_controller.php" class="back-link">← Back to Dashboard</a>
        
        <h1>📑 Saved Jobs</h1>
        
        <?php if ($successMsg): ?>
            <div class="success-message"><?php echo htmlspecialchars($successMsg); ?></div>
        <?php endif; ?>
        
        <?php if (empty($savedJobs)): ?>
            <div class="empty-state">
                <p>📌 No saved jobs yet.</p>
                <a href="../Controller/browse_jobs_controller.php" class="btn btn-primary">Browse Jobs</a>
            </div>
        <?php else: ?>
            <div class="job-list">
                <?php foreach ($savedJobs as $job): ?>
                    <div class="job-card">
                        <div class="job-header">
                            <h3>
                                <a href="../Controller/job_details_controller.php?id=<?php echo $job['id']; ?>">
                                    <?php echo htmlspecialchars($job['title']); ?>
                                </a>
                            </h3>
                            <span class="saved-date">Saved: <?php echo date('M j, Y', strtotime($job['saved_at'])); ?></span>
                        </div>
                        
                        <div class="job-company">
                            🏢 <?php echo htmlspecialchars($job['company_name'] ?? $job['employer_name']); ?>
                        </div>
                        
                        <div class="job-meta">
                            <span>📍 <?php echo htmlspecialchars($job['location']); ?></span>
                            <span>💼 <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?></span>
                            <span>💰 <?php echo formatSalary($job['salary_min'], $job['salary_max']); ?></span>
                        </div>
                        
                        <div class="job-actions">
                            <a href="../Controller/job_details_controller.php?id=<?php echo $job['id']; ?>" class="btn btn-sm">View</a>
                            <a href="../Controller/saved_jobs_controller.php?job_id=<?php echo $job['id']; ?>&action=unsave" class="btn btn-sm btn-unsave">Unsave</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</body>
</html>