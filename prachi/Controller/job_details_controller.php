<?php
session_start();
require_once("../db.php");
require_once("../Model/job_model.php");

$jobId = $_GET['id'] ?? 0;

if (empty($jobId)) {
    header("Location: ../Controller/browse_jobs_controller.php");
    exit();
}

$job = getJobById($jobId);

if (!$job) {
    header("Location: ../Controller/browse_jobs_controller.php");
    exit();
}

// Get fresh application data
$application = null;
if (isset($_SESSION['user_id'])) {
    $application = getUserApplication($jobId, $_SESSION['user_id']);
}

// Get messages
$successMsg = $_SESSION["success"]["withdraw"] ?? $_SESSION["success"]["apply"] ?? null;
$errorMsg = $_SESSION["error"]["withdraw"] ?? $_SESSION["error"]["apply"] ?? null;

// Clear messages
unset($_SESSION["success"]["withdraw"], $_SESSION["success"]["apply"]);
unset($_SESSION["error"]["withdraw"], $_SESSION["error"]["apply"]);

$viewData = [
    'job' => $job,
    'application' => $application,
    'success' => $successMsg,
    'error' => $errorMsg
];

require_once("../View/job_details_view.php");