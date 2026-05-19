<?php
session_start();
require_once("../db.php");
require_once("../Model/job_model.php");

// ============ AJAX REQUEST ============
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    
    header('Content-Type: application/json');
    
    $keyword = $_GET['keyword'] ?? '';
    $categoryId = $_GET['category_id'] ?? '';
    $location = $_GET['location'] ?? '';
    $jobType = $_GET['job_type'] ?? '';
    $experienceLevel = $_GET['experience_level'] ?? '';
    $salaryMin = $_GET['salary_min'] ?? '';
    $salaryMax = $_GET['salary_max'] ?? '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    
    $filters = [
        'category_id' => $categoryId,
        'location' => $location,
        'job_type' => $jobType,
        'experience_level' => $experienceLevel,
        'salary_min' => $salaryMin,
        'salary_max' => $salaryMax
    ];
    
    // Get jobs
    $jobs = searchJobs($keyword, $filters, $limit, $offset);
    $total = countSearchJobs($keyword, $filters);
    
    // Format for JSON
    $formattedJobs = [];
    foreach ($jobs as $job) {
        $formattedJobs[] = [
            'id' => $job['id'],
            'title' => $job['title'],
            'company_name' => $job['company_name'] ?? $job['employer_name'] ?? 'Unknown',
            'category_name' => $job['category_name'] ?? 'N/A',
            'location' => $job['location'],
            'job_type' => $job['job_type'],
            'experience_level' => $job['experience_level'],
            'salary_display' => formatSalary($job['salary_min'], $job['salary_max']),
            'time_ago' => timeAgo($job['created_at']),
            'is_featured' => $job['is_featured'],
            'deadline' => $job['deadline'] ? date('M j, Y', strtotime($job['deadline'])) : 'N/A'
        ];
    }
    
    $totalPages = ceil($total / $limit);
    
    echo json_encode([
        'success' => true,
        'jobs' => $formattedJobs,
        'total' => $total,
        'page' => $page,
        'total_pages' => $totalPages,
        'has_more' => $page < $totalPages
    ]);
    exit();
}

// ============ GET REQUEST - Load View ============
$categories = getAllCategories();
$locations = getJobLocations();
$totalJobs = countActiveJobs();

$viewData = [
    'categories' => $categories,
    'locations' => $locations,
    'totalJobs' => $totalJobs,
    'keyword' => $_GET['keyword'] ?? '',
    'filters' => [
        'category_id' => $_GET['category_id'] ?? '',
        'location' => $_GET['location'] ?? '',
        'job_type' => $_GET['job_type'] ?? '',
        'experience_level' => $_GET['experience_level'] ?? '',
        'salary_min' => $_GET['salary_min'] ?? '',
        'salary_max' => $_GET['salary_max'] ?? ''
    ]
];

require_once("../View/browse_jobs_view.php");