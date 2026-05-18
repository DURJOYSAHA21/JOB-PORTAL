<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../model/company-info-model.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyname = trim($_POST['companyname']);
    $industry    = trim($_POST['industry']);
    $companysize = isset($_POST['companysize']) ? trim($_POST['companysize']) : "";
    $description = trim($_POST['description']);
    $website     = trim($_POST['website']);
    $address     = trim($_POST['address']);
    $id          = (int)$_SESSION["user"]["id"];

    $errors = [];

    if($companyname == "") {
        $errors["companyname"] = "Company name is required";
    }

    if(!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $errors["website"] = "Invalid website URL";
    }

    $logo_path = "";
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];

    if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $fileExtension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if(!in_array($fileExtension, $allowedExtensions)) {
            $errors["logo"] = "Only JPG, JPEG, PNG, and GIF files are allowed";
        } elseif($_FILES['logo']['size'] > 2 * 1024 * 1024) {
            $errors["logo"] = "Logo must be less than 2MB";
        } else {
            $upload_dir = "../uploads/logos/";
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $filename  = "logo_" . $id . "_" . uniqid() . "." . $fileExtension;
            move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $filename);
            $logo_path = $filename;
        }
    }

    if(!empty($errors)) {
        $_SESSION["errors"]    = $errors;
        $_SESSION["old_input"] = $_POST;
        header("Location: ../view/register/company-info-view.php");
        exit();
    }

    updateCompanyInfo($id, $companyname, $industry, $companysize, $description, $website, $address, $logo_path);

    if(companyinfoverify($id)) {
        header("Location: ../view/dashboard-view.php");
        exit();
    } else {
        $_SESSION["is_verified"] = 0;
        header("Location: ../view/register/waiting-view.php");
        exit();
    }
}