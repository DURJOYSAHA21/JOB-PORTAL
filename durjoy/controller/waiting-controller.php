<?php
session_start();
include_once "../model/waiting-model.php";

if(!isset($_SESSION["user"]["id"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$user_id = (int)$_SESSION["user"]["id"];
$status  = getVerificationStatus($user_id);

if(!$status) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit();
}

$_SESSION["is_verified"] = (int)$status["is_verified"];

echo json_encode([
    "success"     => true,
    "is_verified" => (int)$status["is_verified"],
    "is_active"   => (int)$status["is_active"]
]);