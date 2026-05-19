<?php
session_start();
require_once("../db.php");
require_once("../Model/complaint_model.php");
require_once("../Model/job_model.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../View/login_view.php");
    exit();
}

$userId = $_SESSION["user_id"];
$action = $_GET['action'] ?? '';

// ============ SUBMIT COMPLAINT ============
if ($action === 'submit' || $_SERVER["REQUEST_METHOD"] === "POST") {
    $jobId = $_GET['job_id'] ?? 0;
    $job = getJobById($jobId);
    
    if (!$job) {
        header("Location: ../Controller/browse_jobs_controller.php");
        exit();
    }
    
    $subjectId = $job['employer_id'] ?? $job['recruiter_id'] ?? 0;
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $description = trim($_POST['description'] ?? '');
        
        if (empty($description)) {
            $_SESSION["error"]["complaint"] = "Please describe your complaint";
        } elseif (strlen($description) < 20) {
            $_SESSION["error"]["complaint"] = "Complaint must be at least 20 characters";
        } else {
            $result = submitComplaint($userId, $subjectId, $description);
            if ($result) {
                $_SESSION["success"]["complaint"] = "Complaint submitted. Admin will review.";
                header("Location: ../Controller/job_details_controller.php?id=" . $jobId);
                exit();
            }
        }
        header("Location: ../Controller/complaint_controller.php?action=submit&job_id=" . $jobId);
        exit();
    }
    
    $viewData = [
        'page' => 'submit',
        'job' => $job,
        'errors' => $_SESSION["error"] ?? [],
        'oldInput' => $_SESSION["old_input"] ?? null
    ];
    unset($_SESSION["error"], $_SESSION["old_input"]);
    
    require_once("../View/complaint_view.php");
    exit();
}

// ============ VIEW MY COMPLAINTS ============
$complaints = getUserComplaints($userId);

$viewData = [
    'page' => 'list',
    'complaints' => $complaints
];

require_once("../View/complaint_view.php");