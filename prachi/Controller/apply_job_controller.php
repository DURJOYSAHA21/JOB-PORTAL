<?php
session_start();
require_once("../db.php");
require_once("../Model/job_model.php");
require_once("../Model/seeker_profile_model.php");

// ============ AUTH CHECK ============
if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"]["auth"] = "Please login to apply";
    header("Location: ../View/login_view.php");
    exit();
}

$userId = $_SESSION["user_id"];
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

// Get seeker profile
$profile = getSeekerProfileByUserId($userId);
// ============ GET REQUEST - Show Apply Form ============
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    
    // Check for existing application
    $existingApp = getUserApplication($jobId, $userId);
    
    if ($existingApp) {
        // Block if submitted, reviewed, shortlisted, or interview
        $blockedStatuses = ['submitted', 'reviewed', 'shortlisted', 'interview'];
        
        if (in_array($existingApp['status'], $blockedStatuses)) {
            $_SESSION["error"]["apply"] = "You have already applied for this job.";
            header("Location: ../Controller/job_details_controller.php?id=" . $jobId);
            exit();
        }
        // Allow if withdrawn or rejected - they can apply again
    }
    
    $viewData = [
        'job' => $job,
        'profile' => $profile,
        'errors' => $_SESSION["error"] ?? [],
        'oldInput' => $_SESSION["old_input"] ?? null
    ];
    
    unset($_SESSION["error"], $_SESSION["old_input"]);
    
    require_once("../View/apply_job_view.php");
    exit();
}

// ============ POST REQUEST - Submit Application ============
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $errors = [];
    
        // Check duplicate again
        $existingApp = getUserApplication($jobId, $userId);
        if ($existingApp && in_array($existingApp['status'], ['submitted', 'reviewed', 'shortlisted', 'interview'])) {
            $_SESSION["error"]["apply"] = "You have already applied for this job.";
            header("Location: ../Controller/job_details_controller.php?id=" . $jobId);
            exit();
        }
    
    $coverLetter = trim($_POST['cover_letter'] ?? '');
    $useExistingResume = isset($_POST['use_existing_resume']);
    $resumePath = '';
    
    // Validate cover letter
    if (empty($coverLetter)) {
        $errors['cover_letter'] = "Cover letter is required";
    } elseif (strlen($coverLetter) < 20) {
        $errors['cover_letter'] = "Cover letter must be at least 20 characters";
    }
    
    // Handle resume
    if ($useExistingResume) {
        // Use resume from profile
        if (empty($profile['resume_path']) || !file_exists($profile['resume_path'])) {
            $errors['resume'] = "No resume found in your profile. Please upload one.";
        } else {
            $resumePath = $profile['resume_path'];
        }
    } else {
        // Upload new resume
        if (!isset($_FILES['resume']) || $_FILES['resume']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors['resume'] = "Please upload your resume";
        } else {
            $resume = $_FILES['resume'];
            
            if ($resume['size'] > 5000000) {
                $errors['resume'] = "Resume must be less than 5MB";
            }
            
            $ext = strtolower(pathinfo($resume['name'], PATHINFO_EXTENSION));
            if ($ext != 'pdf') {
                $errors['resume'] = "Only PDF files are allowed";
            }
            
            if (!isset($errors['resume'])) {
                $uploadDir = "../uploads/resumes/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $resumePath = $uploadDir . time() . "_" . $userId . "_" . basename($resume['name']);
                if (!move_uploaded_file($resume['tmp_name'], $resumePath)) {
                    $errors['resume'] = "Failed to upload resume";
                }
            }
        }
    }
    
    if (!empty($errors)) {
        $_SESSION["error"] = $errors;
        $_SESSION["old_input"] = $_POST;
        header("Location: ../Controller/apply_job_controller.php?id=" . $jobId);
        exit();
    }
    
    // Submit application
    $result = submitApplication($jobId, $userId, $coverLetter, $resumePath);
    
    if ($result) {
        $_SESSION["success"]["apply"] = "Application submitted successfully!";
        header("Location: ../Controller/job_details_controller.php?id=" . $jobId);
    } else {
        $_SESSION["error"]["apply"] = "Failed to submit application. Please try again.";
        header("Location: ../Controller/apply_job_controller.php?id=" . $jobId);
    }
    exit();
}