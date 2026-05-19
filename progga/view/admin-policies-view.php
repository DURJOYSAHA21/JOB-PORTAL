<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");
$policies = $policies ?? [];
?>
<!DOCTYPE html>
<html>
<head><title>Platform Policies</title><link rel="stylesheet" href="../view/assets/admin.css"></head>
<body>
<?php require_once __DIR__ . "/admin-menu.php"; ?>
<h1>Platform-wide Policies</h1>
<form method="post" action="../controller/admin-policies-controller.php">
    <label>Maximum job postings per employer</label><br>
    <input type="number" name="max_jobs_per_employer" min="1" value="<?php echo htmlspecialchars($policies["max_jobs_per_employer"] ?? "10"); ?>" required><br><br>

    <label>Maximum active applications per seeker</label><br>
    <input type="number" name="max_active_applications_per_seeker" min="1" value="<?php echo htmlspecialchars($policies["max_active_applications_per_seeker"] ?? "20"); ?>" required><br><br>

    <label>Resume visibility default</label><br>
    <select name="resume_visibility_default">
        <option value="private" <?php echo (($policies["resume_visibility_default"] ?? "private") === "private") ? "selected" : ""; ?>>Private</option>
        <option value="public" <?php echo (($policies["resume_visibility_default"] ?? "private") === "public") ? "selected" : ""; ?>>Public</option>
    </select><br><br>

    <button type="submit">Save Policies</button>
</form>
</div>
</body>
</html>
