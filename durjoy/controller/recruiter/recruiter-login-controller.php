<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "../../model/recruiter/recruiter-login-model.php";
unset($_SESSION['errors'], $_SESSION['old_input']);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    if(empty($email)) { $errors["email"] = "Email is required"; }
    if(empty($password)) { $errors["password"] = "Password is required"; }

    if(!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = ["email" => $email];
        header("Location: ../../view/recruiter/recruiter-login-view.php");
        exit();
    }

    $user = recruiterLogin($email);

    if(!$user || !password_verify($password, $user["password_hash"])) {
        $errors["login"] = "Invalid email or password";
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = ["email" => $email];
        header("Location: ../../view/recruiter/recruiter-login-view.php");
        exit();
    }

    if($user["role"] != "recruiter") {
        $errors["login"] = "Access denied. This portal is for recruiters only.";
        $_SESSION['errors'] = $errors;
        header("Location: ../../view/recruiter/recruiter-login-view.php");
        exit();
    }

    if(!$user["is_active"]) {
        $errors["login"] = "Your account has been suspended.";
        $_SESSION['errors'] = $errors;
        header("Location: ../../view/recruiter/recruiter-login-view.php");
        exit();
    }

    // Set session
    $_SESSION['user'] = $user;
    $_SESSION['user_role'] = $user["role"];
    $_SESSION['is_verified'] = (int)$user["is_verified"];
    $_SESSION['success'] = "Login successful.";

    if($user["is_verified"] == 1) {
        header("Location: ../../view/recruiter/recruiter-dashboard-view.php");
    } else {
        header("Location: ../../view/recruiter/register/waiting-view.php");
    }
    exit();
}