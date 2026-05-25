<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../../model/recruiter/recruiter-candidate-search-model.php";
header('Content-Type: application/json');

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'recruiter') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit();
}

$filters = [
    'keyword' => $_POST['keyword'] ?? '',
    'location' => $_POST['location'] ?? '',
    'experience' => $_POST['experience'] ?? '',
    'salary' => $_POST['salary'] ?? ''
];

$candidates = searchCandidates($filters);

$html = '';
if(empty($candidates)) {
    $html = '<p class="empty-state">No candidates found matching your criteria.</p>';
} else {
    foreach($candidates as $c) {
        $html .= '<div class="candidate-card">';
        $html .= '<div class="candidate-header">';
        $html .= '<div>';
        $html .= '<div class="candidate-name">' . htmlspecialchars($c['name']) . '</div>';
        $html .= '<div class="candidate-headline">' . htmlspecialchars($c['headline'] ?? 'No headline') . '</div>';
        $html .= '</div>';
        $html .= '<a href="candidate-profile-view.php?seeker_id=' . $c['user_id'] . '" class="btn btn-view">View Profile</a>';
        $html .= '</div>';
        $html .= '<div class="candidate-meta">';
        $html .= '<span>Exp: ' . htmlspecialchars($c['years_experience'] ?? 'N/A') . ' yrs</span>';
        $html .= '<span>Location: ' . htmlspecialchars($c['preferred_location'] ?? 'N/A') . '</span>';
        $html .= '<span>Education: ' . htmlspecialchars($c['education_level'] ?? 'N/A') . '</span>';
        $html .= '<span>Expected: $' . number_format($c['expected_salary'] ?? 0) . '</span>';
        $html .= '</div>';
        if(!empty($c['skills'])) {
            $html .= '<div class="skills-list">';
            $skills = array_slice(explode(',', $c['skills']), 0, 5);
            foreach($skills as $skill) {
                $html .= '<span class="skill-tag">' . htmlspecialchars(trim($skill)) . '</span>';
            }
            if(count(explode(',', $c['skills'])) > 5) {
                $html .= '<span class="skill-tag">+more</span>';
            }
            $html .= '</div>';
        }
        if(!empty($c['summary'])) {
            $html .= '<div class="candidate-summary">' . htmlspecialchars(substr($c['summary'], 0, 200)) . '...</div>';
        }
        $html .= '</div>';
    }
}

echo json_encode(['success' => true, 'html' => $html, 'count' => count($candidates)]);