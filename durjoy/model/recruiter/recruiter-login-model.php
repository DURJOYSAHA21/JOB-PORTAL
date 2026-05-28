<?php
require_once __DIR__ . '/../../db.php';

function recruiterLogin($email) {
    $conn = connect();
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'recruiter'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $user;
}