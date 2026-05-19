<?php
session_start();
require_once("../db.php");
require_once("../Model/job_model.php");

// Auth check
if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"]["save"] = "Please login to save jobs";
    header("Location: ../View/login_view.php");
    exit();
}

$userId = $_SESSION["user_id"];

// ============ SAVE/UNSAVE ACTION ============
if (isset($_GET['action'])) {
    $jobId = $_GET['job_id'] ?? 0;
    $action = $_GET['action'];
    
    if (!empty($jobId)) {
        if ($action === 'save') {
            if (isJobSaved($jobId, $userId)) {
                $_SESSION["error"]["save"] = "Job already saved";
            } else {
                saveJob($jobId, $userId);
                $_SESSION["success"]["save"] = "Job saved successfully";
            }
        } elseif ($action === 'unsave') {
            unsaveJob($jobId, $userId);
            $_SESSION["success"]["save"] = "Job removed from bookmarks";
        }
    }
    
    // Redirect back to previous page
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../Controller/browse_jobs_controller.php';
    header("Location: " . $redirect);
    exit();
}

// ============ VIEW SAVED JOBS ============
$savedJobs = getSavedJobs($userId);

$viewData = [
    'savedJobs' => $savedJobs,
    'success' => $_SESSION["success"]["save"] ?? null
];

unset($_SESSION["success"]["save"]);

require_once("../View/saved_jobs_view.php");