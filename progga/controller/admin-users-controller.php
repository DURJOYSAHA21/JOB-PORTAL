<?php
session_start();
require_once __DIR__ . "/auth-check-controller.php";
require_once __DIR__ . "/../model/admin-model.php";
checkRole("admin");

$model = new AdminModel();
$allowedRoles = ["employer", "recruiter", "seeker"];
$role = $_GET["role"] ?? $_POST["role"] ?? "employer";
if (!in_array($role, $allowedRoles)) {
    $role = "employer";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $userId = (int)($_POST["user_id"] ?? 0);
    $reason = trim($_POST["reason"] ?? "");

    if ($userId <= 0) {
        $_SESSION["admin_error"] = "Invalid user selected.";
    } elseif ($action === "approve" && in_array($role, ["employer", "recruiter"])) {
        $_SESSION["admin_success"] = $model->approveUser($userId, $_SESSION["user_id"]) ? "Account approved." : "Approval failed.";
    } elseif ($action === "reject" && in_array($role, ["employer", "recruiter"])) {
        if ($reason === "") {
            $_SESSION["admin_error"] = "Rejection reason is required.";
        } else {
            $_SESSION["admin_success"] = $model->rejectUser($userId, $_SESSION["user_id"], $reason) ? "Account rejected with reason." : "Rejection failed.";
        }
    } elseif ($action === "suspend") {
        $_SESSION["admin_success"] = $model->setUserActive($userId, $_SESSION["user_id"], 0) ? "Account suspended/deactivated." : "Action failed.";
    } elseif ($action === "reactivate") {
        $_SESSION["admin_success"] = $model->setUserActive($userId, $_SESSION["user_id"], 1) ? "Account reactivated." : "Action failed.";
    }

    header("Location: admin-users-controller.php?role=" . urlencode($role));
    exit;
}

$keyword = trim($_GET["q"] ?? "");
$verification = trim($_GET["verification"] ?? "");
$users = $model->getUsersByRole($role, $keyword, $verification);
require_once __DIR__ . "/../view/admin-users-view.php";
