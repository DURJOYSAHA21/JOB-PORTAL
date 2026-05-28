<?php
require_once __DIR__ . '/../../db.php';

function getClientReport($recruiterProfileId, $clientId) {
    $conn = connect();

    // Get employer_id for this client
    $stmt = $conn->prepare("SELECT employer_id FROM recruiter_clients WHERE id = ? AND recruiter_id = ?");
    $stmt->bind_param("ii", $clientId, $recruiterProfileId);
    $stmt->execute();
    $client = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if(!$client) { $conn->close(); return null; }
    $employerId = $client['employer_id'];

    // Get employer user_id
    $employerUserId = null;
    if($employerId) {
        $stmt = $conn->prepare("SELECT user_id FROM employer_profiles WHERE id = ?");
        $stmt->bind_param("i", $employerId);
        $stmt->execute();
        $ep = $stmt->get_result()->fetch_assoc();
        $employerUserId = $ep ? (int)$ep['user_id'] : null;
        $stmt->close();
    }

    // Total jobs
    $sql = "SELECT COUNT(*) as cnt FROM jobs WHERE recruiter_id = ?";
    $params = [$recruiterProfileId];
    $types = "i";
    if($employerUserId) { $sql .= " AND employer_id = ?"; $params[] = $employerUserId; $types .= "i"; }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $totalJobs = (int)$stmt->get_result()->fetch_assoc()['cnt'];
    $stmt->close();

    // Active jobs
    $sql = "SELECT COUNT(*) as cnt FROM jobs WHERE recruiter_id = ? AND status='active'";
    $params = [$recruiterProfileId];
    $types = "i";
    if($employerUserId) { $sql .= " AND employer_id = ?"; $params[] = $employerUserId; $types .= "i"; }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $activeJobs = (int)$stmt->get_result()->fetch_assoc()['cnt'];
    $stmt->close();

    // Jobs list with app counts
    $sql = "SELECT j.id, j.title, j.status, (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as app_count
            FROM jobs j WHERE j.recruiter_id = ?";
    $params = [$recruiterProfileId];
    $types = "i";
    if($employerUserId) { $sql .= " AND j.employer_id = ?"; $params[] = $employerUserId; $types .= "i"; }
    $sql .= " ORDER BY j.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $jobs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Pipeline stage counts
    $sql = "SELECT 
            COUNT(a.id) as total,
            SUM(CASE WHEN a.status='submitted' THEN 1 ELSE 0 END) as submitted,
            SUM(CASE WHEN a.status='reviewed' THEN 1 ELSE 0 END) as reviewed,
            SUM(CASE WHEN a.status='shortlisted' THEN 1 ELSE 0 END) as shortlisted,
            SUM(CASE WHEN a.status='interview' THEN 1 ELSE 0 END) as interview,
            SUM(CASE WHEN a.status='rejected' THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN a.status='hired' THEN 1 ELSE 0 END) as hired
            FROM applications a JOIN jobs j ON a.job_id = j.id
            WHERE j.recruiter_id = ?";
    $params = [$recruiterProfileId];
    $types = "i";
    if($employerUserId) { $sql .= " AND j.employer_id = ?"; $params[] = $employerUserId; $types .= "i"; }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stages = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $conn->close();

    return [
        'total_jobs' => $totalJobs,
        'active_jobs' => $activeJobs,
        'total_applications' => (int)$stages['total'],
        'hired_count' => (int)$stages['hired'],
        'jobs' => $jobs,
        'stage_submitted' => (int)$stages['submitted'],
        'stage_reviewed' => (int)$stages['reviewed'],
        'stage_shortlisted' => (int)$stages['shortlisted'],
        'stage_interview' => (int)$stages['interview'],
        'stage_rejected' => (int)$stages['rejected'],
        'stage_hired' => (int)$stages['hired']
    ];
}