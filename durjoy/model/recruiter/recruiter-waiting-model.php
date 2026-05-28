<?php
require_once __DIR__ . '/../../db.php';
function getRecruiterVerificationStatus($user_id) {
    $conn = connect();
    $stmt = $conn->prepare("SELECT is_verified, is_active FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}