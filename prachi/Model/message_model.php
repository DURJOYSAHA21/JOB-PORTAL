<?php
require_once("../db.php");

/**
 * Get all conversations for a user (latest message per conversation)
 */
function getConversations($userId) {
    global $conn;
    $userId = (int)$userId;
    
    $sql = "SELECT 
                CASE WHEN m.sender_id = $userId THEN m.recipient_id ELSE m.sender_id END AS other_user_id,
                u.name AS other_user_name,
                u.role AS other_user_role,
                ep.company_name,
                rp.agency_name,
                MAX(m.body) AS last_message,
                MAX(m.sent_at) AS last_time,
                SUM(CASE WHEN m.recipient_id = $userId AND m.is_read = 0 THEN 1 ELSE 0 END) AS unread_count
            FROM messages m
            JOIN users u ON (CASE WHEN m.sender_id = $userId THEN m.recipient_id ELSE m.sender_id END) = u.id
            LEFT JOIN employer_profiles ep ON u.id = ep.user_id
            LEFT JOIN recruiter_profiles rp ON u.id = rp.user_id
            WHERE m.sender_id = $userId OR m.recipient_id = $userId
            GROUP BY other_user_id
            ORDER BY last_time DESC";
    
    $result = mysqli_query($conn, $sql);
    $conversations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $conversations[] = $row;
    }
    return $conversations;
}

/**
 * Get messages between two users
 */
function getMessages($userId, $otherUserId, $limit = 50) {
    global $conn;
    $userId = (int)$userId;
    $otherUserId = (int)$otherUserId;
    
    $sql = "SELECT m.*, u.name AS sender_name
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE (m.sender_id = $userId AND m.recipient_id = $otherUserId)
               OR (m.sender_id = $otherUserId AND m.recipient_id = $userId)
            ORDER BY m.sent_at ASC
            LIMIT $limit";
    
    $result = mysqli_query($conn, $sql);
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
    return $messages;
}

/**
 * Send a message
 */
function sendMessage($senderId, $recipientId, $body, $applicationId = null) {
    global $conn;
    $senderId = (int)$senderId;
    $recipientId = (int)$recipientId;
    $body = mysqli_real_escape_string($conn, $body);
    $applicationId = $applicationId ? (int)$applicationId : 'NULL';
    
    $sql = "INSERT INTO messages (sender_id, recipient_id, application_id, body, sent_at, is_read)
            VALUES ($senderId, $recipientId, $applicationId, '$body', NOW(), 0)";
    return mysqli_query($conn, $sql);
}

/**
 * Mark messages as read from a specific sender
 */
function markMessagesRead($recipientId, $senderId) {
    global $conn;
    $recipientId = (int)$recipientId;
    $senderId = (int)$senderId;
    
    $sql = "UPDATE messages SET is_read = 1 
            WHERE recipient_id = $recipientId AND sender_id = $senderId AND is_read = 0";
    return mysqli_query($conn, $sql);
}

/**
 * Get recruiter outreach messages for a seeker
 */
function getRecruiterOutreach($seekerId) {
    global $conn;
    $seekerId = (int)$seekerId;
    
    $sql = "SELECT ro.*, u.name AS recruiter_name, rp.agency_name, j.title AS job_title
            FROM recruiter_outreach ro
            JOIN users u ON ro.recruiter_id = u.id
            LEFT JOIN recruiter_profiles rp ON u.id = rp.user_id
            LEFT JOIN jobs j ON ro.job_id = j.id
            WHERE ro.seeker_id = $seekerId
            ORDER BY ro.sent_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $outreach = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $outreach[] = $row;
    }
    return $outreach;
}

/**
 * Update recruiter outreach status
 */
function updateOutreachStatus($outreachId, $status) {
    global $conn;
    $outreachId = (int)$outreachId;
    $status = mysqli_real_escape_string($conn, $status);
    
    $sql = "UPDATE recruiter_outreach SET status = '$status' WHERE id = $outreachId";
    return mysqli_query($conn, $sql);
}

/**
 * Get user by ID (for starting new conversation)
 */
function getUserByIdSimple($userId) {
    global $conn;
    $userId = (int)$userId;
    
    $sql = "SELECT u.*, ep.company_name, rp.agency_name
            FROM users u
            LEFT JOIN employer_profiles ep ON u.id = ep.user_id
            LEFT JOIN recruiter_profiles rp ON u.id = rp.user_id
            WHERE u.id = $userId";
    
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}