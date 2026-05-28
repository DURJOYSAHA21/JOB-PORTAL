<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/recruiter-client-model.php';

function filterRecruiterJobs($recruiterProfileId, $filters = []) {
    $conn = connect();
    $sql = "SELECT j.*, c.name as category_name,
            COALESCE(
                (SELECT company_name_override FROM recruiter_clients WHERE recruiter_id = j.recruiter_id AND employer_id = j.employer_id LIMIT 1),
                (SELECT company_name FROM employer_profiles WHERE id = j.employer_id LIMIT 1)
            ) as client_name,
            (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count
            FROM jobs j
            LEFT JOIN categories c ON j.category_id = c.id
            WHERE j.recruiter_id = ?";
    $params = [$recruiterProfileId];
    $types = "i";

    if(!empty($filters['status'])) { 
        $sql .= " AND j.status = ?"; 
        $params[] = $filters['status']; 
        $types .= "s"; 
    }
    if(!empty($filters['category_id'])) { 
        $sql .= " AND j.category_id = ?"; 
        $params[] = (int)$filters['category_id']; 
        $types .= "i"; 
    }
    if(!empty($filters['client_id'])) {
        // Filter by client: match jobs where employer_id matches the client's employer_id
        $sql .= " AND j.employer_id = (SELECT ep.user_id FROM recruiter_clients rc 
                  JOIN employer_profiles ep ON rc.employer_id = ep.id 
                  WHERE rc.id = ? LIMIT 1)";
        $params[] = (int)$filters['client_id'];
        $types .= "i";
    }

    $sql .= " ORDER BY j.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}