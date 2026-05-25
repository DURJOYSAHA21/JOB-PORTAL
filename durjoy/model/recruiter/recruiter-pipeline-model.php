<?php
require_once __DIR__ . '/../../db.php';

function getCandidatePipeline($recruiterProfileId) {
    $conn = connect();
    $sql = "SELECT a.id, a.status, a.applied_at,
            j.title as job_title,
            u.name as candidate_name, u.email as candidate_email,
            sp.years_experience,
            COALESCE(rc.company_name_override, ep.company_name) as client_name
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            LEFT JOIN recruiter_clients rc ON j.recruiter_id = rc.recruiter_id AND j.employer_id = rc.employer_id
            LEFT JOIN employer_profiles ep ON j.employer_id = ep.id
            WHERE j.recruiter_id = ?
            ORDER BY FIELD(a.status, 'interview', 'shortlisted', 'reviewed', 'submitted', 'rejected'), a.applied_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recruiterProfileId);
    $stmt->execute();
    $pipeline = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $pipeline;
}