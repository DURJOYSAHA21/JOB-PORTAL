<?php
session_start();
require_once("../db.php");
require_once("../Model/alert_model.php");
require_once("../Model/job_model.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../View/login_view.php");
    exit();
}

$userId = $_SESSION["user_id"];

// ============ DELETE ALERT ============
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $alertId = $_GET['id'] ?? 0;
    deleteAlert($alertId, $userId);
    $_SESSION["success"]["alert"] = "Alert deleted";
    header("Location: ../Controller/alerts_controller.php");
    exit();
}

// ============ CREATE ALERT ============
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $keyword = $_POST['keyword'] ?? '';
    $categoryId = $_POST['category_id'] ?? '';
    $location = $_POST['location'] ?? '';
    $jobType = $_POST['job_type'] ?? '';
    
    if (empty($keyword) && empty($categoryId) && empty($location) && empty($jobType)) {
        $_SESSION["error"]["alert"] = "Please set at least one preference";
    } else {
        createAlert($userId, $keyword, $categoryId, $location, $jobType);
        $_SESSION["success"]["alert"] = "Alert created successfully";
    }
    
    header("Location: ../Controller/alerts_controller.php");
    exit();
}

// ============ VIEW ALERTS ============
$alerts = getAlertsBySeeker($userId);
$categories = getAllCategories();
$notifications = getNotifications($userId, 10);
$unreadCount = countUnreadNotifications($userId);

$viewData = [
    'alerts' => $alerts,
    'categories' => $categories,
    'notifications' => $notifications,
    'unreadCount' => $unreadCount,
    'success' => $_SESSION["success"]["alert"] ?? null,
    'error' => $_SESSION["error"]["alert"] ?? null
];

unset($_SESSION["success"]["alert"], $_SESSION["error"]["alert"]);

require_once("../View/alerts_view.php");