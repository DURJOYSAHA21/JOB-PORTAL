<?php
require_once __DIR__ . '/../db.php';

function getApplicationsByEmployer($employer_id) {
    $conn = connect();
    $sql = "SELECT a.*, j.title as job_title, u.name as applicant_name, u.email as applicant_email, u.phone as applicant_phone,
            sp.headline, sp.skills, sp.years_experience, sp.education_level, sp.resume_path as seeker_resume
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            WHERE j.employer_id = ?
            ORDER BY a.applied_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applications = [];
    while($row = $result->fetch_assoc()) { $applications[] = $row; }
    $stmt->close();
    $conn->close();
    return $applications;
}

function getEmployerJobTitles($employer_id) {
    $conn = connect();
    $sql = "SELECT id, title FROM jobs WHERE employer_id = ? ORDER BY title ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobs = [];
    while($row = $result->fetch_assoc()) { $jobs[] = $row; }
    $stmt->close();
    $conn->close();
    return $jobs;
}

function filterApplications($employer_id, $filters = []) {
    $conn = connect();
    $sql = "SELECT a.*, j.title as job_title, u.name as applicant_name, u.email as applicant_email, u.phone as applicant_phone,
            sp.headline, sp.skills, sp.years_experience, sp.education_level, sp.resume_path as seeker_resume
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            WHERE j.employer_id = ?";
    $params = [$employer_id];
    $types = "i";

    if(!empty($filters['job_id'])) { $sql .= " AND a.job_id = ?"; $params[] = (int)$filters['job_id']; $types .= "i"; }
    if(!empty($filters['status'])) { $sql .= " AND a.status = ?"; $params[] = $filters['status']; $types .= "s"; }
    if(!empty($filters['experience_level'])) { $sql .= " AND j.experience_level = ?"; $params[] = $filters['experience_level']; $types .= "s"; }
    if(!empty($filters['date_from'])) { $sql .= " AND DATE(a.applied_at) >= ?"; $params[] = $filters['date_from']; $types .= "s"; }
    if(!empty($filters['date_to'])) { $sql .= " AND DATE(a.applied_at) <= ?"; $params[] = $filters['date_to']; $types .= "s"; }

    $sql .= " ORDER BY a.applied_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $applications = [];
    while($row = $result->fetch_assoc()) { $applications[] = $row; }
    $stmt->close();
    $conn->close();
    return $applications;
}

function getApplicationById($application_id, $employer_id) {
    $conn = connect();
    $sql = "SELECT a.*, j.title as job_title, u.name as applicant_name, u.email as applicant_email, u.phone as applicant_phone,
            u.id as seeker_user_id, sp.headline, sp.skills, sp.years_experience, sp.education_level,
            sp.current_salary, sp.expected_salary, sp.preferred_location, sp.summary, sp.resume_path as seeker_resume
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            WHERE a.id = ? AND j.employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $application_id, $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $application;
}

function updateApplicationStatus($application_id, $employer_id, $new_status) {
    $conn = connect();
    $sql = "SELECT a.id FROM applications a JOIN jobs j ON a.job_id = j.id WHERE a.id = ? AND j.employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $application_id, $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) { $stmt->close(); $conn->close(); return false; }
    $stmt->close();

    $sql = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $application_id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result ? $new_status : false;
}

function getShortlistedCandidates($employer_id) {
    $conn = connect();
    $sql = "SELECT a.*, j.title as job_title, u.name as applicant_name, u.email as applicant_email, u.phone as applicant_phone,
            sp.headline, sp.skills, sp.years_experience, sp.education_level, sp.resume_path as seeker_resume
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            WHERE j.employer_id = ? AND a.status = 'shortlisted'
            ORDER BY a.applied_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $candidates = [];
    while($row = $result->fetch_assoc()) { $candidates[] = $row; }
    $stmt->close();
    $conn->close();
    return $candidates;
}

function getCompanyAnalytics($employer_id) {
    $conn = connect();

    $sql = "SELECT COUNT(*) as total_jobs FROM jobs WHERE employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $totalJobs = (int)$stmt->get_result()->fetch_assoc()['total_jobs'];
    $stmt->close();

    $sql = "SELECT COUNT(*) as active_jobs FROM jobs WHERE employer_id = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $activeJobs = (int)$stmt->get_result()->fetch_assoc()['active_jobs'];
    $stmt->close();

    $sql = "SELECT COUNT(*) as total_apps FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $totalApps = (int)$stmt->get_result()->fetch_assoc()['total_apps'];
    $stmt->close();

    $sql = "SELECT SUM(CASE WHEN a.status='submitted' THEN 1 ELSE 0 END) as submitted,
            SUM(CASE WHEN a.status='reviewed' THEN 1 ELSE 0 END) as reviewed,
            SUM(CASE WHEN a.status='shortlisted' THEN 1 ELSE 0 END) as shortlisted,
            SUM(CASE WHEN a.status='interview' THEN 1 ELSE 0 END) as interview,
            SUM(CASE WHEN a.status='rejected' THEN 1 ELSE 0 END) as rejected
            FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $statusBreakdown = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $sql = "SELECT AVG(DATEDIFF(
                (SELECT MIN(a2.applied_at) FROM applications a2 WHERE a2.seeker_id = a.seeker_id AND a2.status = 'shortlisted'),
                a.applied_at
            )) as avg_days
            FROM applications a JOIN jobs j ON a.job_id = j.id
            WHERE j.employer_id = ? AND a.status = 'shortlisted'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $avgData = $stmt->get_result()->fetch_assoc();
    $avgDays = $avgData['avg_days'] ? round((float)$avgData['avg_days'], 1) : 0;
    $stmt->close();

    $sql = "SELECT j.id, j.title, COUNT(a.id) as app_count FROM jobs j LEFT JOIN applications a ON j.id = a.job_id WHERE j.employer_id = ? GROUP BY j.id, j.title ORDER BY app_count DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appsPerJob = [];
    while($row = $result->fetch_assoc()) { $appsPerJob[] = $row; }
    $stmt->close();

    $sql = "SELECT DATE_FORMAT(a.applied_at, '%Y-%m') as month, COUNT(*) as count FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.employer_id = ? GROUP BY DATE_FORMAT(a.applied_at, '%Y-%m') ORDER BY month ASC LIMIT 12";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $monthlyTrend = [];
    while($row = $result->fetch_assoc()) { $monthlyTrend[] = $row; }
    $stmt->close();

    $conn->close();

    return [
        'total_jobs' => $totalJobs,
        'active_jobs' => $activeJobs,
        'total_applications' => $totalApps,
        'submitted' => (int)$statusBreakdown['submitted'],
        'reviewed' => (int)$statusBreakdown['reviewed'],
        'shortlisted' => (int)$statusBreakdown['shortlisted'],
        'interview' => (int)$statusBreakdown['interview'],
        'rejected' => (int)$statusBreakdown['rejected'],
        'avg_days_to_shortlist' => $avgDays,
        'apps_per_job' => $appsPerJob,
        'monthly_trend' => $monthlyTrend
    ];
}

?>