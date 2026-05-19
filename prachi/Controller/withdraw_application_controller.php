<?php
session_start();
require_once("../db.php");
require_once("../Model/job_model.php");

// Auth check
if (!isset($_SESSION["user_id"])) {
    header("Location: ../View/login_view.php");
    exit();
}

$userId = $_SESSION["user_id"];
$jobId = $_GET['job_id'] ?? 0;

if (empty($jobId)) {
    header("Location: ../Controller/browse_jobs_controller.php");
    exit();
}

// Get fresh application from DB
$application = getUserApplication($jobId, $userId);

if (!$application) {
    $_SESSION["error"]["withdraw"] = "No application found.";
    header("Location: ../Controller/job_details_controller.php?id=" . $jobId);
    exit();
}

// Check status - ONLY 'submitted' can be withdrawn
if ($application['status'] !== 'submitted') {
    $statusMessages = [
        'reviewed' => 'reviewed by employer',
        'shortlisted' => 'shortlisted',
        'interview' => 'in interview stage',
        'rejected' => 'rejected',
        'withdrawn' => 'already withdrawn'
    ];
    $msg = $statusMessages[$application['status']] ?? $application['status'];
    $_SESSION["error"]["withdraw"] = "Cannot withdraw. Application has been " . $msg . ".";
    header("Location: ../Controller/job_details_controller.php?id=" . $jobId);
    exit();
}

// Withdraw
$result = withdrawApplication($application['id'], $userId);

if ($result) {
    $_SESSION["success"]["withdraw"] = "Application withdrawn successfully.";
} else {
    $_SESSION["error"]["withdraw"] = "Failed to withdraw application.";
}

header("Location: ../Controller/job_details_controller.php?id=" . $jobId);
exit();