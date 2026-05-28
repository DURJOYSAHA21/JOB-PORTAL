<?php
require_once __DIR__ . '/../../db.php';

function getRecruiterClients($recruiter_user_id) {
    $conn = connect();
    $recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
    $sql = "SELECT rc.*, 
            COALESCE(rc.company_name_override, ep.company_name) as company_name,
            CASE WHEN rc.employer_id IS NOT NULL THEN 1 ELSE 0 END as is_registered,
            (SELECT COUNT(*) FROM jobs WHERE recruiter_id = ? AND employer_id = rc.employer_id) as jobs_posted
            FROM recruiter_clients rc
            LEFT JOIN employer_profiles ep ON rc.employer_id = ep.id
            WHERE rc.recruiter_id = ?
            ORDER BY rc.added_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $recruiterProfileId, $recruiterProfileId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getRecruiterProfileId($user_id) {
    $conn = connect();
    $stmt = $conn->prepare("SELECT id FROM recruiter_profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ? (int)$row['id'] : null;
}

function addRecruiterClient($recruiter_user_id, $employer_id, $company_name_override) {
    $conn = connect();
    $recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
    $sql = "INSERT INTO recruiter_clients (recruiter_id, employer_id, company_name_override, added_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $eid = $employer_id ? (int)$employer_id : null;
    $stmt->bind_param("iis", $recruiterProfileId, $eid, $company_name_override);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function getAvailableEmployers() {
    $conn = connect();
    $sql = "SELECT ep.id, ep.company_name 
            FROM employer_profiles ep 
            JOIN users u ON ep.user_id = u.id 
            WHERE u.is_verified = 1 AND u.is_active = 1 
            ORDER BY ep.company_name ASC";
    $result = $conn->query($sql);
    $employers = [];
    while($row = $result->fetch_assoc()) {
        $employers[] = $row;
    }
    $conn->close();
    return $employers;
}
function getClientById($client_id) {
    $conn = connect();
    $sql = "SELECT * FROM recruiter_clients WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
function getClientEmployerUserId($client_id) {
    $conn = connect();
    $sql = "SELECT ep.user_id FROM recruiter_clients rc 
            JOIN employer_profiles ep ON rc.employer_id = ep.id 
            WHERE rc.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $conn->close();
    return $result ? (int)$result['user_id'] : null;
}