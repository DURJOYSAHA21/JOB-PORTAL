<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "../../model/recruiter/recruiter-personal-info-model.php";

function redirectWithError($errors, $oldInput) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old_input'] = $oldInput;
    header("Location: ../../view/recruiter/register/personal-info-view.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);
    $cpassword = trim($_POST["confirm-password"]);

    $oldInput = ["fullname" => $fullname, "email" => $email, "phone" => $phone, "password" => $password, "confirm-password" => $cpassword];
    $errors = [];

    if(empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($cpassword)) { $errors["register"] = "All fields are required"; }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors["email"] = "Please provide a valid email address."; }
    if($password !== $cpassword) { $errors["password"] = "Passwords do not match."; }
    if(strlen($password) < 8) { $errors["password"] = "Password must be at least 8 characters."; }
    elseif(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) { $errors["password"] = "Password must contain uppercase, lowercase, number, and special character."; }

    if(!empty($errors)) { redirectWithError($errors, $oldInput); }

    unset($_SESSION['user']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insert_id = registerRecruiter($fullname, $email, $phone, $hashedPassword);

    if($insert_id) {
        $_SESSION['user'] = ["fullname" => $fullname, "email" => $email, "id" => $insert_id, "role" => "recruiter"];
        $_SESSION['user_role'] = "recruiter";
        header("Location: ../../view/recruiter/register/recruiter-agency-info-view.php");
    } else {
        $errors["register"] = "Registration failed.";
        redirectWithError($errors, $oldInput);
    }
    exit();
}
