<?php
require_once("../db.php");

/**
 * Create a job alert
 */
function createAlert($seekerId, $keyword, $categoryId, $location, $jobType) {
    global $conn;
    $keyword = mysqli_real_escape_string($conn, trim($keyword));
    $location = mysqli_real_escape_string($conn, trim($location));
    $jobType = mysqli_real_escape_string($conn, trim($jobType));
    $categoryId = !empty($categoryId) ? (int)$categoryId : 'NULL';
    
    $sql = "INSERT INTO job_alerts (seeker_id, keyword, category_id, location, job_type, created_at)
            VALUES ($seekerId, '$keyword', $categoryId, '$location', '$jobType', NOW())";
    return mysqli_query($conn, $sql);
}

/**
 * Get all alerts for a seeker
 */
function getAlertsBySeeker($seekerId) {
    global $conn;
    $seekerId = (int)$seekerId;
    
    $sql = "SELECT a.*, c.name AS category_name
            FROM job_alerts a
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.seeker_id = $seekerId
            ORDER BY a.created_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $alerts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $alerts[] = $row;
    }
    return $alerts;
}

/**
 * Delete an alert
 */
function deleteAlert($alertId, $seekerId) {
    global $conn;
    $alertId = (int)$alertId;
    $seekerId = (int)$seekerId;
    
    $sql = "DELETE FROM job_alerts WHERE id = $alertId AND seeker_id = $seekerId";
    mysqli_query($conn, $sql);
    return mysqli_affected_rows($conn) > 0;
}

/**
 * Match new job against all active alerts and create notifications
 */
function matchJobToAlerts($jobId) {
    global $conn;
    $jobId = (int)$jobId;
    
    // Get the new job
    $sql = "SELECT * FROM jobs WHERE id = $jobId";
    $result = mysqli_query($conn, $sql);
    $job = mysqli_fetch_assoc($result);
    if (!$job) return;
    
    // Find matching alerts
    $sql = "SELECT * FROM job_alerts WHERE 1=1";
    
    $alerts = mysqli_query($conn, $sql);
    
    while ($alert = mysqli_fetch_assoc($alerts)) {
        $match = true;
        
        // Check keyword
        if (!empty($alert['keyword'])) {
            $kw = strtolower($alert['keyword']);
            $title = strtolower($job['title']);
            $desc = strtolower($job['description']);
            if (strpos($title, $kw) === false && strpos($desc, $kw) === false) {
                $match = false;
            }
        }
        
        // Check category
        if (!empty($alert['category_id']) && $alert['category_id'] != $job['category_id']) {
            $match = false;
        }
        
        // Check location
        if (!empty($alert['location']) && stripos($job['location'], $alert['location']) === false) {
            $match = false;
        }
        
        // Check job type
        if (!empty($alert['job_type']) && $alert['job_type'] != $job['job_type']) {
            $match = false;
        }
        
        if ($match) {
            $link = "../Controller/job_details_controller.php?id=" . $jobId;
            $message = "New job matching your alert: " . mysqli_real_escape_string($conn, $job['title']);
            $sql = "INSERT INTO notifications (user_id, message, link, created_at)
                    VALUES ({$alert['seeker_id']}, '$message', '$link', NOW())";
            mysqli_query($conn, $sql);
        }
    }
}

/**
 * Get notifications for a user
 */
function getNotifications($userId, $limit = 20) {
    global $conn;
    $userId = (int)$userId;
    
    $sql = "SELECT * FROM notifications WHERE user_id = $userId ORDER BY created_at DESC LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $notifications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
    return $notifications;
}

/**
 * Count unread notifications
 */
function countUnreadNotifications($userId) {
    global $conn;
    $userId = (int)$userId;
    
    $sql = "SELECT COUNT(*) AS count FROM notifications WHERE user_id = $userId AND is_read = 0";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

/**
 * Mark notification as read
 */
function markNotificationRead($notifId, $userId) {
    global $conn;
    $notifId = (int)$notifId;
    $userId = (int)$userId;
    
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = $notifId AND user_id = $userId";
    return mysqli_query($conn, $sql);
}

/**
 * Mark all notifications as read
 */
function markAllNotificationsRead($userId) {
    global $conn;
    $userId = (int)$userId;
    
    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = $userId AND is_read = 0";
    return mysqli_query($conn, $sql);
}