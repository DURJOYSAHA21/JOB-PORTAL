<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    $_SESSION['errors'] = ['app' => 'Application not found'];
header("Location: view-applications-view.php");
    exit();
}

require_once "../../model/message-model.php";
require_once "../../model/application-model.php";

$app_id = (int)($_GET['app_id'] ?? 0);
$employer_id = (int)$_SESSION['user']['id'];

if($app_id <= 0) {
    header("Location: view-applications-view.php");
    exit();
}

$application = getApplicationById($app_id, $employer_id);
$seeker = getSeekerUserIdFromApplication($app_id, $employer_id);
$messages = getMessagesByApplication($app_id, $employer_id);

if(!$application || !$seeker) {
    $_SESSION['errors'] = ['app' => 'Application not found or access denied'];
    header("Location: view-applications-view.php");
    exit();
}

$success = $_SESSION['success'] ?? null;
$errors = $_SESSION['errors'] ?? null;
unset($_SESSION['success'], $_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/messages.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
                <a href="../dashboard-view.php">Dashboard</a>
                <a href="../company-profile-view.php">Company Profile</a>
                <a href="post-job-view.php">Post a Job</a>
                <a href="manage-jobs-view.php">Manage Jobs</a>
                <a href="view-applications-view.php" class="active">Applications</a>
                <a href="shortlisted-view.php">Shortlisted</a>
                <a href="../company-analytics-view.php">Analytics</a>
                <a href="recruiter-relationships-view.php">Recruiters</a>
                <a href="submit-complaint-view.php">Complaints</a>
                <a href="../../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Messages</h1>
                <a href="applicant-detail-view.php?app_id=<?php echo $app_id; ?>" class="btn-back">Back to Applicant</a>
            </div>

            <?php if($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if($errors): ?>
                <div class="alert alert-error">
                    <?php foreach($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="chat-container">
                <div class="chat-header">
                    <div class="chat-header-info">
                        <h3><?php echo htmlspecialchars($seeker['name']); ?></h3>
                        <p>
                            Job: <?php echo htmlspecialchars($application['job_title']); ?> 
                            <span class="status-badge status-<?php echo strtolower($application['status']); ?>">
                                <?php echo ucfirst($application['status']); ?>
                            </span>
                        </p>
                    </div>
                    <a href="applicant-detail-view.php?app_id=<?php echo $app_id; ?>" class="link-view">View Profile</a>
                </div>

                <div class="chat-messages" id="chat-messages">
                    <?php if(empty($messages)): ?>
                        <div class="empty-chat">
                            <p>No messages yet. Send a message to start the conversation.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($messages as $msg): 
                            $isSent = ($msg['sender_id'] == $employer_id);
                        ?>
                            <div class="message <?php echo $isSent ? 'message-sent' : 'message-received'; ?>">
                                <div><?php echo nl2br(htmlspecialchars($msg['body'])); ?></div>
                                <div class="message-time"><?php echo date('M d, g:i A', strtotime($msg['sent_at'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="chat-input-area">
                    <form class="chat-form" method="POST" action="../../controller/message-send-controller.php">
                        <input type="hidden" name="application_id" value="<?php echo $app_id; ?>">
                        <input type="hidden" name="redirect_url" value="../../view/jobs/message-applicant-view.php?app_id=<?php echo $app_id; ?>">
                        <textarea name="message_body" placeholder="Type your message here..." rows="1" required></textarea>
                        <button type="submit" class="btn-send">Send</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
    </script>
</body>
</html>