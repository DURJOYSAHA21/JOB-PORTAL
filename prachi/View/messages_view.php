<?php
$conversations = $viewData['conversations'] ?? [];
$messages = $viewData['messages'] ?? [];
$otherUser = $viewData['otherUser'] ?? null;
$activeChat = $viewData['activeChat'] ?? null;
$recruiterOutreach = $viewData['recruiterOutreach'] ?? [];
$successMsg = $viewData['success'] ?? null;
$currentUserId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Job Portal</title>
    <link rel="stylesheet" href="../View/css/messages.css">
</head>
<body>
    <div class="container">
        
        <a href="../Controller/seeker_dashboard_controller.php" class="back-link">← Back to Dashboard</a>
        
        <h1>💬 Messages</h1>
        
        <?php if ($successMsg): ?>
            <div class="success-message"><?php echo htmlspecialchars($successMsg); ?></div>
        <?php endif; ?>
        
        <div class="messages-layout">
            
            <!-- Sidebar: Conversation List -->
            <div class="chat-sidebar">
                <h3>Conversations</h3>
                
                <?php if (empty($conversations)): ?>
                    <p class="empty-text">No conversations yet</p>
                <?php else: ?>
                    <?php foreach ($conversations as $conv): ?>
                        <a href="../Controller/messages_controller.php?user=<?php echo $conv['other_user_id']; ?>" 
                           class="conv-item <?php echo $activeChat == $conv['other_user_id'] ? 'active' : ''; ?>">
                            <div class="conv-name">
                                <?php echo htmlspecialchars($conv['company_name'] ?? $conv['agency_name'] ?? $conv['other_user_name']); ?>
                                <?php if ($conv['unread_count'] > 0): ?>
                                    <span class="unread-badge"><?php echo $conv['unread_count']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="conv-preview"><?php echo htmlspecialchars(substr($conv['last_message'], 0, 40)); ?>...</div>
                            <div class="conv-time"><?php echo date('M j', strtotime($conv['last_time'])); ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Main: Chat Area -->
            <div class="chat-main">
                <?php if ($otherUser): ?>
                    
                    <div class="chat-header">
                        <h3><?php echo htmlspecialchars($otherUser['company_name'] ?? $otherUser['agency_name'] ?? $otherUser['name']); ?></h3>
                    </div>
                    
                    <div class="chat-messages" id="chatMessages">
    <?php if (empty($messages) && empty($recruiterOutreach)): ?>
        <p class="empty-text">No messages yet. Start the conversation!</p>
    <?php else: ?>
        
        <!-- Show recruiter outreach for this user -->
        <?php foreach ($recruiterOutreach as $ro): ?>
            <?php if ($ro['recruiter_id'] == $activeChat): ?>
                <div class="message received">
                    <div class="msg-bubble outreach-bubble">
                        <div class="outreach-label">📨 Recruiter Outreach<?php if ($ro['job_title']): ?> - <?php echo htmlspecialchars($ro['job_title']); ?><?php endif; ?></div>
                        <?php echo nl2br(htmlspecialchars($ro['message'])); ?>
                    </div>
                    <div class="msg-time"><?php echo date('M j, g:i A', strtotime($ro['sent_at'])); ?></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <!-- Show regular messages -->
        <?php foreach ($messages as $msg): ?>
            <div class="message <?php echo $msg['sender_id'] == $currentUserId ? 'sent' : 'received'; ?>">
                <div class="msg-bubble">
                    <?php echo nl2br(htmlspecialchars($msg['body'])); ?>
                </div>
                <div class="msg-time"><?php echo date('g:i A', strtotime($msg['sent_at'])); ?></div>
            </div>
        <?php endforeach; ?>
        
    <?php endif; ?>
</div>
                    
                    <form method="post" class="chat-form">
                        <input type="hidden" name="recipient_id" value="<?php echo $otherUser['id']; ?>">
                        <textarea name="message" placeholder="Type your message..." required></textarea>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                    
                <?php else: ?>
                    <div class="no-chat">
                        <p>👈 Select a conversation or check recruiter outreach below</p>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
        
        <!-- Recruiter Outreach -->
        <div class="section">
            <h2>📨 Recruiter Outreach</h2>
            
            <?php if (empty($recruiterOutreach)): ?>
                <p class="empty-text">No recruiter outreach messages yet</p>
            <?php else: ?>
                <?php foreach ($recruiterOutreach as $ro): ?>
                    <div class="outreach-card">
                        <div class="outreach-header">
                            <strong><?php echo htmlspecialchars($ro['agency_name'] ?? $ro['recruiter_name']); ?></strong>
                            <?php if ($ro['job_title']): ?>
                                <span class="tag">Re: <?php echo htmlspecialchars($ro['job_title']); ?></span>
                            <?php endif; ?>
                            <span class="outreach-status status-<?php echo $ro['status']; ?>"><?php echo ucfirst($ro['status']); ?></span>
                        </div>
                        <p class="outreach-message"><?php echo nl2br(htmlspecialchars($ro['message'])); ?></p>
                        <div class="outreach-footer">
                            <span class="outreach-time"><?php echo date('M j, Y', strtotime($ro['sent_at'])); ?></span>
                            <a href="../Controller/messages_controller.php?user=<?php echo $ro['recruiter_id']; ?>" class="btn btn-sm">Reply</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
    </div>
</body>
</html>