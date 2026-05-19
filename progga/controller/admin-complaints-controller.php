<?php
session_start();
require_once __DIR__ . "/auth-check-controller.php";
require_once __DIR__ . "/../model/admin-model.php";
checkRole("admin");
$model = new AdminModel();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int)($_POST["complaint_id"] ?? 0);
    $adminNote = trim($_POST["admin_note"] ?? "");

    if ($id <= 0 || $adminNote === "") {
        $_SESSION["admin_error"] = "Complaint and resolution note are required.";
    } else {
        $_SESSION["admin_success"] = $model->resolveComplaint($id, $adminNote) ? "Complaint resolved." : "Could not resolve complaint.";
    }

    header("Location: admin-complaints-controller.php");
    exit;
}

$status = trim($_GET["status"] ?? "");
$complaints = $model->getComplaints($status);
require_once __DIR__ . "/../view/admin-complaints-view.php";
