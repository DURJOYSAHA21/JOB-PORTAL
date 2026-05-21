<?php
require_once __DIR__ . '/../../db.php';

function addRecruiterInfo($user_id, $agencyname, $specialization, $description, $website) {
    $conn = connect();
    $sql = "INSERT INTO recruiter_profiles (user_id, agency_name, specialization, description, website) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $agencyname, $specialization, $description, $website);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function recruiterInfoVerify($user_id) {
    $conn = connect();
    $sql = "SELECT * FROM recruiter_profiles JOIN users ON recruiter_profiles.user_id = users.id WHERE users.id = ? AND users.is_verified = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}