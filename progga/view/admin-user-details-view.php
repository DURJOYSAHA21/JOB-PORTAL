<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
    <link rel="stylesheet" href="../view/assets/admin.css">
</head>
<body>
<?php require_once __DIR__ . "/admin-menu.php"; ?>
<h1>User Details</h1>
<p><a href="../controller/admin-users-controller.php?role=<?php echo htmlspecialchars($user['role']); ?>">← Back to <?php echo htmlspecialchars(ucfirst($user['role'])); ?> Accounts</a></p>

<h2>Account Information</h2>
<table>
    <tr><th>ID</th><td><?php echo (int)$user["id"]; ?></td></tr>
    <tr><th>Name</th><td><?php echo htmlspecialchars($user["name"]); ?></td></tr>
    <tr><th>Email</th><td><?php echo htmlspecialchars($user["email"]); ?></td></tr>
    <tr><th>Phone</th><td><?php echo htmlspecialchars($user["phone"] ?? ""); ?></td></tr>
    <tr><th>Role</th><td><?php echo htmlspecialchars(ucfirst($user["role"])); ?></td></tr>
    <tr><th>Verified</th><td><?php echo $user["is_verified"] ? "Yes" : "No"; ?></td></tr>
    <tr><th>Active</th><td><?php echo $user["is_active"] ? "Active" : "Inactive"; ?></td></tr>
    <tr><th>Registered</th><td><?php echo htmlspecialchars($user["created_at"]); ?></td></tr>
</table>

<h2>Profile Information</h2>
<?php if (!empty($profile)): ?>
<table>
    <?php foreach ($profile as $key => $value): ?>
        <tr>
            <th><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $key))); ?></th>
            <td><?php echo nl2br(htmlspecialchars((string)$value)); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No profile information has been added for this user yet.</p>
<?php endif; ?>

<h2>Admin Action History</h2>
<?php if (!empty($actions)): ?>
<table>
    <tr><th>Action</th><th>Note</th><th>Admin</th><th>Date</th></tr>
    <?php foreach ($actions as $action): ?>
        <tr>
            <td><?php echo htmlspecialchars($action["action_type"]); ?></td>
            <td><?php echo nl2br(htmlspecialchars($action["note"] ?? "")); ?></td>
            <td><?php echo htmlspecialchars($action["admin_name"] ?? "Admin"); ?></td>
            <td><?php echo htmlspecialchars($action["created_at"]); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No admin actions recorded yet.</p>
<?php endif; ?>
</div>
</body>
</html>
