<?php
session_start();
require_once("../db.php");
require_once("../Model/user_model.php");
require_once("../Model/seeker_profile_model.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($name === "" || $email === "" || $phone === "" || $password === "" || $confirm_password === "") {
        $_SESSION["error"]["register"] = "All fields are required.";
        header("Location: ../View/register_view.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error"]["register"] = "Please enter a valid email address.";
        header("Location: ../View/register_view.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION["error"]["register"] = "Passwords do not match.";
        header("Location: ../View/register_view.php");
        exit();
    }

    if (strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W_]/', $password)) {
        $_SESSION["error"]["register"] = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
        header("Location: ../View/register_view.php");
        exit();
    }

    if (isEmailTaken($email)) {
        $_SESSION["error"]["register"] = "This email is already registered.";
        header("Location: ../View/register_view.php");
        exit();
    }

    $created = createSeekerUser($name, $email, $phone, $password);

    if ($created) {
        $_SESSION["success"]["register"] = "Registration successful. Please login.";
        header("Location: ../View/login_view.php");
        exit();
    }

    $_SESSION["error"]["register"] = "Unable to create account. Please try again.";
    header("Location: ../View/register_view.php");
    exit();
}

header("Location: ../View/register_view.php");
exit();
?>