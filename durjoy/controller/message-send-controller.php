<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../model/message-model.php";

if(!isset($_SESSION['user']['id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../view/login-view.php"); exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = (int)$_SESSION['user']['id'];
    $application_id = (int)($_POST['application_id'] ?? 0);
    $body = trim($_POST['message_body'] ?? '');
    $redirect_url = $_POST['redirect_url'] ?? '../view/jobs/view-applications-view.php';
    $errors = [];

    if($application_id <= 0) $errors['app'] = 'Invalid application';
    if(empty($body)) $errors['body'] = 'Message cannot be empty';
    if(strlen($body) > 5000) $errors['body'] = 'Message too long';

    if(!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: " . $redirect_url); exit();
    }

    $seeker = getSeekerUserIdFromApplication($application_id, $sender_id);

    if(!$seeker) {
        $_SESSION['errors'] = ['app' => 'Application not found'];
        header("Location: " . $redirect_url); exit();
    }

    $sent = sendMessage($sender_id, $seeker['user_id'], $application_id, $body);

    if($sent) {
        $_SESSION['success'] = 'Message sent to ' . htmlspecialchars($seeker['name']);
    } else {
        $_SESSION['errors'] = ['send' => 'Failed to send message'];
    }

    header("Location: " . $redirect_url); exit();
}