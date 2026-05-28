<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "../../model/recruiter/recruiter-agency-info-model.php";

if(!isset($_SESSION['user'])) { header("Location: ../../view/recruiter/register/personal-info-view.php"); exit(); }

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agencyname = trim($_POST['agencyname']);
    $specialization = trim($_POST['specialization']);
    $description = trim($_POST['description']);
    $website = trim($_POST['website']);
    $id = (int)$_SESSION["user"]["id"];
    $errors = [];

    if($agencyname == "") { $errors["agencyname"] = "Agency name is required"; }
    if(!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) { $errors["website"] = "Invalid website URL"; }

    if(!empty($errors)) {
        $_SESSION["errors"] = $errors;
        $_SESSION["old_input"] = $_POST;
        header("Location: ../../view/recruiter/register/recruiter-agency-info-view.php");
        exit();
    }

    addRecruiterInfo($id, $agencyname, $specialization, $description, $website);

    if(recruiterInfoVerify($id)) {
        header("Location: ../../view/recruiter/recruiter-dashboard-view.php");
    } else {
        $_SESSION["is_verified"] = 0;
        header("Location: ../../view/recruiter/register/waiting-view.php");
    }
    exit();
}