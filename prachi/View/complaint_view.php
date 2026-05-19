<?php
$page = $viewData['page'] ?? 'list';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints - Job Portal</title>
    <link rel="stylesheet" href="../View/css/complaint.css">
</head>
<body>
    <div class="container">
        
        <a href="../Controller/seeker_dashboard_controller.php" class="back-link">← Back to Dashboard</a>
        
        <?php if ($page === 'submit'): ?>
            
            <?php $job = $viewData['job']; ?>
            <a href="../Controller/job_details_controller.php?id=<?php echo $job['id']; ?>" class="back-link">← Back to Job</a>
            
            <div class="card">
                <h1>🚨 Submit Complaint</h1>
                
                <div class="job-info">
                    <p><strong>Job:</strong> <?php echo htmlspecialchars($job['title']); ?></p>
                    <p><strong>Posted by:</strong> <?php echo htmlspecialchars($job['company_name'] ?? $job['employer_name']); ?></p>
                </div>
                
                <?php if (isset($viewData['errors']['complaint'])): ?>
                    <div class="error-alert"><?php echo htmlspecialchars($viewData['errors']['complaint']); ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="form-group">
                        <label>Describe your complaint <span class="required">*</span></label>
                        <textarea name="description" placeholder="Describe the issue (minimum 20 characters)..." required><?php echo htmlspecialchars($viewData['oldInput']['description'] ?? ''); ?></textarea>
                        <span class="input-hint">e.g., Misleading job description, fake posting, inappropriate behavior, salary mismatch</span>
                    </div>
                    
                    <div class="btn-row">
                        <a href="../Controller/job_details_controller.php?id=<?php echo $job['id']; ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit Complaint</button>
                    </div>
                </form>
            </div>
            
        <?php else: ?>
            
            <h1>📝 My Complaints</h1>
            
            <?php $complaints = $viewData['complaints'] ?? []; ?>
            
            <?php if (empty($complaints)): ?>
                <div class="empty-state"><p>No complaints submitted yet.</p></div>
            <?php else: ?>
                <?php foreach ($complaints as $complaint): ?>
                    <div class="complaint-card">
                        <div class="complaint-header">
                            <span class="complaint-status status-<?php echo $complaint['status']; ?>"><?php echo ucfirst($complaint['status']); ?></span>
                            <span class="complaint-date"><?php echo date('M j, Y', strtotime($complaint['created_at'])); ?></span>
                        </div>
                        <p class="complaint-about"><strong>About:</strong> <?php echo htmlspecialchars($complaint['subject_name']); ?> (<?php echo htmlspecialchars($complaint['subject_email']); ?>)</p>
                        <p class="complaint-text"><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></p>
                        <?php if ($complaint['admin_note']): ?>
                            <div class="admin-note"><strong>Admin Response:</strong> <?php echo nl2br(htmlspecialchars($complaint['admin_note'])); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        <?php endif; ?>
        
    </div>
</body>
</html>