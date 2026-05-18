<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../model/job-model.php";
header('Content-Type: application/json');

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'employer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']); exit();
}

$job_id = (int)($_POST['job_id'] ?? 0);
$employer_id = (int)$_SESSION['user']['id'];

if($job_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid job ID']); exit();
}

$result = toggleJobStatus($job_id, $employer_id);

if($result) {
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to toggle. Job not found or access denied.']);
}
exit();