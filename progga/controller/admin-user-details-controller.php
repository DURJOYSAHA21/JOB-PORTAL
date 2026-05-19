<?php
session_start();
require_once __DIR__ . "/auth-check-controller.php";
require_once __DIR__ . "/../model/admin-model.php";

checkRole("admin");

$model = new AdminModel();
$userId = (int)($_GET["id"] ?? 0);
$user = $userId > 0 ? $model->getUser($userId) : null;

if (!$user || $user["role"] === "admin") {
    $_SESSION["admin_error"] = "User was not found.";
    header("Location: admin-users-controller.php?role=employer");
    exit;
}

$profile = $model->getProfileForUser($userId, $user["role"]);
$actions = $model->getAdminActionsForUser($userId);

require_once __DIR__ . "/../view/admin-user-details-view.php";
