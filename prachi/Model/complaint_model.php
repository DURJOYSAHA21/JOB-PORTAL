<?php
require_once("../db.php");
/**
 * Get complaints submitted by a user
 */
function getUserComplaints($userId) {
    global $conn;
    $userId = (int)$userId;
    
    $sql = "SELECT c.*, u.name AS subject_name, u.email AS subject_email
            FROM complaints c
            JOIN users u ON c.subject_id = u.id
            WHERE c.submitter_id = $userId
            ORDER BY c.created_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $complaints = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $complaints[] = $row;
    }
    return $complaints;
}
function submitComplaint($submitterId, $subjectId, $description) {
    global $conn;
    $submitterId = (int)$submitterId;
    $subjectId = (int)$subjectId;
    $description = mysqli_real_escape_string($conn, trim($description));
    
    $sql = "INSERT INTO complaints (submitter_id, subject_id, description, status, created_at)
            VALUES ($submitterId, $subjectId, '$description', 'open', NOW())";
    
    $result = mysqli_query($conn, $sql);
    
    // Debug - remove after testing
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
    
    return $result;
}