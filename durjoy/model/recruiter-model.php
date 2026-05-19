<?php
require_once __DIR__ . '/../db.php';

function getEmployerProfileIdByUserId($user_id) {
    $conn = connect();
    $sql = "SELECT id FROM employer_profiles WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $row ? (int)$row['id'] : null;
}

function getEmployerRecruiters($user_id) {
    $employer_profile_id = getEmployerProfileIdByUserId($user_id);
    if(!$employer_profile_id) return [];

    $conn = connect();
    $sql = "SELECT rc.*, u.name as recruiter_name, u.email as recruiter_email, u.phone as recruiter_phone,
            rp.agency_name, rp.specialization, rp.website,
            (SELECT COUNT(*) FROM jobs WHERE employer_id = ? AND recruiter_id = rc.recruiter_id) as jobs_posted,
            (SELECT COUNT(*) FROM jobs WHERE employer_id = ? AND recruiter_id = rc.recruiter_id AND status = 'active') as active_jobs
            FROM recruiter_clients rc
            JOIN recruiter_profiles rp ON rc.recruiter_id = rp.id
            JOIN users u ON rp.user_id = u.id
            WHERE rc.employer_id = ?
            ORDER BY rc.added_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $user_id, $employer_profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recruiters = [];
    while($row = $result->fetch_assoc()) { $recruiters[] = $row; }
    $stmt->close();
    $conn->close();
    return $recruiters;
}

function getRecruiterJobs($user_id, $recruiter_id) {
    $conn = connect();
    $sql = "SELECT j.*, c.name as category_name,
            (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count
            FROM jobs j
            LEFT JOIN categories c ON j.category_id = c.id
            WHERE j.employer_id = ? AND j.recruiter_id = ?
            ORDER BY j.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $recruiter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobs = [];
    while($row = $result->fetch_assoc()) { $jobs[] = $row; }
    $stmt->close();
    $conn->close();
    return $jobs;
}