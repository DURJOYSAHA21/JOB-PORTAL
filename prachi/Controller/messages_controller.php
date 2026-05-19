<?php
session_start();
require_once("../db.php");
require_once("../Model/message_model.php");
require_once("../Model/alert_model.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../View/login_view.php");
    exit();
}

$userId = $_SESSION["user_id"];
// ============ SEND MESSAGE ============
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $recipientId = $_POST['recipient_id'] ?? 0;
    $body = trim($_POST['message'] ?? '');
    
    if (!empty($recipientId) && !empty($body)) {
        sendMessage($userId, $recipientId, $body);
        
        // Update outreach status to 'responded'
        $sql = "UPDATE recruiter_outreach SET status = 'responded' 
                WHERE recruiter_id = $recipientId AND seeker_id = $userId AND status IN ('sent', 'read')";
        mysqli_query($conn, $sql);
    }
    
    header("Location: ../Controller/messages_controller.php?user=" . $recipientId);
    exit();
}
// ============ VIEW CONVERSATION ============
if (isset($_GET['user'])) {
    $otherUserId = (int)$_GET['user'];
    
    // Mark messages as read
    markMessagesRead($userId, $otherUserId);
    
    // Update outreach status from 'sent' to 'read'
    $sql = "UPDATE recruiter_outreach SET status = 'read' 
            WHERE recruiter_id = $otherUserId AND seeker_id = $userId AND status = 'sent'";
    mysqli_query($conn, $sql);
    
    $messages = getMessages($userId, $otherUserId);
    $otherUser = getUserByIdSimple($otherUserId);
    $conversations = getConversations($userId);
    $outreachMessages = getRecruiterOutreach($userId);
    
    $viewData = [
        'conversations' => $conversations,
        'messages' => $messages,
        'otherUser' => $otherUser,
        'activeChat' => $otherUserId,
        'recruiterOutreach' => $outreachMessages
    ];
    
    require_once("../View/messages_view.php");
    exit();
}

// ============ VIEW ALL CONVERSATIONS ============
$conversations = getConversations($userId);
$recruiterOutreach = getRecruiterOutreach($userId);

$viewData = [
    'conversations' => $conversations,
    'recruiterOutreach' => $recruiterOutreach,
    'messages' => [],
    'otherUser' => null,
    'activeChat' => null,
    'success' => $_SESSION["success"]["message"] ?? null
];

unset($_SESSION["success"]["message"]);

require_once("../View/messages_view.php");