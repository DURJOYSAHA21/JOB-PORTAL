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

$employer_id = (int)$_SESSION['user']['id'];

$filters = [
    'job_id' => $_POST['job_id'] ?? '',
    'status' => $_POST['status'] ?? '',
    'experience_level' => $_POST['experience_level'] ?? '',
    'date_from' => $_POST['date_from'] ?? '',
    'date_to' => $_POST['date_to'] ?? ''
];

$applications = filterApplications($employer_id, $filters);

$html = '';
if(empty($applications)) {
    $html = '<tr class="empty-row"><td colspan="8">No applications found matching your filters.</td></tr>';
} else {
    foreach($applications as $app) {
        $statusClass = 'status-' . strtolower($app['status']);
        $appliedDate = date('M d, Y', strtotime($app['applied_at']));
        $resumeFile = $app['resume_path'] ?: ($app['seeker_resume'] ?? '');

        $html .= '<tr>';
        $html .= '<td><strong>' . htmlspecialchars($app['applicant_name']) . '</strong></td>';
        $html .= '<td>' . htmlspecialchars($app['job_title']) . '</td>';
        $html .= '<td>' . htmlspecialchars($app['headline'] ?? 'N/A') . '</td>';
        $html .= '<td>' . htmlspecialchars($app['years_experience'] ?? 'N/A') . ' yrs</td>';
        $html .= '<td><span class="status-badge ' . $statusClass . '">' . ucfirst($app['status']) . '</span></td>';
        $html .= '<td>' . $appliedDate . '</td>';
        if($resumeFile) {
            $html .= '<td><a href="../../uploads/resumes/' . htmlspecialchars($resumeFile) . '" target="_blank" class="btn btn-download">Resume</a></td>';
        } else {
            $html .= '<td><span style="color:#a0aec0;">No resume</span></td>';
        }
        $html .= '<td><a href="applicant-detail-view.php?app_id=' . $app['id'] . '" class="btn btn-view">View</a></td>';
        $html .= '</tr>';
    }
}

echo json_encode(['success' => true, 'html' => $html, 'count' => count($applications)]);
exit();