<?php
require_once("../db.php");

function getAllActiveJobs($limit = 10, $offset = 0) {
    global $conn;
    $sql = "SELECT j.*, c.name AS category_name, ep.company_name
            FROM jobs j
            LEFT JOIN categories c ON j.category_id = c.id
            LEFT JOIN users u ON j.employer_id = u.id
            LEFT JOIN employer_profiles ep ON u.id = ep.user_id
            WHERE j.status = 'active'
            ORDER BY j.is_featured DESC, j.created_at DESC
            LIMIT $limit OFFSET $offset";
    
    $result = mysqli_query($conn, $sql);
    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
    return $jobs;
}

function countActiveJobs() {
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM jobs WHERE status = 'active'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function searchJobs($keyword, $filters = [], $limit = 10, $offset = 0) {
    global $conn;
    
    $where = "WHERE j.status = 'active'";
    
    if (!empty($keyword)) {
        $keyword = mysqli_real_escape_string($conn, $keyword);
        $where .= " AND (j.title LIKE '%$keyword%' 
                     OR j.description LIKE '%$keyword%' 
                     OR ep.company_name LIKE '%$keyword%' 
                     OR u.name LIKE '%$keyword%')";
    }
    
    if (!empty($filters['category_id'])) {
        $catId = (int)$filters['category_id'];
        $where .= " AND j.category_id = $catId";
    }
    
    if (!empty($filters['location'])) {
        $loc = mysqli_real_escape_string($conn, $filters['location']);
        $where .= " AND j.location LIKE '%$loc%'";
    }
    
    if (!empty($filters['job_type'])) {
        $jt = mysqli_real_escape_string($conn, $filters['job_type']);
        $where .= " AND j.job_type = '$jt'";
    }
    
    if (!empty($filters['experience_level'])) {
        $el = mysqli_real_escape_string($conn, $filters['experience_level']);
        $where .= " AND j.experience_level = '$el'";
    }
    
    if (!empty($filters['salary_min'])) {
        $smin = (float)$filters['salary_min'];
        $where .= " AND j.salary_max >= $smin";
    }
    
    if (!empty($filters['salary_max'])) {
        $smax = (float)$filters['salary_max'];
        $where .= " AND j.salary_min <= $smax";
    }
    
    $sql = "SELECT j.*, c.name AS category_name, ep.company_name
            FROM jobs j
            LEFT JOIN categories c ON j.category_id = c.id
            LEFT JOIN users u ON j.employer_id = u.id
            LEFT JOIN employer_profiles ep ON u.id = ep.user_id
            $where
            ORDER BY j.is_featured DESC, j.created_at DESC
            LIMIT $limit OFFSET $offset";
    
    $result = mysqli_query($conn, $sql);
    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
    return $jobs;
}

function countSearchJobs($keyword, $filters = []) {
    global $conn;
    
    $where = "WHERE j.status = 'active'";
    
    if (!empty($keyword)) {
        $keyword = mysqli_real_escape_string($conn, $keyword);
        $where .= " AND (j.title LIKE '%$keyword%' 
                     OR j.description LIKE '%$keyword%' 
                     OR ep.company_name LIKE '%$keyword%' 
                     OR u.name LIKE '%$keyword%')";
    }
    
    if (!empty($filters['category_id'])) {
        $catId = (int)$filters['category_id'];
        $where .= " AND j.category_id = $catId";
    }
    
    if (!empty($filters['location'])) {
        $loc = mysqli_real_escape_string($conn, $filters['location']);
        $where .= " AND j.location LIKE '%$loc%'";
    }
    
    if (!empty($filters['job_type'])) {
        $jt = mysqli_real_escape_string($conn, $filters['job_type']);
        $where .= " AND j.job_type = '$jt'";
    }
    
    if (!empty($filters['experience_level'])) {
        $el = mysqli_real_escape_string($conn, $filters['experience_level']);
        $where .= " AND j.experience_level = '$el'";
    }
    
    if (!empty($filters['salary_min'])) {
        $smin = (float)$filters['salary_min'];
        $where .= " AND j.salary_max >= $smin";
    }
    
    if (!empty($filters['salary_max'])) {
        $smax = (float)$filters['salary_max'];
        $where .= " AND j.salary_min <= $smax";
    }
    
    $sql = "SELECT COUNT(*) AS total
            FROM jobs j
            LEFT JOIN users u ON j.employer_id = u.id
            LEFT JOIN employer_profiles ep ON u.id = ep.user_id
            $where";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function getAllCategories() {
    global $conn;
    $sql = "SELECT DISTINCT id, name FROM categories ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

function getJobLocations() {
    global $conn;
    $sql = "SELECT DISTINCT location FROM jobs WHERE status = 'active' AND location != '' ORDER BY location ASC";
    $result = mysqli_query($conn, $sql);
    $locations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $locations[] = $row['location'];
    }
    return $locations;
}

