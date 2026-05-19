<?php
session_start();
require_once("../db.php");
require_once("../Model/alert_model.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../View/login_view.php");
    exit();
}

$userId = $_SESSION["user_id"];
$action = $_GET['action'] ?? '';

if ($action === 'mark_read') {
    $notifId = $_GET['id'] ?? 0;
    markNotificationRead($notifId, $userId);
} elseif ($action === 'mark_all_read') {
    markAllNotificationsRead($userId);
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '../Controller/seeker_dashboard_controller.php';
header("Location: " . $redirect);
exit();