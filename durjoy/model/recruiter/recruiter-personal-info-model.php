<?php
require_once __DIR__ . '/../../db.php';

function registerRecruiter($fullname, $email, $phone, $hashedPassword) {
    $conn = connect();
    $role = 'recruiter';
    $sql = "INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fullname, $email, $phone, $hashedPassword, $role);
    if(!$stmt->execute()) { $stmt->close(); $conn->close(); return false; }
    $insertId = $conn->insert_id;
    $stmt->close();
    $conn->close();
    return $insertId;
}