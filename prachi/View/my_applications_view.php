<?php
$applications = $viewData['applications'] ?? [];
$successMsg = $viewData['success'] ?? null;
$errorMsg = $viewData['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications - Job Portal</title>
    <link rel="stylesheet" href="../View/css/my_applications.css">
</head>
<body>
    <div class="container">
        
        <a href="../Controller/browse_jobs_controller.php" class="back-link">← Back to Jobs</a>
        
        <h1>My Applications</h1>
        
        <?php if ($successMsg): ?>
            <div class="success-message"><?php echo htmlspecialchars($successMsg); ?></div>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <div class="error-alert"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>
        
        <?php if (empty($applications)): ?>
            <div class="empty-state">
                <p>📋 No applications yet.</p>
                <a href="../Controller/browse_jobs_controller.php" class="btn btn-primary">Browse Jobs</a>
            </div>
        <?php else: ?>
            <div class="application-list">
                <?php foreach ($applications as $app): ?>
                    <?php $badge = getStatusBadge($app['status']); ?>
                    <div class="application-card">
                        <div class="app-header">
                            <h3>
                                <a href="../Controller/job_details_controller.php?id=<?php echo $app['job_id']; ?>">
                                    <?php echo htmlspecialchars($app['job_title']); ?>
                                </a>
                            </h3>
                            <span class="status-badge <?php echo $badge['class']; ?>">
                                <?php echo $badge['label']; ?>
                            </span>
                        </div>
                        
                        <div class="app-company">
                            🏢 <?php echo htmlspecialchars($app['company_name'] ?? $app['employer_name']); ?>
                        </div>
                        
                        <div class="app-meta">
                            <span>📍 <?php echo htmlspecialchars($app['job_location']); ?></span>
                            <span>💼 <?php echo ucfirst(str_replace('-', ' ', $app['job_type'])); ?></span>
                            <span>💰 <?php echo formatSalary($app['salary_min'], $app['salary_max']); ?></span>
                        </div>
                        
                        <div class="app-footer">
                            <span class="app-date">Applied: <?php echo date('M j, Y', strtotime($app['applied_at'])); ?></span>
                            
                            <div class="app-actions">
                                <a href="../Controller/job_details_controller.php?id=<?php echo $app['job_id']; ?>" class="btn btn-sm">View Job</a>
                                
                                <?php if ($app['status'] === 'submitted'): ?>
                                    <a href="../Controller/withdraw_application_controller.php?job_id=<?php echo $app['job_id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Withdraw this application?')">Withdraw</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</body>
</html>