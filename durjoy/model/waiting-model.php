<?php
require_once __DIR__ . '/../db.php';

function getVerificationStatus($user_id) {
    $conn = connect();
    $stmt = $conn->prepare("SELECT is_verified, is_active FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $conn->close();
    return $row;
}