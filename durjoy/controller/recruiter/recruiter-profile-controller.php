<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../../model/recruiter/recruiter-profile-model.php";
if(!isset($_SESSION['user']) || $_SESSION['user_role'] !== 'recruiter') { header("Location: ../../view/recruiter/recruiter-login-view.php"); exit(); }

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agencyname = trim($_POST['agencyname']);
    $specialization = trim($_POST['specialization']);
    $description = trim($_POST['description']);
    $website = trim($_POST['website']);
    $id = (int)$_SESSION['user']['id'];
    $errors = [];

    if($agencyname == "") { $errors['agencyname'] = "Agency name is required"; }
    if(!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) { $errors['website'] = "Invalid website URL"; }

    if(!empty($errors)) { $_SESSION['errors'] = $errors; header("Location: ../../view/recruiter/recruiter-profile-view.php"); exit(); }

    updateRecruiterProfile($id, $agencyname, $specialization, $description, $website);
    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: ../../view/recruiter/recruiter-profile-view.php");
    exit();
}