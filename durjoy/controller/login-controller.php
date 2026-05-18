<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../model/login-model.php";

unset($_SESSION['errors'], $_SESSION['old_input']);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    if(empty($email)) {
        $errors["email"] = "Email is required";
    }

    if(empty($password)) {
        $errors["password"] = "Password is required";
    }

    if(!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = ["email" => $email, "password" => $password];
        header("Location: ../view/login-view.php");
        exit();
    }

    $user = login($email);

    if(!$user || !password_verify($password, $user["password_hash"])) {
        $errors["login"] = "Invalid email or password";
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = ["email" => $email, "password" => $password];
        header("Location: ../view/login-view.php");
        exit();
    }

    if($user["role"] != "employer") {
        $errors["login"] = "Access denied. This portal is for employers only.";
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = ["email" => $email, "password" => $password];
        header("Location: ../view/login-view.php");
        exit();
    }

    if(!$user["is_active"]) {
        $errors["login"] = "Your account has been suspended.";
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = ["email" => $email, "password" => $password];
        header("Location: ../view/login-view.php");
        exit();
    }

    $_SESSION['user']        = $user;
    $_SESSION['user_role']   = $user["role"];
    $_SESSION['is_verified'] = (int)$user["is_verified"];
    $_SESSION['success']     = "Login successful.";

    if($user["is_verified"] == 1) {
        header("Location: ../view/dashboard-view.php");
        exit();
    } else {
        header("Location: ../view/register/waiting-view.php");
        exit();
    }
}