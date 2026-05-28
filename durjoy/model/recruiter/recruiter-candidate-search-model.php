<?php
require_once __DIR__ . '/../../db.php';

function getAllCandidates() {
    $conn = connect();
    $sql = "SELECT u.id as user_id, u.name, sp.headline, sp.skills, sp.years_experience, 
            sp.preferred_location, sp.education_level, sp.expected_salary, sp.summary
            FROM seeker_profiles sp
            JOIN users u ON sp.user_id = u.id
            WHERE u.is_active = 1 AND u.is_verified = 1
            ORDER BY sp.years_experience DESC
            LIMIT 50";
    $result = $conn->query($sql);
    $candidates = [];
    while($row = $result->fetch_assoc()) { $candidates[] = $row; }
    $conn->close();
    return $candidates;
}

function searchCandidates($filters = []) {
    $conn = connect();
    $sql = "SELECT u.id as user_id, u.name, sp.headline, sp.skills, sp.years_experience, 
            sp.preferred_location, sp.education_level, sp.expected_salary, sp.summary
            FROM seeker_profiles sp
            JOIN users u ON sp.user_id = u.id
            WHERE u.is_active = 1 AND u.is_verified = 1";
    $params = [];
    $types = "";

    if(!empty($filters['keyword'])) {
        $kw = '%' . $filters['keyword'] . '%';
        $sql .= " AND (sp.skills LIKE ? OR sp.headline LIKE ? OR u.name LIKE ? OR sp.summary LIKE ?)";
        $params[] = $kw; $params[] = $kw; $params[] = $kw; $params[] = $kw;
        $types .= "ssss";
    }
    if(!empty($filters['location'])) {
        $loc = '%' . $filters['location'] . '%';
        $sql .= " AND sp.preferred_location LIKE ?";
        $params[] = $loc; $types .= "s";
    }
    if(!empty($filters['experience'])) {
        if($filters['experience'] === 'entry') { $sql .= " AND sp.years_experience <= 2"; }
        elseif($filters['experience'] === 'mid') { $sql .= " AND sp.years_experience BETWEEN 3 AND 5"; }
        elseif($filters['experience'] === 'senior') { $sql .= " AND sp.years_experience >= 5"; }
    }
    if(!empty($filters['salary'])) {
        $sql .= " AND sp.expected_salary <= ?";
        $params[] = (int)$filters['salary']; $types .= "i";
    }

    $sql .= " ORDER BY sp.years_experience DESC LIMIT 50";
    $stmt = $conn->prepare($sql);
    if(!empty($params)) { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $candidates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $candidates;
}
function getSeekerPublicProfile($seeker_user_id) {
    $conn = connect();
    $sql = "SELECT u.name, u.email, u.phone,
            sp.headline, sp.summary, sp.skills, sp.years_experience, 
            sp.education_level, sp.current_salary, sp.expected_salary, 
            sp.preferred_location, sp.resume_path
            FROM users u
            LEFT JOIN seeker_profiles sp ON u.id = sp.user_id
            WHERE u.id = ? AND u.role = 'seeker' AND u.is_active = 1 AND u.is_verified = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seeker_user_id);
    $stmt->execute();
    $profile = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $profile;
}