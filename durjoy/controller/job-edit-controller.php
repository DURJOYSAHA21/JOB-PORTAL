<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../model/job-model.php";

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../view/login-view.php"); exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = (int)($_POST['job_id'] ?? 0);
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
    if($salary_min !== null && $salary_max !== null && $salary_min > $salary_max) $errors['salary_range'] = 'Min salary cannot be greater than max salary';

    if(!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header("Location: ../view/jobs/edit-job-view.php?job_id=" . $job_id);
        exit();
    }

    $updated = updateJob($job_id, $employer_id, $category_id, $title, $description, $requirements, $benefits, $salary_min, $salary_max, $location, $job_type, $experience_level, $deadline, $status);

    if($updated) {
        $_SESSION['success'] = 'Job updated successfully!';
        header("Location: ../view/jobs/manage-jobs-view.php");
    } else {
        $_SESSION['errors'] = ['db_error' => 'Failed to update job'];
        header("Location: ../view/jobs/edit-job-view.php?job_id=" . $job_id);
    }
    exit();
}