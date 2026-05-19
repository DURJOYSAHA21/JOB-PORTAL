<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");
?>
<!DOCTYPE html>
<html>
<head><title>Handle Complaints</title><link rel="stylesheet" href="../view/assets/admin.css"></head>
<body>
<?php require_once __DIR__ . "/admin-menu.php"; ?>
<h1>Complaints & Disputes</h1>
<form class="filter" method="get" action="../controller/admin-complaints-controller.php">
    <select name="status">
        <option value="">All</option>
        <option value="open" <?php echo ($status ?? '') === 'open' ? 'selected' : ''; ?>>Open</option>
        <option value="resolved" <?php echo ($status ?? '') === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
    </select>
    <button type="submit">Filter</button>
</form>
<table>
<tr><th>ID</th><th>Submitter</th><th>Subject</th><th>Description</th><th>Status</th><th>Admin Note</th><th>Created</th><th>Resolution</th></tr>
<?php foreach ($complaints as $complaint): ?>
<tr>
    <td><?php echo (int)$complaint["id"]; ?></td>
    <td><?php echo htmlspecialchars($complaint["submitter_name"] ?? "Unknown"); ?></td>
    <td><?php echo htmlspecialchars($complaint["subject_name"] ?? "Unknown"); ?></td>
    <td><?php echo nl2br(htmlspecialchars($complaint["description"])); ?></td>
    <td><span class="badge"><?php echo htmlspecialchars($complaint["status"]); ?></span></td>
    <td><?php echo nl2br(htmlspecialchars($complaint["admin_note"] ?? "")); ?></td>
    <td><?php echo htmlspecialchars($complaint["created_at"]); ?></td>
    <td>
        <?php if ($complaint["status"] !== "resolved"): ?>
            <form method="post" action="../controller/admin-complaints-controller.php">
                <input type="hidden" name="complaint_id" value="<?php echo (int)$complaint["id"]; ?>">
                <textarea name="admin_note" placeholder="Write resolution note" required></textarea><br>
                <button type="submit">Mark Resolved</button>
            </form>
        <?php else: ?>
            Resolved
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
