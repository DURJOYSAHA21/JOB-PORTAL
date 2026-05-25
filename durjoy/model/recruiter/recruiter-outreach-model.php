<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/recruiter-client-model.php';

function sendOutreach($recruiter_profile_id, $seeker_user_id, $job_id, $message) {
    $conn = connect();
    $sql = "INSERT INTO recruiter_outreach (recruiter_id, seeker_id, job_id, message, status, sent_at) 
            VALUES (?, ?, ?, ?, 'sent', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $recruiter_profile_id, $seeker_user_id, $job_id, $message);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function getRecruiterOutreach($recruiter_profile_id) {
    $conn = connect();
    $sql = "SELECT ro.*, 
            u.name as seeker_name,
            j.title as job_title
            FROM recruiter_outreach ro
            JOIN users u ON ro.seeker_id = u.id
            JOIN jobs j ON ro.job_id = j.id
            WHERE ro.recruiter_id = ?
            ORDER BY ro.sent_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recruiter_profile_id);
    $stmt->execute();
    $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $messages;
}