<?php
require_once __DIR__ . '/../../db.php';

function getRecruiterProfile($user_id) {
    $conn = connect();
    $sql = "SELECT * FROM recruiter_profiles WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateRecruiterProfile($user_id, $agencyname, $specialization, $description, $website) {
    $conn = connect();
    $existing = getRecruiterProfile($user_id);
    if($existing) {
        $sql = "UPDATE recruiter_profiles SET agency_name=?, specialization=?, description=?, website=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $agencyname, $specialization, $description, $website, $user_id);
    } else {
        $sql = "INSERT INTO recruiter_profiles (user_id, agency_name, specialization, description, website) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $agencyname, $specialization, $description, $website);
    }
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}