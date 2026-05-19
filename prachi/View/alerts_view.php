<?php
$alerts = $viewData['alerts'] ?? [];
$categories = $viewData['categories'] ?? [];
$notifications = $viewData['notifications'] ?? [];
$unreadCount = $viewData['unreadCount'] ?? 0;
$successMsg = $viewData['success'] ?? null;
$errorMsg = $viewData['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Alerts - Job Portal</title>
    <link rel="stylesheet" href="../View/css/alerts.css">
</head>
<body>
    <div class="container">
        
        <a href="../Controller/seeker_dashboard_controller.php" class="back-link">← Back to Dashboard</a>
        
        <h1>🔔 Job Alerts</h1>
        
        <?php if ($successMsg): ?>
            <div class="success-message"><?php echo htmlspecialchars($successMsg); ?></div>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <div class="error-alert"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>
        
        <!-- Notifications -->
        <div class="section">
            <div class="section-header">
                <h2>Notifications <?php if ($unreadCount > 0): ?><span class="badge"><?php echo $unreadCount; ?></span><?php endif; ?></h2>
                <?php if ($unreadCount > 0): ?>
                    <a href="../Controller/notification_controller.php?action=mark_all_read" class="btn-text">Mark all read</a>
                <?php endif; ?>
            </div>
            
            <?php if (empty($notifications)): ?>
                <p class="empty-text">No notifications yet</p>
            <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                    <div class="notif-item <?php echo $notif['is_read'] ? '' : 'unread'; ?>">
                        <div class="notif-content">
                            <p><?php echo htmlspecialchars($notif['message']); ?></p>
                            <span class="notif-time"><?php echo date('M j, Y g:i A', strtotime($notif['created_at'])); ?></span>
                        </div>
                        <div class="notif-actions">
                            <?php if ($notif['link']): ?>
                                <a href="../Controller/notification_controller.php?action=mark_read&id=<?php echo $notif['id']; ?>&redirect=<?php echo urlencode($notif['link']); ?>">View</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Create Alert -->
        <div class="section">
            <h2>Create New Alert</h2>
            <form method="post" class="alert-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Keyword</label>
                        <input type="text" name="keyword" placeholder="e.g., PHP, Laravel">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id">
                            <option value="">Any Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" placeholder="e.g., Dhaka, Remote">
                    </div>
                    <div class="form-group">
                        <label>Job Type</label>
                        <select name="job_type">
                            <option value="">Any Type</option>
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="remote">Remote</option>
                            <option value="contract">Contract</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create Alert</button>
            </form>
        </div>
        
        <!-- Active Alerts -->
        <div class="section">
            <h2>Active Alerts</h2>
            <?php if (empty($alerts)): ?>
                <p class="empty-text">No alerts set up yet</p>
            <?php else: ?>
                <?php foreach ($alerts as $alert): ?>
                    <div class="alert-card">
                        <div class="alert-info">
                            <?php if ($alert['keyword']): ?>
                                <span class="tag">🔑 <?php echo htmlspecialchars($alert['keyword']); ?></span>
                            <?php endif; ?>
                            <?php if ($alert['category_name']): ?>
                                <span class="tag">📁 <?php echo htmlspecialchars($alert['category_name']); ?></span>
                            <?php endif; ?>
                            <?php if ($alert['location']): ?>
                                <span class="tag">📍 <?php echo htmlspecialchars($alert['location']); ?></span>
                            <?php endif; ?>
                            <?php if ($alert['job_type']): ?>
                                <span class="tag">💼 <?php echo ucfirst(str_replace('-', ' ', $alert['job_type'])); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="../Controller/alerts_controller.php?action=delete&id=<?php echo $alert['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Delete this alert?')">🗑 Delete</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
    </div>
</body>
</html>