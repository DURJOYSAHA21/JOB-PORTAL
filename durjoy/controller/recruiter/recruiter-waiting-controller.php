<?php
session_start();
include_once "../../model/recruiter/recruiter-waiting-model.php";

if(!isset($_SESSION["user"]["id"])) { echo json_encode(["success" => false]); exit(); }
$user_id = (int)$_SESSION["user"]["id"];
$status = getRecruiterVerificationStatus($user_id);
$_SESSION["is_verified"] = (int)$status["is_verified"];
echo json_encode(["success" => true, "is_verified" => (int)$status["is_verified"], "is_active" => (int)$status["is_active"]]);