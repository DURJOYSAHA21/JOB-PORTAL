<?php
session_start();
require_once __DIR__ . "/auth-check-controller.php";
require_once __DIR__ . "/../model/admin-model.php";
checkRole("admin");
$model = new AdminModel();
$month = trim($_GET["month"] ?? date("Y-m"));
if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
    $month = date("Y-m");
}
$report = $model->monthlyReport($month);

if (isset($_GET["export"]) && $_GET["export"] === "csv") {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=monthly-platform-summary-" . $month . ".csv");
    $out = fopen("php://output", "w");
    fputcsv($out, ["Metric", "Value"]);
    fputcsv($out, ["Month", $report["month"]]);
    fputcsv($out, ["New Users", $report["new_users"]]);
    foreach ($report["new_users_by_role"] as $row) {
        fputcsv($out, ["New Users - " . $row["role"], $row["total"]]);
    }
    fputcsv($out, ["Jobs Posted", $report["jobs_posted"]]);
    fputcsv($out, ["Applications", $report["applications"]]);
    fputcsv($out, ["Top Category", $report["top_category"]["name"] . " (" . $report["top_category"]["total"] . ")"]);
    fputcsv($out, ["Top Employer", $report["top_employer"]["name"] . " (" . $report["top_employer"]["total"] . ")"]);
    fputcsv($out, ["Most Active Recruiter", $report["most_active_recruiter"]["name"] . " (" . $report["most_active_recruiter"]["total"] . ")"]);
    fputcsv($out, ["Complaints Opened", $report["complaints_opened"]]);
    fputcsv($out, ["Complaints Resolved", $report["complaints_resolved"]]);
    fclose($out);
    exit;
}

require_once __DIR__ . "/../view/admin-monthly-report-view.php";
