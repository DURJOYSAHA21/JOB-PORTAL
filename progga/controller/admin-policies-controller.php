<?php
session_start();
require_once __DIR__ . "/auth-check-controller.php";
require_once __DIR__ . "/../model/admin-model.php";
checkRole("admin");
$model = new AdminModel();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $maxJobs = trim($_POST["max_jobs_per_employer"] ?? "");
    $maxApps = trim($_POST["max_active_applications_per_seeker"] ?? "");
    $resumeDefault = trim($_POST["resume_visibility_default"] ?? "");

    if (!ctype_digit($maxJobs) || (int)$maxJobs < 1) {
        $_SESSION["admin_error"] = "Maximum job postings must be a positive number.";
    } elseif (!ctype_digit($maxApps) || (int)$maxApps < 1) {
        $_SESSION["admin_error"] = "Maximum active applications must be a positive number.";
    } elseif (!in_array($resumeDefault, ["private", "public"])) {
        $_SESSION["admin_error"] = "Resume visibility default must be private or public.";
    } else {
        $model->setPolicy("max_jobs_per_employer", $maxJobs);
        $model->setPolicy("max_active_applications_per_seeker", $maxApps);
        $model->setPolicy("resume_visibility_default", $resumeDefault);
        $_SESSION["admin_success"] = "Platform policies updated.";
    }

    header("Location: admin-policies-controller.php");
    exit;
}

$policies = $model->getPolicies();
require_once __DIR__ . "/../view/admin-policies-view.php";
