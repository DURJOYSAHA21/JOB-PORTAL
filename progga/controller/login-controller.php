<?php
session_start();

require_once __DIR__ . "/../model/user-model.php";

function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
    return $data;
}

$errors = [];
$email = "";
$password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_POST["email"])) {
        $errors["email"] = "Email is required.";
    } else {
        $email = sanitizeInput($_POST["email"]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid email format.";
        }
    }

    if (empty($_POST["password"])) {
        $errors["password"] = "Password is required.";
    } else {
        $password = $_POST["password"];
    }

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        $_SESSION["old_email"] = $email;
        header("Location: ../view/login-view.php");
        exit;
    }

    $userModel = new UserModel();
    $user = $userModel->findUserByEmail($email);

    if ($user && password_verify($password, $user["password_hash"])) {

        if ((int)$user["is_active"] !== 1) {
            $_SESSION["errors"]["login"] = "Your account is not active.";
            $_SESSION["old_email"] = $email;
            header("Location: ../view/login-view.php");
            exit;
        }

        if (in_array($user["role"], ["employer", "recruiter"]) && (int)$user["is_verified"] !== 1) {
            $_SESSION["errors"]["login"] = "Your account is waiting for admin approval.";
            $_SESSION["old_email"] = $email;
            header("Location: ../view/login-view.php");
            exit;
        }

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] === "admin") {
            header("Location: ../controller/admin-dashboard-controller.php");
            exit;
        }

        if ($user["role"] === "employer") {
            header("Location: ../view/employer-dashboard-view.php");
            exit;
        }

        if ($user["role"] === "seeker") {
            header("Location: ../view/seeker-dashboard-view.php");
            exit;
        }

        if ($user["role"] === "recruiter") {
            header("Location: ../view/recruiter-dashboard-view.php");
            exit;
        }

        $_SESSION["errors"]["login"] = "Invalid user role.";
        header("Location: ../view/login-view.php");
        exit;
    }

    $_SESSION["errors"]["login"] = "Invalid email or password.";
    $_SESSION["old_email"] = $email;
    header("Location: ../view/login-view.php");
    exit;
}

header("Location: ../view/login-view.php");
exit;
