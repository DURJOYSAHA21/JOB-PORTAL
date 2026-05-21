<?php
require_once __DIR__ . '/../../db.php';
function recruiterLogin($email) {
    $conn = connect();
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'recruiter'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}