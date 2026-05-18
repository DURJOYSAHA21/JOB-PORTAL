<?php
require_once __DIR__ . '/../db.php';

function addCompanyInfo($user_id, $companyname, $industry, $companysize, $description, $website, $address, $logo) {
    $conn = connect();
    $sql = "INSERT INTO employer_profiles (user_id, company_name, industry, company_size, description, website, address, logo_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $user_id, $companyname, $industry, $companysize, $description, $website, $address, $logo);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function getCompanyInfo($user_id) {
    $conn = connect();
    $sql = "SELECT * FROM employer_profiles WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $company = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $company;
}

function updateCompanyInfo($user_id, $companyname, $industry, $companysize, $description, $website, $address, $logo_path) {
    $conn = connect();
    $existing = getCompanyInfo($user_id);
    
    if($existing) {
        $sql = "UPDATE employer_profiles 
                SET company_name = ?, industry = ?, company_size = ?, description = ?, website = ?, address = ?, logo_path = ?
                WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $companyname, $industry, $companysize, $description, $website, $address, $logo_path, $user_id);
    } else {
        return addCompanyInfo($user_id, $companyname, $industry, $companysize, $description, $website, $address, $logo_path);
    }
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function companyinfoverify($user_id) {
    $conn = connect();
    $sql = "SELECT * FROM employer_profiles 
            JOIN users ON employer_profiles.user_id = users.id 
            WHERE users.id = ? AND users.is_verified = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}