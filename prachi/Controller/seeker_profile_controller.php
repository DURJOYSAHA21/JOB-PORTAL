<?php
session_start();
require_once("../db.php");
require_once("../Model/user_model.php");
require_once("../Model/seeker_profile_model.php");

// ============ AUTH CHECK ============
if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"]["auth"] = "Please login to access your profile";
    header("Location: ../View/login_view.php");
    exit();
}

$userId = $_SESSION["user_id"];
$user = getUserById($userId);

// Role check
if (!$user || $user["role"] !== "seeker") {
    $_SESSION["error"]["auth"] = "Access denied. Seeker account required.";
    header("Location: ../View/login_view.php");
    exit();
}

// ============ UPLOAD DIRECTORIES ============
$resumeUploadDir = "../uploads/resumes/";
$profilePicUploadDir = "../uploads/profile_pics/";

// Create directories if not exist
if (!is_dir($resumeUploadDir)) {
    mkdir($resumeUploadDir, 0777, true);
}
if (!is_dir($profilePicUploadDir)) {
    mkdir($profilePicUploadDir, 0777, true);
}

// ============ GET REQUEST - Load View ============
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $profile = getSeekerProfileByUserId($userId);

    $viewData = [
        'user' => $user,
        'profile' => $profile,
        'errors' => $_SESSION["error"] ?? [],
        'success' => $_SESSION["success"]["profile"] ?? null,
        'successPic' => $_SESSION["success"]["profile_pic"] ?? null,
        'successResume' => $_SESSION["success"]["resume"] ?? null,
        'oldInput' => $_SESSION["old_input"] ?? null
    ];

    unset($_SESSION["error"], $_SESSION["success"], $_SESSION["old_input"]);

    require_once("../View/seeker_profile_view.php");
    exit();
}

// ============ POST REQUEST - Form Submit ============
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $errors = [];
    $profilePicName = $user['profile_pic']; // Keep existing
    $resumeName = null;
    
    // Get existing profile
    $existingProfile = getSeekerProfileByUserId($userId);
    if ($existingProfile) {
        $resumeName = $existingProfile['resume_path'];
    }
    
    // ============ HANDLE PROFILE PICTURE UPLOAD ============
    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $profilePic = $_FILES["profile_pic"];
        
        // Check if file is actual image
        $check = getimagesize($profilePic["tmp_name"]);
        if ($check === false) {
            $errors["profile_pic"] = "File is not an image.";
        }
        
        // Check file size (limit to 2MB)
        if ($profilePic["size"] > 2000000) {
            $errors["profile_pic"] = "Profile picture must be less than 2MB.";
        }
        
        // Allow certain file formats
        $imageFileType = strtolower(pathinfo($profilePic["name"], PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            $errors["profile_pic"] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
        
        if (!isset($errors["profile_pic"])) {
            // Delete old profile picture
            if (!empty($user['profile_pic']) && file_exists($user['profile_pic'])) {
                unlink($user['profile_pic']);
            }
            
            // Upload new profile picture
            $profilePicName = $profilePicUploadDir . time() . "_" . basename($profilePic["name"]);
            if (move_uploaded_file($profilePic["tmp_name"], $profilePicName)) {
                updateUserProfilePic($userId, $profilePicName);
                $_SESSION["success"]["profile_pic"] = "Profile picture updated successfully!";
                $user['profile_pic'] = $profilePicName;
            } else {
                $errors["profile_pic"] = "Sorry, there was an error uploading your profile picture.";
            }
        }
    }
    
    // ============ HANDLE RESUME UPLOAD ============
    if (isset($_FILES["resume"]) && $_FILES["resume"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $resume = $_FILES["resume"];
        
        // Check file size (limit to 5MB)
        if ($resume["size"] > 5000000) {
            $errors["resume"] = "Resume file must be less than 5MB.";
        }
        
        // Allow only PDF
        $resumeFileType = strtolower(pathinfo($resume["name"], PATHINFO_EXTENSION));
        if ($resumeFileType != "pdf") {
            $errors["resume"] = "Only PDF files are allowed for resume.";
        }
        
        if (!isset($errors["resume"])) {
            // Delete old resume
            if (!empty($existingProfile['resume_path']) && file_exists($existingProfile['resume_path'])) {
                unlink($existingProfile['resume_path']);
            }
            
            // Upload new resume
            $resumeName = $resumeUploadDir . time() . "_" . basename($resume["name"]);
            if (move_uploaded_file($resume["tmp_name"], $resumeName)) {
                $_SESSION["success"]["resume"] = "Resume uploaded successfully!";
            } else {
                $errors["resume"] = "Sorry, there was an error uploading your resume.";
            }
        }
    }
    
    // ============ VALIDATE PROFILE DATA ============
    $profileErrors = validateProfileData($_POST);
    $errors = array_merge($errors, $profileErrors);
    
    if (!empty($errors)) {
        $_SESSION["error"] = $errors;
        $_SESSION["old_input"] = $_POST;
        header("Location: ../Controller/seeker_profile_controller.php");
        exit();
    }
    
    // ============ SAVE PROFILE ============
    $profileData = sanitizeProfileInput($_POST);
    $profileData['resume_path'] = $resumeName;
    
    $result = saveSeekerProfile($userId, $profileData);
    
    if ($result) {
        $_SESSION["success"]["profile"] = "Profile saved successfully!";
    } else {
        $_SESSION["error"]["profile"] = "Failed to save profile. Please try again.";
    }
    
    header("Location: ../Controller/seeker_profile_controller.php");
    exit();
}