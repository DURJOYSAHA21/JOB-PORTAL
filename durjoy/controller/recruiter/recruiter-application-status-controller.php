<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../../model/recruiter/recruiter-application-model.php";
header('Content-Type: application/json');

$application_id = (int)($_POST['application_id'] ?? 0);
$new_status = trim($_POST['status'] ?? '');
$valid = ['submitted','reviewed','shortlisted','interview','rejected'];

if(!in_array($new_status, $valid)) {
    echo json_encode(['success' => false]); exit();
}

$updated = updateRecruiterApplicationStatus($application_id, $new_status);
echo json_encode(['success' => true, 'new_status' => $updated]);