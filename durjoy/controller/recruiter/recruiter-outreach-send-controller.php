<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../../model/recruiter/recruiter-outreach-model.php";
require_once "../../model/recruiter/recruiter-client-model.php";

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'recruiter') {
    header("Location: ../../view/recruiter/recruiter-login-view.php"); exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recruiter_user_id = (int)$_SESSION['user']['id'];
    $recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
    $seeker_id = (int)($_POST['seeker_id'] ?? 0);
    $job_id = (int)($_POST['job_id'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    $errors = [];

    if($seeker_id <= 0) $errors[] = 'Please select a seeker';
    if($job_id <= 0) $errors[] = 'Please select a job';
    if(empty($message)) $errors[] = 'Message cannot be empty';

    if(!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../../view/recruiter/outreach/send-outreach-view.php"); exit();
    }

    sendOutreach($recruiterProfileId, $seeker_id, $job_id, $message);
    $_SESSION['success'] = 'Outreach message sent successfully!';
    header("Location: ../../view/recruiter/outreach/outreach-list-view.php");
    exit();
}