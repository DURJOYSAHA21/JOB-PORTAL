<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../model/complaint-model.php";

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../view/login-view.php"); exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitter_id = (int)$_SESSION['user']['id'];
    $subject_id = (int)($_POST['subject_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $errors = [];

    if($subject_id <= 0) $errors['subject'] = 'Please select a person';
    if(empty($description)) $errors['description'] = 'Please describe your complaint';
    if(strlen($description) < 20) $errors['description'] = 'Description must be at least 20 characters';
    if($subject_id === $submitter_id) $errors['subject'] = 'You cannot complain about yourself';

    if(!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header("Location: ../view/jobs/submit-complaint-view.php"); exit();
    }

    $submitted = submitComplaint($submitter_id, $subject_id, $description);

    if($submitted) {
        $_SESSION['success'] = 'Complaint submitted successfully. Admin will review it shortly.';
        header("Location: ../view/jobs/my-complaints-view.php");
    } else {
        $_SESSION['errors'] = ['submit' => 'Failed to submit complaint'];
        $_SESSION['old_input'] = $_POST;
        header("Location: ../view/jobs/submit-complaint-view.php");
    }
    exit();
}