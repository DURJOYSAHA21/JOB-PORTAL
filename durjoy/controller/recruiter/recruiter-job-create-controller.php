<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../../model/job-model.php";
require_once "../../model/recruiter/recruiter-client-model.php";

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'recruiter') {
    header("Location: ../../view/recruiter/recruiter-login-view.php"); exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recruiter_user_id = (int)$_SESSION['user']['id'];
    $recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
    $client_id = (int)($_POST['client_id'] ?? 0);
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
    if($client_id <= 0) $errors['client_id'] = 'Please select a client company';
    if(empty($title)) $errors['title'] = 'Job title is required';
    if($category_id <= 0) $errors['category_id'] = 'Please select a category';
    if(empty($description)) $errors['description'] = 'Job description is required';
    if(empty($location)) $errors['location'] = 'Location is required';
    if(empty($deadline)) $errors['deadline'] = 'Deadline is required';
    if($salary_min !== null && $salary_max !== null && $salary_min > $salary_max) $errors['salary_range'] = 'Min cannot be greater than max';

    if(!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header("Location: ../../view/recruiter/job/post-job-view.php"); exit();
    }

    // Get employer_id from client record
    $client = getClientById($client_id);
    $employer_id = $employer_id = getClientEmployerUserId($client_id);

 

    $job_id = createJob($employer_id, $category_id, $title, $description, $requirements,
                        $benefits, $salary_min, $salary_max, $location, $job_type,
                        $experience_level, $deadline, $status, $recruiterProfileId);

    if($job_id) {
        $_SESSION['success'] = ($status === 'active') ? 'Job posted!' : 'Job saved as draft!';
        header("Location: ../../view/recruiter/job/manage-jobs-view.php");
    } else {
        $_SESSION['errors'] = ['db' => 'Failed to create job'];
        header("Location: ../../view/recruiter/job/post-job-view.php");
    }
    exit();
}