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

// Get all applications
$applications = getSeekerApplications($userId);

$viewData = [
    'applications' => $applications,
    'success' => $_SESSION["success"]["withdraw"] ?? null,
    'error' => $_SESSION["error"]["withdraw"] ?? null
];

unset($_SESSION["success"]["withdraw"], $_SESSION["error"]["withdraw"]);

require_once("../View/my_applications_view.php");