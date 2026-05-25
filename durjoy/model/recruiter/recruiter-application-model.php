<?php
require_once __DIR__ . '/../../db.php';

function getRecruiterApplications($recruiterProfileId) {
    $conn = connect();
    $sql = "SELECT a.*, j.title as job_title,
            u.name as applicant_name,
            COALESCE(rc.company_name_override, ep.company_name) as client_name
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            LEFT JOIN recruiter_clients rc ON j.recruiter_id = rc.recruiter_id AND j.employer_id = rc.employer_id
            LEFT JOIN employer_profiles ep ON j.employer_id = ep.id
            WHERE j.recruiter_id = ?
            ORDER BY a.applied_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recruiterProfileId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function filterRecruiterApplications($recruiterProfileId, $filters) {
    $conn = connect();
    $sql = "SELECT a.*, j.title as job_title,
            u.name as applicant_name,
            COALESCE(rc.company_name_override, ep.company_name) as client_name
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            LEFT JOIN recruiter_clients rc ON j.recruiter_id = rc.recruiter_id AND j.employer_id = rc.employer_id
            LEFT JOIN employer_profiles ep ON j.employer_id = ep.id
            WHERE j.recruiter_id = ?";
    $params = [$recruiterProfileId];
    $types = "i";

    if(!empty($filters['job_id'])) { $sql .= " AND a.job_id = ?"; $params[] = (int)$filters['job_id']; $types .= "i"; }
    if(!empty($filters['status'])) { $sql .= " AND a.status = ?"; $params[] = $filters['status']; $types .= "s"; }

    $sql .= " ORDER BY a.applied_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function updateRecruiterApplicationStatus($application_id, $new_status) {
    $conn = connect();
    $sql = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $application_id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result ? $new_status : false;
}