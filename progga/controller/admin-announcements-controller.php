<?php
session_start();
require_once __DIR__ . "/auth-check-controller.php";
require_once __DIR__ . "/../model/admin-model.php";
checkRole("admin");
$model = new AdminModel();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    if ($action === "create") {
        $title = trim($_POST["title"] ?? "");
        $body = trim($_POST["body"] ?? "");
        if ($title === "" || $body === "") {
            $_SESSION["admin_error"] = "Announcement title and body are required.";
        } else {
            $_SESSION["admin_success"] = $model->createAnnouncement($title, $body, $_SESSION["user_id"]) ? "Announcement posted." : "Could not post announcement.";
        }
    } elseif ($action === "delete") {
        $id = (int)($_POST["id"] ?? 0);
        $_SESSION["admin_success"] = $model->deleteAnnouncement($id) ? "Announcement deleted." : "Could not delete announcement.";
    }

    header("Location: admin-announcements-controller.php");
    exit;
}

$announcements = $model->getAnnouncements();
require_once __DIR__ . "/../view/admin-announcements-view.php";
