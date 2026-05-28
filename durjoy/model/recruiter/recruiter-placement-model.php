<?php
require_once __DIR__ . '/../../db.php';

function getPlacementHistory($recruiterProfileId) {
    $conn = connect();
    $sql = "SELECT a.id, a.status, a.applied_at,
            j.title as job_title,
            u.name as candidate_name, u.email as candidate_email,
            COALESCE(rc.company_name_override, ep.company_name) as client_name,
            a.applied_at as hired_date
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            LEFT JOIN recruiter_clients rc ON j.recruiter_id = rc.recruiter_id AND j.employer_id = rc.employer_id
            LEFT JOIN employer_profiles ep ON j.employer_id = ep.id
            WHERE j.recruiter_id = ? AND a.status IN ('hired', 'placed')
            ORDER BY a.applied_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recruiterProfileId);
    $stmt->execute();
    $placements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $placements;
}