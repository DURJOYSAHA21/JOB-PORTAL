<?php
require_once __DIR__ . '/../../db.php';

function getRecruiterAnalytics($recruiterProfileId) {
    $conn = connect();

    // Total outreach
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM recruiter_outreach WHERE recruiter_id = ?");
    $stmt->bind_param("i", $recruiterProfileId);
    $stmt->execute();
    $totalOutreach = (int)$stmt->get_result()->fetch_assoc()['cnt'];
    $stmt->close();

    // Outreach status counts
    $stmt = $conn->prepare("SELECT 
        SUM(CASE WHEN status='sent' THEN 1 ELSE 0 END) as sent,
        SUM(CASE WHEN status='read' THEN 1 ELSE 0 END) as `read`,
        SUM(CASE WHEN status='responded' THEN 1 ELSE 0 END) as responded
        FROM recruiter_outreach WHERE recruiter_id = ?");
    $stmt->bind_param("i", $recruiterProfileId);
    $stmt->execute();
    $outreach = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $responseRate = $totalOutreach > 0 ? round((($outreach['read'] + $outreach['responded']) / $totalOutreach) * 100) : 0;

    // Total applications
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.recruiter_id = ?");
    $stmt->bind_param("i", $recruiterProfileId);
    $stmt->execute();
    $totalApps = (int)$stmt->get_result()->fetch_assoc()['cnt'];
    $stmt->close();

    // Application status breakdown
    $stmt = $conn->prepare("SELECT 
        SUM(CASE WHEN a.status='submitted' THEN 1 ELSE 0 END) as submitted,
        SUM(CASE WHEN a.status='reviewed' THEN 1 ELSE 0 END) as reviewed,
        SUM(CASE WHEN a.status='shortlisted' THEN 1 ELSE 0 END) as shortlisted,
        SUM(CASE WHEN a.status='interview' THEN 1 ELSE 0 END) as interview,
        SUM(CASE WHEN a.status='hired' THEN 1 ELSE 0 END) as hired
        FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.recruiter_id = ?");
    $stmt->bind_param("i", $recruiterProfileId);
    $stmt->execute();
    $appStatus = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $totalHired = (int)$appStatus['hired'];
    $placementRate = $totalApps > 0 ? round(($totalHired / $totalApps) * 100) : 0;

    $conn->close();

    return [
        'total_outreach' => $totalOutreach,
        'outreach_sent' => (int)$outreach['sent'],
        'outreach_read' => (int)$outreach['read'],
        'outreach_responded' => (int)$outreach['responded'],
        'response_rate' => $responseRate,
        'total_applications' => $totalApps,
        'app_submitted' => (int)$appStatus['submitted'],
        'app_reviewed' => (int)$appStatus['reviewed'],
        'app_shortlisted' => (int)$appStatus['shortlisted'],
        'app_interview' => (int)$appStatus['interview'],
        'app_hired' => $totalHired,
        'placement_rate' => $placementRate
    ];
}

function getClientSuccessRates($recruiterProfileId) {
    $conn = connect();
    $sql = "SELECT 
            COALESCE(rc.company_name_override, ep.company_name) as client_name,
            COUNT(a.id) as total,
            SUM(CASE WHEN a.status = 'hired' THEN 1 ELSE 0 END) as hired
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            LEFT JOIN recruiter_clients rc ON j.recruiter_id = rc.recruiter_id AND j.employer_id = rc.employer_id
            LEFT JOIN employer_profiles ep ON j.employer_id = ep.id
            WHERE j.recruiter_id = ?
            GROUP BY client_name
            ORDER BY total DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recruiterProfileId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $result;
}