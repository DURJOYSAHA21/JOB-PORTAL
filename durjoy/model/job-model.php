<?php
require_once __DIR__ . '/../db.php';

function getEmployerJobs($employer_id) {
    $conn = connect();
    $sql = "SELECT j.*, c.name as category_name, 
            (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count
            FROM jobs j
            LEFT JOIN categories c ON j.category_id = c.id
            WHERE j.employer_id = ?
            ORDER BY j.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $jobs = [];
    while($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    
    $stmt->close();
    $conn->close();
    return $jobs;
}