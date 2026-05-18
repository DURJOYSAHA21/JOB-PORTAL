<?php
require_once __DIR__ . '/../db.php';

function registerUser($fullname, $email, $phone, $hashedPassword) {
    $conn = connect();
    $role = 'employer';
    $sql = "INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fullname, $email, $phone, $hashedPassword, $role);

    if (!$stmt->execute()) {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        throw new RuntimeException("Registration failed: " . $error);
    }

    $insertId = $conn->insert_id;
    $stmt->close();
    $conn->close();
    return $insertId;
}

function emailExists($email) {
    $conn = connect();
    $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    return $count > 0;
}