<?php
session_start();
require_once __DIR__ . "/auth-check-controller.php";
require_once __DIR__ . "/../model/admin-model.php";
checkRole("admin");
$model = new AdminModel();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $jobId = (int)($_POST["job_id"] ?? 0);

    if ($jobId <= 0) {
        $_SESSION["admin_error"] = "Invalid job selected.";
    } elseif ($action === "remove") {
        $_SESSION["admin_success"] = $model->removeJob($jobId) ? "Policy-violating job removed." : "Could not remove job.";
    } elseif ($action === "feature") {
        $_SESSION["admin_success"] = $model->setJobFeatured($jobId, 1) ? "Job marked as featured." : "Could not feature job.";
    } elseif ($action === "unfeature") {
        $_SESSION["admin_success"] = $model->setJobFeatured($jobId, 0) ? "Featured status removed." : "Could not update job.";
    }

    header("Location: admin-jobs-controller.php");
    exit;
}

$keyword = trim($_GET["q"] ?? "");
$status = trim($_GET["status"] ?? "");
$employerId = trim($_GET["employer_id"] ?? "");
$recruiterId = trim($_GET["recruiter_id"] ?? "");
$jobs = $model->getJobs($keyword, $status, $employerId, $recruiterId);
$employers = $model->getEmployers();
$recruiters = $model->getRecruiters();
require_once __DIR__ . "/../view/admin-jobs-view.php";
