<?php
require_once __DIR__ . '/../db.php';

function sendMessage($sender_id, $recipient_id, $application_id, $body) {
    $conn = connect();
    $sql = "INSERT INTO messages (sender_id, recipient_id, application_id, body, sent_at, is_read) VALUES (?, ?, ?, ?, NOW(), 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $sender_id, $recipient_id, $application_id, $body);
    $result = $stmt->execute();
    $insert_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    return $result ? $insert_id : false;
}

function getMessagesByApplication($application_id, $employer_id) {
    $conn = connect();
    $sql = "SELECT m.*, s.name as sender_name, s.role as sender_role, r.name as recipient_name
            FROM messages m
            JOIN users s ON m.sender_id = s.id
            JOIN users r ON m.recipient_id = r.id
            JOIN applications a ON m.application_id = a.id
            JOIN jobs j ON a.job_id = j.id
            WHERE m.application_id = ? AND j.employer_id = ?
            ORDER BY m.sent_at ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $application_id, $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while($row = $result->fetch_assoc()) { $messages[] = $row; }
    $stmt->close();
    $conn->close();
    return $messages;
}

function getSeekerUserIdFromApplication($application_id, $employer_id) {
    $conn = connect();
    $sql = "SELECT u.id as user_id, u.name, u.email
            FROM applications a
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            JOIN jobs j ON a.job_id = j.id
            WHERE a.id = ? AND j.employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $application_id, $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $seeker = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $seeker;
}