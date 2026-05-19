<?php
session_start();
require_once("../db.php");
require_once("../Model/user_model.php");
require_once("../Model/seeker_profile_model.php");

// Auth check
if (!isset($_SESSION["user_id"])) {
    header("Location: ../View/login_view.php");
    exit();
}

$userId = $_SESSION["user_id"];
$user = getUserById($userId);

if (!$user || $user["role"] !== "seeker") {
    header("Location: ../View/login_view.php");
    exit();
}

$profile = getSeekerProfileByUserId($userId);
$action = $_GET['action'] ?? '';

if (empty($profile['resume_path']) || !file_exists($profile['resume_path'])) {
    $_SESSION["error"]["resume"] = "No resume found.";
    header("Location: ../Controller/seeker_profile_controller.php");
    exit();
}

$resumePath = $profile['resume_path'];
$resumeName = basename($resumePath);

if ($action === 'view') {
    // View resume in browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $resumeName . '"');
    header('Content-Length: ' . filesize($resumePath));
    readfile($resumePath);
    exit();
    
} elseif ($action === 'download') {
    // Download resume
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $resumeName . '"');
    header('Content-Length: ' . filesize($resumePath));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    readfile($resumePath);
    exit();
    
} else {
    header("Location: ../Controller/seeker_profile_controller.php");
    exit();
}