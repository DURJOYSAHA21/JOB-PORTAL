<?php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . "/../auth-check-controller.php";
require_once __DIR__ . "/../../model/admin-model.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$jobId = (int)($_POST["job_id"] ?? 0);
if ($jobId <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid job id"]);
    exit;
}

$model = new AdminModel();
$newValue = $model->toggleJobFeatured($jobId);
if ($newValue === false) {
    echo json_encode(["success" => false, "message" => "Could not update featured status"]);
    exit;
}

echo json_encode(["success" => true, "is_featured" => (int)$newValue]);
