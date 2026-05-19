<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../model/application-model.php";
header('Content-Type: application/json');

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'employer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']); exit();
}

$application_id = (int)($_POST['application_id'] ?? 0);
$new_status = trim($_POST['status'] ?? '');
$employer_id = (int)$_SESSION['user']['id'];

$valid_statuses = ['submitted', 'reviewed', 'shortlisted', 'interview', 'rejected'];
if(!in_array($new_status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']); exit();
}

$updated = updateApplicationStatus($application_id, $employer_id, $new_status);

if($updated) {
    echo json_encode(['success' => true, 'new_status' => $updated, 'message' => 'Status updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update']);
}
exit();