function formatSalary($min, $max) {
    if ($min <= 0 && $max <= 0) return "Negotiable";
    if ($min <= 0) return "Up to ৳" . number_format($max);
    if ($max <= 0) return "From ৳" . number_format($min);
    return "৳" . number_format($min) . " - ৳" . number_format($max);
}

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return "Just now";
    if ($diff < 3600) return floor($diff / 60) . "m ago";
    if ($diff < 86400) return floor($diff / 3600) . "h ago";
    if ($diff < 604800) return floor($diff / 86400) . "d ago";
    return date("M j, Y", $time);
}
function getJobById($jobId) {
    global $conn;
    $jobId = (int)$jobId;
    
    $sql = "SELECT j.*, 
                   c.name AS category_name,
                   ep.company_name, ep.description AS company_description,
                   ep.website AS company_website, ep.logo_path AS company_logo,
                   u.name AS employer_name
            FROM jobs j
            LEFT JOIN categories c ON j.category_id = c.id
            LEFT JOIN users u ON j.employer_id = u.id
            LEFT JOIN employer_profiles ep ON u.id = ep.user_id
            WHERE j.id = $jobId AND j.status = 'active'
            LIMIT 1";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}
/**
 * Check if user already applied to this job
 */
function hasUserApplied($jobId, $seekerId) {
    global $conn;
    $jobId = (int)$jobId;
    $seekerId = (int)$seekerId;
    
    $sql = "SELECT id FROM applications WHERE job_id = $jobId AND seeker_id = $seekerId";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result) > 0;
}

/**
 * Submit job application
 */
function submitApplication($jobId, $seekerId, $coverLetter, $resumePath) {
    global $conn;
    
    $jobId = (int)$jobId;
    $seekerId = (int)$seekerId;
    $coverLetter = mysqli_real_escape_string($conn, $coverLetter);
    $resumePath = mysqli_real_escape_string($conn, $resumePath);
    
    $sql = "INSERT INTO applications (job_id, seeker_id, cover_letter, resume_path, status, applied_at)
            VALUES ($jobId, $seekerId, '$coverLetter', '$resumePath', 'submitted', NOW())";
    
    return mysqli_query($conn, $sql);
}
/**
 * Get user's application for a specific job
 */
function getUserApplication($jobId, $seekerId) {
    global $conn;
    $jobId = (int)$jobId;
    $seekerId = (int)$seekerId;
    
    $sql = "SELECT * FROM applications WHERE job_id = $jobId AND seeker_id = $seekerId LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

/**
 * Withdraw application (only if status is still 'submitted')
 */
function withdrawApplication($applicationId, $seekerId) {
    global $conn;
    $applicationId = (int)$applicationId;
    $seekerId = (int)$seekerId;
    
    $sql = "UPDATE applications SET status = 'withdrawn' 
            WHERE id = $applicationId AND seeker_id = $seekerId AND status = 'submitted'";
    
    mysqli_query($conn, $sql);
    return mysqli_affected_rows($conn) > 0;
}
/**
 * Get all applications by seeker
 */
function getSeekerApplications($seekerId) {
    global $conn;
    $seekerId = (int)$seekerId;
    
    $sql = "SELECT a.*, j.title AS job_title, j.location AS job_location, 
                   j.job_type, j.salary_min, j.salary_max,
                   ep.company_name, u.name AS employer_name
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            LEFT JOIN users u ON j.employer_id = u.id
            LEFT JOIN employer_profiles ep ON u.id = ep.user_id
            WHERE a.seeker_id = $seekerId
            ORDER BY a.applied_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $applications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $applications[] = $row;
    }
    return $applications;
}

/**
 * Get status badge class and label
 */
function getStatusBadge($status) {
    $badges = [
        'submitted' => ['label' => 'Submitted', 'class' => 'badge-submitted'],
        'reviewed' => ['label' => 'Reviewed', 'class' => 'badge-reviewed'],
        'shortlisted' => ['label' => 'Shortlisted', 'class' => 'badge-shortlisted'],
        'interview' => ['label' => 'Interview', 'class' => 'badge-interview'],
        'rejected' => ['label' => 'Rejected', 'class' => 'badge-rejected'],
        'withdrawn' => ['label' => 'Withdrawn', 'class' => 'badge-withdrawn']
    ];
    return $badges[$status] ?? ['label' => ucfirst($status), 'class' => ''];
}
/**
 * Check if job is saved by user
 */
function isJobSaved($jobId, $userId) {
    global $conn;
    $jobId = (int)$jobId;
    $userId = (int)$userId;
    
    $sql = "SELECT id FROM saved_jobs WHERE job_id = $jobId AND user_id = $userId";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result) > 0;
}

/**
 * Save a job
 */
function saveJob($jobId, $userId) {
    global $conn;
    $jobId = (int)$jobId;
    $userId = (int)$userId;
    
    $sql = "INSERT INTO saved_jobs (user_id, job_id, saved_at) VALUES ($userId, $jobId, NOW())";
    return mysqli_query($conn, $sql);
}

/**
 * Unsave a job
 */
function unsaveJob($jobId, $userId) {
    global $conn;
    $jobId = (int)$jobId;
    $userId = (int)$userId;
    
    $sql = "DELETE FROM saved_jobs WHERE job_id = $jobId AND user_id = $userId";
    mysqli_query($conn, $sql);
    return mysqli_affected_rows($conn) > 0;
}

/**
 * Get all saved jobs by user
 */
function getSavedJobs($userId) {
    global $conn;
    $userId = (int)$userId;
    
    $sql = "SELECT s.id AS saved_id, s.saved_at, j.*, 
                   c.name AS category_name,
                   ep.company_name, u.name AS employer_name
            FROM saved_jobs s
            JOIN jobs j ON s.job_id = j.id
            LEFT JOIN categories c ON j.category_id = c.id
            LEFT JOIN users u ON j.employer_id = u.id
            LEFT JOIN employer_profiles ep ON u.id = ep.user_id
            WHERE s.user_id = $userId
            ORDER BY s.saved_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
    return $jobs;
}