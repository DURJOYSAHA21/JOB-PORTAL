<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");
$titleMap = ["employer" => "Employer Accounts", "recruiter" => "Recruiter Accounts", "seeker" => "Seeker Accounts"];
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($titleMap[$role]); ?></title>
    <link rel="stylesheet" href="../view/assets/admin.css">
</head>
<body>
<?php require_once __DIR__ . "/admin-menu.php"; ?>
<h1><?php echo htmlspecialchars($titleMap[$role]); ?></h1>
<form class="filter" method="get" action="../controller/admin-users-controller.php">
    <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
    <input type="text" name="q" placeholder="Search name, email, phone" value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
    <?php if ($role !== "seeker"): ?>
        <select name="verification">
            <option value="">All</option>
            <option value="pending" <?php echo ($verification ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="verified" <?php echo ($verification ?? '') === 'verified' ? 'selected' : ''; ?>>Verified</option>
            <option value="suspended" <?php echo ($verification ?? '') === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
        </select>
    <?php else: ?>
        <select name="verification">
            <option value="">All</option>
            <option value="suspended" <?php echo ($verification ?? '') === 'suspended' ? 'selected' : ''; ?>>Deactivated</option>
        </select>
    <?php endif; ?>
    <button type="submit">Search</button>
</form>
<table>
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Verified</th><th>Active</th><th>Created</th><th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo (int)$user["id"]; ?></td>
            <td><?php echo htmlspecialchars($user["name"]); ?></td>
            <td><?php echo htmlspecialchars($user["email"]); ?></td>
            <td><?php echo htmlspecialchars($user["phone"]); ?></td>
            <td><?php echo $user["is_verified"] ? "Yes" : "No"; ?></td>
            <td><?php echo $user["is_active"] ? "Active" : "Inactive"; ?></td>
            <td><?php echo htmlspecialchars($user["created_at"]); ?></td>
            <td>
                <a class="button-link" href="../controller/admin-user-details-controller.php?id=<?php echo (int)$user["id"]; ?>">View</a>
                <?php if ($role !== "seeker" && !$user["is_verified"] && $user["is_active"]): ?>
                    <form class="inline-form" method="post" action="../controller/admin-users-controller.php">
                        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
                        <input type="hidden" name="user_id" value="<?php echo (int)$user["id"]; ?>">
                        <input type="hidden" name="action" value="approve">
                        <button type="submit">Approve</button>
                    </form>
                    <form method="post" action="../controller/admin-users-controller.php">
                        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
                        <input type="hidden" name="user_id" value="<?php echo (int)$user["id"]; ?>">
                        <input type="hidden" name="action" value="reject">
                        <input type="text" name="reason" placeholder="Reject reason" required>
                        <button class="danger" type="submit">Reject</button>
                    </form>
                <?php endif; ?>
                <?php if ($user["is_active"]): ?>
                    <form class="inline-form" method="post" action="../controller/admin-users-controller.php">
                        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
                        <input type="hidden" name="user_id" value="<?php echo (int)$user["id"]; ?>">
                        <input type="hidden" name="action" value="suspend">
                        <button class="danger" type="submit"><?php echo $role === "seeker" ? "Deactivate" : "Suspend"; ?></button>
                    </form>
                <?php else: ?>
                    <form class="inline-form" method="post" action="../controller/admin-users-controller.php">
                        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
                        <input type="hidden" name="user_id" value="<?php echo (int)$user["id"]; ?>">
                        <input type="hidden" name="action" value="reactivate">
                        <button type="submit">Reactivate</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</div>
</body>
</html>
