<?php
session_start();
require_once("../db.php");
require_once("../Model/user_model.php");
require_once("../Model/seeker_profile_model.php");
require_once("../Model/job_model.php");

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
$profileComplete = ($profile && !empty($profile['headline']) && !empty($profile['summary']) && !empty($profile['skills']));
$totalActiveJobs = countActiveJobs();

$viewData = [
    'user' => $user,
    'profile' => $profile,
    'profileComplete' => $profileComplete,
    'totalActiveJobs' => $totalActiveJobs
];

require_once("../View/seeker_dashboard_view.php");