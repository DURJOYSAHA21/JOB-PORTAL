<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");
?>
<!DOCTYPE html>
<html>
<head><title>Announcements</title><link rel="stylesheet" href="../view/assets/admin.css"></head>
<body>
<?php require_once __DIR__ . "/admin-menu.php"; ?>

<h1>Platform-wide Announcements</h1>

<form class="filter" method="post" action="../controller/admin-announcements-controller.php">
    <input type="hidden" name="action" value="create">
    <input type="text" name="title" placeholder="Announcement title" required style="width: 50%;"><br>
    <textarea name="body" placeholder="Announcement body" required style="width: 70%; height: 90px;"></textarea><br>
    <button type="submit">Post Announcement</button>
</form>
<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Body</th>
    <th>Posted By</th>
    <th>Created</th>
    <th>Action</th>
</tr>
<?php foreach ($announcements as $announcement): ?>
<tr>
    <td><?php echo (int)$announcement["id"]; ?></td>
    <td><?php echo htmlspecialchars($announcement["title"]); ?></td>
    <td><?php echo nl2br(htmlspecialchars($announcement["body"])); ?></td>
    <td><?php echo htmlspecialchars($announcement["admin_name"] ?? "Admin"); ?></td>
    <td><?php echo htmlspecialchars($announcement["created_at"]); ?></td>
    <td>
        <form method="post" action="../controller/admin-announcements-controller.php">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo (int)$announcement["id"]; ?>">
            <button class="danger" type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
