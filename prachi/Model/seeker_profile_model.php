<?php
require_once("../db.php");

function getSeekerProfileByUserId($userId) {
    global $conn;
    $sql = "SELECT * FROM seeker_profiles WHERE user_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return null;
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function createSeekerProfile($userId, $data) {
    global $conn;
    $sql = "INSERT INTO seeker_profiles (
                user_id, headline, summary, skills, years_experience, 
                education_level, current_salary, expected_salary, preferred_location,
                resume_path
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;
    
    $resumePath = $data['resume_path'] ?? null;
    
    mysqli_stmt_bind_param($stmt, "isssisddss",
        $userId, $data['headline'], $data['summary'], $data['skills'],
        $data['years_experience'], $data['education_level'],
        $data['current_salary'], $data['expected_salary'], $data['preferred_location'],
        $resumePath
    );
    
    if (mysqli_stmt_execute($stmt)) return $conn->insert_id;
    return false;
}

function updateSeekerProfile($userId, $data) {
    global $conn;
    $sql = "UPDATE seeker_profiles SET 
                headline=?, summary=?, skills=?, years_experience=?,
                education_level=?, current_salary=?, expected_salary=?, preferred_location=?,
                resume_path=?
            WHERE user_id=?";
    
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;
    
    $resumePath = $data['resume_path'] ?? null;
    
    mysqli_stmt_bind_param($stmt, "sssisddssi",
        $data['headline'], $data['summary'], $data['skills'],
        $data['years_experience'], $data['education_level'],
        $data['current_salary'], $data['expected_salary'], $data['preferred_location'],
        $resumePath, $userId
    );
    
    return mysqli_stmt_execute($stmt);
}

function saveSeekerProfile($userId, $data) {
    $existing = getSeekerProfileByUserId($userId);
    if ($existing) return updateSeekerProfile($userId, $data);
    return createSeekerProfile($userId, $data);
}

function updateUserProfilePic($userId, $profilePicPath) {
    global $conn;
    $sql = "UPDATE users SET profile_pic = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, "si", $profilePicPath, $userId);
    return mysqli_stmt_execute($stmt);
}

function validateProfileData($data) {
    $errors = [];
    
    if (empty(trim($data['headline']))) {
        $errors['headline'] = "Headline is required";
    } elseif (strlen(trim($data['headline'])) > 255) {
        $errors['headline'] = "Headline must be less than 255 characters";
    }
    
    if (empty(trim($data['summary']))) {
        $errors['summary'] = "Professional summary is required";
    }
    
    if (empty(trim($data['skills']))) {
        $errors['skills'] = "At least one skill is required";
    }
    
    if (!isset($data['years_experience']) || $data['years_experience'] === '') {
        $errors['years_experience'] = "Years of experience is required";
    } elseif (!is_numeric($data['years_experience']) || $data['years_experience'] < 0 || $data['years_experience'] > 50) {
        $errors['years_experience'] = "Please enter a valid number (0-50)";
    }
    
    if (empty(trim($data['education_level']))) {
        $errors['education_level'] = "Education level is required";
    }
    
    if (!empty($data['current_salary']) && !is_numeric($data['current_salary'])) {
        $errors['current_salary'] = "Enter a valid current salary amount";
    }
    
    if (empty($data['expected_salary']) || !is_numeric($data['expected_salary'])) {
        $errors['expected_salary'] = "Enter a valid expected salary amount";
    } elseif ($data['expected_salary'] < 0) {
        $errors['expected_salary'] = "Expected salary cannot be negative";
    }
    
    if (empty(trim($data['preferred_location']))) {
        $errors['preferred_location'] = "Preferred location is required";
    }
    
    return $errors;
}

function sanitizeProfileInput($data) {
    global $conn;
    return [
        'headline' => mysqli_real_escape_string($conn, trim($data['headline'])),
        'summary' => mysqli_real_escape_string($conn, trim($data['summary'])),
        'skills' => mysqli_real_escape_string($conn, trim($data['skills'])),
        'years_experience' => (int)$data['years_experience'],
        'education_level' => mysqli_real_escape_string($conn, trim($data['education_level'])),
        'current_salary' => !empty($data['current_salary']) ? (float)$data['current_salary'] : 0,
        'expected_salary' => (float)$data['expected_salary'],
        'preferred_location' => mysqli_real_escape_string($conn, trim($data['preferred_location'])),
        'resume_path' => $data['resume_path'] ?? null
    ];
}