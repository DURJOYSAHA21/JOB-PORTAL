<?php
require_once __DIR__ . '/../db.php';

function createJob($employer_id, $category_id, $title, $description, $requirements, $benefits, $salary_min, $salary_max, $location, $job_type, $experience_level, $deadline, $status = 'draft', $recruiter_id = null) {
    $conn = connect();
    $sql = "INSERT INTO jobs (employer_id, recruiter_id, category_id, title, description, requirements, benefits, salary_min, salary_max, location, job_type, experience_level, deadline, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiissssdssssss", $employer_id, $recruiter_id, $category_id, $title, $description, $requirements, $benefits, $salary_min, $salary_max, $location, $job_type, $experience_level, $deadline, $status);
    $result = $stmt->execute();
    $insert_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    return $result ? $insert_id : false;
}

function getEmployerJobs($employer_id) {
    $conn = connect();
    $sql = "SELECT j.*, c.name as category_name, (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count FROM jobs j LEFT JOIN categories c ON j.category_id = c.id WHERE j.employer_id = ? ORDER BY j.created_at DESC";
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

function getJobById($job_id, $employer_id = null) {
    $conn = connect();
    if($employer_id) {
        $sql = "SELECT j.*, c.name as category_name FROM jobs j LEFT JOIN categories c ON j.category_id = c.id WHERE j.id = ? AND j.employer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $job_id, $employer_id);
    } else {
        $sql = "SELECT j.*, c.name as category_name FROM jobs j LEFT JOIN categories c ON j.category_id = c.id WHERE j.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $job_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $job;
}

function updateJob($job_id, $employer_id, $category_id, $title, $description, $requirements, $benefits, $salary_min, $salary_max, $location, $job_type, $experience_level, $deadline, $status) {
    $conn = connect();
    $sql = "UPDATE jobs SET category_id=?, title=?, description=?, requirements=?, benefits=?, salary_min=?, salary_max=?, location=?, job_type=?, experience_level=?, deadline=?, status=? WHERE id=? AND employer_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssdssssssii", $category_id, $title, $description, $requirements, $benefits, $salary_min, $salary_max, $location, $job_type, $experience_level, $deadline, $status, $job_id, $employer_id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function deleteJob($job_id, $employer_id) {
    $conn = connect();
    $sql = "DELETE FROM jobs WHERE id = ? AND employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $employer_id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function toggleJobStatus($job_id, $employer_id) {
    $conn = connect();
    $job = getJobById($job_id, $employer_id);
    if(!$job) { $conn->close(); return false; }
    $new_status = ($job['status'] === 'active') ? 'closed' : 'active';
    $sql = "UPDATE jobs SET status = ? WHERE id = ? AND employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $new_status, $job_id, $employer_id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result ? ['success' => true, 'new_status' => $new_status, 'job_id' => $job_id] : false;
}

function repostJob($job_id, $employer_id) {
    $conn = connect();
    $sql = "UPDATE jobs SET status = 'active' WHERE id = ? AND employer_id = ? AND status = 'closed'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $employer_id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function getAllCategories() {
    $conn = connect();
    $sql = "SELECT id, name FROM categories ORDER BY name ASC";
    $result = $conn->query($sql);
    $categories = [];
    while($row = $result->fetch_assoc()) { $categories[] = $row; }
    $conn->close();
    return $categories;
}

function getJobApplicationFunnel($job_id, $employer_id) {
    $conn = connect();
    $sql = "SELECT id, title FROM jobs WHERE id = ? AND employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();
    $stmt->close();
    if(!$job) { $conn->close(); return null; }
    $sql = "SELECT COUNT(*) as total, SUM(CASE WHEN status='submitted' THEN 1 ELSE 0 END) as submitted, SUM(CASE WHEN status='reviewed' THEN 1 ELSE 0 END) as reviewed, SUM(CASE WHEN status='shortlisted' THEN 1 ELSE 0 END) as shortlisted, SUM(CASE WHEN status='interview' THEN 1 ELSE 0 END) as interview, SUM(CASE WHEN status='rejected' THEN 1 ELSE 0 END) as rejected FROM applications WHERE job_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $funnel = $result->fetch_assoc();
    $funnel['job_title'] = $job['title'];
    $stmt->close();
    $conn->close();
    return $funnel;
}

function getApplicationsOverTime($job_id, $employer_id) {
    $conn = connect();
    $sql = "SELECT id FROM jobs WHERE id = ? AND employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) { $stmt->close(); $conn->close(); return []; }
    $stmt->close();
    $sql = "SELECT DATE(applied_at) as date, COUNT(*) as count FROM applications WHERE job_id=? GROUP BY DATE(applied_at) ORDER BY DATE(applied_at) ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while($row = $result->fetch_assoc()) { $data[] = ['date' => $row['date'], 'count' => (int)$row['count']]; }
    $stmt->close();
    $conn->close();
    return $data;
}

function getRecruiterJobs($recruiter_profile_id) {
    $conn = connect();
    $sql = "SELECT j.*, c.name as category_name,
            COALESCE(
                (SELECT company_name_override FROM recruiter_clients WHERE recruiter_id = j.recruiter_id AND employer_id = j.employer_id LIMIT 1),
                (SELECT company_name FROM employer_profiles WHERE id = j.employer_id LIMIT 1)
            ) as client_name,
            (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count
            FROM jobs j
            LEFT JOIN categories c ON j.category_id = c.id
            WHERE j.recruiter_id = ?
            ORDER BY j.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recruiter_profile_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}