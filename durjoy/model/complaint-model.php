<?php
require_once __DIR__ . '/../db.php';

function submitComplaint($submitter_id, $subject_id, $description) {
    $conn = connect();
    $sql = "INSERT INTO complaints (submitter_id, subject_id, description, status, created_at) VALUES (?, ?, ?, 'open', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $submitter_id, $subject_id, $description);
    $result = $stmt->execute();
    $insert_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    return $result ? $insert_id : false;
}

function getEmployerComplaints($submitter_id) {
    $conn = connect();
    $sql = "SELECT c.*, u.name as subject_name, u.email as subject_email, u.role as subject_role
            FROM complaints c JOIN users u ON c.subject_id = u.id
            WHERE c.submitter_id = ? ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $submitter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $complaints = [];
    while($row = $result->fetch_assoc()) { $complaints[] = $row; }
    $stmt->close();
    $conn->close();
    return $complaints;
}

function getComplaintSubjects($employer_id) {
    $conn = connect();
    $sql = "SELECT DISTINCT u.id, u.name, u.email, u.role, rp.agency_name
            FROM recruiter_clients rc
            JOIN recruiter_profiles rp ON rc.recruiter_id = rp.id
            JOIN users u ON rp.user_id = u.id
            WHERE rc.employer_id = (SELECT id FROM employer_profiles WHERE user_id = ?)
            UNION
            SELECT DISTINCT u.id, u.name, u.email, u.role, NULL as agency_name
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN seeker_profiles sp ON a.seeker_id = sp.id
            JOIN users u ON sp.user_id = u.id
            WHERE j.employer_id = ?
            ORDER BY role, name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $employer_id, $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $subjects = [];
    while($row = $result->fetch_assoc()) { $subjects[] = $row; }
    $stmt->close();
    $conn->close();
    return $subjects;
}