<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../../model/recruiter/recruiter-client-model.php";
if(!isset($_SESSION['user']) || $_SESSION['user_role'] !== 'recruiter') { header("Location: ../../view/recruiter/recruiter-login-view.php"); exit(); }

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recruiter_id = (int)$_SESSION['user']['id'];
    $client_type = $_POST['client_type'] ?? 'registered';
    $employer_id = $client_type === 'registered' ? ($_POST['employer_id'] ?? null) : null;
    $company_name_override = $client_type === 'standalone' ? trim($_POST['company_name_override'] ?? '') : null;

    if($client_type === 'standalone' && empty($company_name_override)) {
        $_SESSION['errors'] = ['company' => 'Company name is required'];
        header("Location: ../../view/recruiter/client/add-client-view.php"); exit();
    }

    addRecruiterClient($recruiter_id, $employer_id, $company_name_override);
    $_SESSION['success'] = "Client added successfully!";
    header("Location: ../../view/recruiter/client/manage-clients-view.php");
    exit();
}