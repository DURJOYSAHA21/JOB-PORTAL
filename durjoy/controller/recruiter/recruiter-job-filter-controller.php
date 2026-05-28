<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../../model/recruiter/recruiter-job-filter-model.php";
header('Content-Type: application/json');

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'recruiter') {
    echo json_encode(['success' => false]); exit();
}

$recruiterProfileId = getRecruiterProfileId((int)$_SESSION['user']['id']);
$filters = [
    'client_id' => $_POST['client_id'] ?? '',
    'status' => $_POST['status'] ?? '',
    'category_id' => $_POST['category_id'] ?? ''
];

$jobs = filterRecruiterJobs($recruiterProfileId, $filters);

$html = '';
foreach($jobs as $job) {
    $html .= '<tr>';
    $html .= '<td><strong>' . htmlspecialchars($job['title']) . '</strong></td>';
    $html .= '<td>' . htmlspecialchars($job['client_name'] ?? 'N/A') . '</td>';
    $html .= '<td>' . htmlspecialchars($job['category_name'] ?? 'N/A') . '</td>';
    $html .= '<td><span class="status-badge status-' . $job['status'] . '">' . ucfirst($job['status']) . '</span></td>';
    $html .= '<td>' . $job['application_count'] . '</td>';
    $html .= '<td>' . date('M d, Y', strtotime($job['deadline'])) . '</td>';
    $html .= '</tr>';
}

echo json_encode(['success' => true, 'html' => $html, 'count' => count($jobs)]);