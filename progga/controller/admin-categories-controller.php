<?php
session_start();
require_once __DIR__ . "/auth-check-controller.php";
require_once __DIR__ . "/../model/admin-model.php";
checkRole("admin");
$model = new AdminModel();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $id = (int)($_POST["id"] ?? 0);
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($action === "create") {
        if ($name === "") {
            $_SESSION["admin_error"] = "Category name is required.";
        } else {
            $_SESSION["admin_success"] = $model->createCategory($name, $description) ? "Category added." : "Could not add category. It may already exist.";
        }
    } elseif ($action === "rename") {
        if ($id <= 0 || $name === "") {
            $_SESSION["admin_error"] = "Valid category and name are required.";
        } else {
            $_SESSION["admin_success"] = $model->renameCategory($id, $name, $description) ? "Category updated." : "Could not update category.";
        }
    } elseif ($action === "delete") {
        $message = "";
        if ($id <= 0) {
            $_SESSION["admin_error"] = "Invalid category selected.";
        } elseif ($model->deleteCategory($id, $message)) {
            $_SESSION["admin_success"] = "Category deleted.";
        } else {
            $_SESSION["admin_error"] = $message ?: "Could not delete category.";
        }
    }

    header("Location: admin-categories-controller.php");
    exit;
}

$categories = $model->getCategories();
require_once __DIR__ . "/../view/admin-categories-view.php";
