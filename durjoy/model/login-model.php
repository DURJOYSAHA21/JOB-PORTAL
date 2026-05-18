<?php
require_once __DIR__ . '/../db.php';

function login($email) {
    $conn = connect();
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'employer'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $user;
}