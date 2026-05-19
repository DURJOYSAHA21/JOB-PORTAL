<?php
$job = $viewData['job'] ?? null;
$application = $viewData['application'] ?? null;
$successMsg = $viewData['success'] ?? null;
$errorMsg = $viewData['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - Job Portal</title>
    <link rel="stylesheet" href="../View/css/job_details.css">
</head>
<body>
    <div class="container">
        
        <a href="../Controller/browse_jobs_controller.php" class="back-link">← Back to Jobs</a>
        
        <!-- Messages -->
        <?php if ($successMsg): ?>
            <div class="success-message"><?php echo htmlspecialchars($successMsg); ?></div>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <div class="error-alert"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>
        
        <div class="job-detail-card">
            
            <!-- Header -->
            <div class="job-header">
                <h1><?php echo htmlspecialchars($job['title']); ?></h1>
                <div class="company-name"><?php echo htmlspecialchars($job['company_name'] ?? $job['employer_name']); ?></div>
                
                <div class="job-meta">
                    <span class="meta-tag">📍 <?php echo htmlspecialchars($job['location']); ?></span>
                    <span class="meta-tag">💼 <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?></span>
                    <span class="meta-tag">🎯 <?php echo ucfirst($job['experience_level']); ?> Level</span>
                    <span class="meta-tag">📁 <?php echo htmlspecialchars($job['category_name'] ?? 'N/A'); ?></span>
                </div>
                
                <div class="salary">💰 Salary: <?php echo formatSalary($job['salary_min'], $job['salary_max']); ?></div>
                <div class="deadline">📅 Deadline: <?php echo date('F j, Y', strtotime($job['deadline'])); ?></div>
                <div class="posted-info">Posted: <?php echo timeAgo($job['created_at']); ?></div>
                <div class="save-btn-container">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php 
                    require_once(__DIR__ . "/../Model/job_model.php");
                    $saved = isJobSaved($job['id'], $_SESSION['user_id']); 
                    ?>
                    <?php if ($saved): ?>
                        <a href="../Controller/saved_jobs_controller.php?job_id=<?php echo $job['id']; ?>&action=unsave" class="btn-save saved">❤️ Saved</a>
                    <?php else: ?>
                        <a href="../Controller/saved_jobs_controller.php?job_id=<?php echo $job['id']; ?>&action=save" class="btn-save">🤍 Save Job</a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="../Controller/complaint_controller.php?action=submit&job_id=<?php echo $job['id']; ?>" class="btn-complaint">🚨 Report</a>
            </div>
            
            <!-- Description -->
            <div class="job-section">
                <h2>Description</h2>
                <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            </div>
            
            <!-- Requirements -->
            <?php if (!empty($job['requirements'])): ?>
            <div class="job-section">
                <h2>Requirements</h2>
                <p><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Benefits -->
            <?php if (!empty($job['benefits'])): ?>
            <div class="job-section">
                <h2>Benefits</h2>
                <p><?php echo nl2br(htmlspecialchars($job['benefits'])); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Company Info -->
            <div class="job-section">
                <h2>About Company</h2>
                <p><strong><?php echo htmlspecialchars($job['company_name'] ?? $job['employer_name']); ?></strong></p>
                <?php if (!empty($job['company_description'])): ?>
                    <p><?php echo nl2br(htmlspecialchars($job['company_description'])); ?></p>
                <?php endif; ?>
                <?php if (!empty($job['company_website'])): ?>
                    <p>🌐 <a href="<?php echo htmlspecialchars($job['company_website']); ?>" target="_blank"><?php echo htmlspecialchars($job['company_website']); ?></a></p>
                <?php endif; ?>
            </div>
            
<!-- Apply / Withdraw Section -->
<div class="apply-section">
    <?php if (isset($_SESSION['user_id'])): ?>
        
        <?php if ($application): ?>
            
            <?php if ($application['status'] === 'submitted'): ?>
                <!-- Pending application - can withdraw -->
                <div class="application-status status-submitted">
                    ✅ Application Submitted
                </div>
                <a href="../Controller/withdraw_application_controller.php?job_id=<?php echo $job['id']; ?>" 
                   class="btn-withdraw" 
                   onclick="return confirm('Are you sure you want to withdraw this application?')">
                    ↩ Withdraw Application
                </a>
                
            <?php elseif ($application['status'] === 'withdrawn'): ?>
                <!-- Withdrawn - can apply again -->
                <div class="application-status status-withdrawn">
                    ↩ Application Withdrawn
                </div>
                <a href="../Controller/apply_job_controller.php?id=<?php echo $job['id']; ?>" class="btn-apply">Apply Again</a>
                
            <?php elseif ($application['status'] === 'rejected'): ?>
                <!-- Rejected - can apply again -->
                <div class="application-status status-withdrawn">
                    ❌ Application Rejected
                </div>
                <a href="../Controller/apply_job_controller.php?id=<?php echo $job['id']; ?>" class="btn-apply">Apply Again</a>
                
            <?php else: ?>
                <!-- Reviewed, Shortlisted, Interview - cannot re-apply -->
                <div class="application-status status-reviewed">
                    📋 Can't apply anymore-Application <?php echo ucfirst($application['status']); ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- Never applied -->
            <a href="../Controller/apply_job_controller.php?id=<?php echo $job['id']; ?>" class="btn-apply">Apply Now</a>
        <?php endif; ?>
        
    <?php else: ?>
        <a href="../View/login_view.php" class="btn-apply">Login to Apply</a>
    <?php endif; ?>
</div>
            
        </div>
        
    </div>
</body>
</html>