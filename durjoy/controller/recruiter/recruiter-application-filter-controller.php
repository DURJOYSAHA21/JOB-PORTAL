<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../../model/recruiter/recruiter-application-model.php";
require_once "../../model/recruiter/recruiter-client-model.php";
header('Content-Type: application/json');

$recruiterProfileId = getRecruiterProfileId((int)$_SESSION['user']['id']);
$filters = ['job_id' => $_POST['job_id'] ?? '', 'status' => $_POST['status'] ?? ''];
$applications = filterRecruiterApplications($recruiterProfileId, $filters);

$html = '';
foreach($applications as $app) {
    $html .= '<tr>';
    $html .= '<td><strong>' . htmlspecialchars($app['applicant_name']) . '</strong></td>';
    $html .= '<td>' . htmlspecialchars($app['job_title']) . '</td>';
    $html .= '<td>' . htmlspecialchars($app['client_name'] ?? 'N/A') . '</td>';
    $html .= '<td>';
    $html .= '<span class="status-badge status-' . $app['status'] . '">' . ucfirst($app['status']) . '</span>';
    $html .= '<select class="status-dropdown" onchange="updateStatus(' . $app['id'] . ', this)">';
    foreach(['submitted','reviewed','shortlisted','interview','rejected'] as $s) {
        $sel = $app['status'] == $s ? 'selected' : '';
        $html .= '<option value="' . $s . '" ' . $sel . '>' . ucfirst($s) . '</option>';
    }
    $html .= '</select><span class="status-msg" id="msg-' . $app['id'] . '"></span>';
    $html .= '</td>';
    $html .= '<td>' . date('M d, Y', strtotime($app['applied_at'])) . '</td>';
    $html .= '<td><a href="applicant-detail-view.php?app_id=' . $app['id'] . '" class="btn btn-view">View</a></td>';
    $html .= '</tr>';
}

echo json_encode(['success' => true, 'html' => $html, 'count' => count($applications)]);