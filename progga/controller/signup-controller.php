<?php
session_start();

require_once __DIR__ . "/../model/user-model.php";

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $errors = [];

    $name = sanitizeInput($_POST["name"] ?? "");
    $email = sanitizeInput($_POST["email"] ?? "");
    $phone = sanitizeInput($_POST["phone"] ?? "");
    $role = sanitizeInput($_POST["role"] ?? "");

    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    $_SESSION["old"]["signup"] = [
        "name" => $name,
        "email" => $email,
        "phone" => $phone,
        "role" => $role
    ];

    $allowed_roles = ["seeker", "employer", "recruiter"];

    // Validate name
    if (empty($name)) {
        $errors["name"] = "Full name is required.";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $errors["name"] = "Name can contain only letters and spaces.";
    }

    // Validate email
    if (empty($email)) {
        $errors["email"] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    }

    // Validate phone
    if (empty($phone)) {
        $errors["phone"] = "Phone number is required.";
    } elseif (!preg_match("/^01[0-9]{9}$/", $phone)) {
        $errors["phone"] = "Phone number must be a valid 11 digit Bangladeshi number.";
    }

    // Validate role
    if (empty($role)) {
        $errors["role"] = "Please select a role.";
    } elseif (!in_array($role, $allowed_roles)) {
        $errors["role"] = "Invalid role selected.";
    }

    // Validate password
    if (empty($password)) {
        $errors["password"] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors["password"] = "Password must be at least 6 characters.";
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $errors["confirm_password"] = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $errors["confirm_password"] = "Passwords do not match.";
    }

    // Check email already exists
    if (!isset($errors["email"])) {
        $userModel = new UserModel();

        if ($userModel->emailExists($email)) {
            $errors["email"] = "Email already exists.";
        }
    }

    // If validation errors exist, redirect back
    if (!empty($errors)) {
        $_SESSION["errors"]["signup"] = $errors;
        header("Location: ../view/signup-view.php");
        exit;
    }

    // Save user
    $userModel = new UserModel();

    if ($userModel->createUser($name, $email, $phone, $password, $role)) {
        unset($_SESSION["old"]["signup"]);
        unset($_SESSION["errors"]["signup"]);

        if ($role === "seeker") {
            $_SESSION["success"] = "Registration successful. You can now login.";
        } else {
            $_SESSION["success"] = "Registration successful. Please wait for admin approval.";
        }

        header("Location: ../view/login-view.php");
        exit;
    }

    $_SESSION["errors"]["signup"]["general"] = "Registration failed. Please try again.";
    header("Location: ../view/signup-view.php");
    exit;
}

header("Location: ../view/signup-view.php");
exit;