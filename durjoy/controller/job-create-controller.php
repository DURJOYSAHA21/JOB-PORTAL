<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../model/job-model.php";

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../view/login-view.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employer_id = (int)$_SESSION['user']['id'];
    $category_id = (int)($_POST['category_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $requirements = trim($_POST['requirements'] ?? '');
    $benefits = trim($_POST['benefits'] ?? '');
    $salary_min = $_POST['salary_min'] !== '' ? (float)$_POST['salary_min'] : null;
    $salary_max = $_POST['salary_max'] !== '' ? (float)$_POST['salary_max'] : null;
    $location = trim($_POST['location'] ?? '');
    $job_type = trim($_POST['job_type'] ?? '');
    $experience_level = trim($_POST['experience_level'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');
    $action = $_POST['action'] ?? 'draft';
    $status = ($action === 'publish') ? 'active' : 'draft';
    $errors = [];

    if(empty($title)) $errors['title'] = 'Job title is required';
    if($category_id <= 0) $errors['category_id'] = 'Please select a category';
    if(empty($description)) $errors['description'] = 'Job description is required';
    if(empty($location)) $errors['location'] = 'Location is required';
    if(empty($job_type)) $errors['job_type'] = 'Please select a job type';
    if(empty($experience_level)) $errors['experience_level'] = 'Please select experience level';
    if(empty($deadline)) $errors['deadline'] = 'Application deadline is required';
    if($salary_min !== null && $salary_max !== null && $salary_min > $salary_max) $errors['salary_range'] = 'Min salary cannot be greater than max salary';

    if(!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header("Location: ../view/jobs/post-job-view.php");
        exit();
    }

    $job_id = createJob($employer_id, $category_id, $title, $description, $requirements, $benefits, $salary_min, $salary_max, $location, $job_type, $experience_level, $deadline, $status);

    if($job_id) {
        $_SESSION['success'] = ($status === 'active') ? 'Job posted successfully!' : 'Job saved as draft!';
        header("Location: ../view/jobs/manage-jobs-view.php");
    } else {
        $_SESSION['errors'] = ['db_error' => 'Failed to create job'];
        $_SESSION['old_input'] = $_POST;
        header("Location: ../view/jobs/post-job-view.php");
    }
    exit();
}