<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../model/job-model.php";

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../view/login-view.php"); exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = (int)($_POST['job_id'] ?? 0);
    $employer_id = (int)$_SESSION['user']['id'];

    if(repostJob($job_id, $employer_id)) {
        $_SESSION['success'] = 'Job reposted successfully!';
    } else {
        $_SESSION['errors'] = ['repost' => 'Failed to repost job'];
    }
    header("Location: ../view/jobs/manage-jobs-view.php");
    exit();
}