<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Categories</title><link rel="stylesheet" href="../view/assets/admin.css"></head>
<body>
<?php require_once __DIR__ . "/admin-menu.php"; ?>
<h1>Manage Job Categories</h1>
<form class="filter" method="post" action="../controller/admin-categories-controller.php">
    <input type="hidden" name="action" value="create">
    <input type="text" name="name" placeholder="Category name" required>
    <input type="text" name="description" placeholder="Description">
    <button type="submit">Add Category</button>
</form>
<table>
<tr><th>ID</th><th>Name</th><th>Description</th><th>Total Jobs</th><th>Active Jobs</th><th>Actions</th></tr>
<?php foreach ($categories as $category): ?>
<tr>
    <td><?php echo (int)$category["id"]; ?></td>
    <td><?php echo htmlspecialchars($category["name"]); ?></td>
    <td><?php echo htmlspecialchars($category["description"]); ?></td>
    <td><?php echo (int)$category["total_jobs"]; ?></td>
    <td><?php echo (int)$category["active_jobs"]; ?></td>
    <td>
        <form method="post" action="../controller/admin-categories-controller.php">
            <input type="hidden" name="action" value="rename">
            <input type="hidden" name="id" value="<?php echo (int)$category["id"]; ?>">
            <input type="text" name="name" value="<?php echo htmlspecialchars($category["name"]); ?>" required>
            <input type="text" name="description" value="<?php echo htmlspecialchars($category["description"]); ?>">
            <button type="submit">Rename</button>
        </form>
        <form class="inline-form" method="post" action="../controller/admin-categories-controller.php" onsubmit="return confirm('Delete this category? Active-job categories will be blocked.');">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo (int)$category["id"]; ?>">
            <button class="danger" type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
