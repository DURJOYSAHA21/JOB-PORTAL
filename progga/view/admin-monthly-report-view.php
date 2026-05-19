<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");
?>

<!DOCTYPE html>
<html>
<head><title>Monthly Platform Summary</title><link rel="stylesheet" href="../view/assets/admin.css"></head>
<body>
<?php require_once __DIR__ . "/admin-menu.php"; ?>
<h1>Monthly Platform Summary Report</h1>
<form class="filter" method="get" action="../controller/admin-report-controller.php">
    <label>Select Month</label>
    <input type="month" name="month" value="<?php echo htmlspecialchars($month); ?>">
    <button type="submit">Generate</button>
    <a href="../controller/admin-report-controller.php?month=<?php echo urlencode($month); ?>&export=csv">Export CSV File</a>
</form>
<table>
<tr><th>Metric</th><th>Value</th></tr>
<tr><td>Month</td><td><?php echo htmlspecialchars($report["month"]); ?></td></tr>
<tr><td>Total New Users</td><td><?php echo (int)$report["new_users"]; ?></td></tr>
<?php foreach ($report["new_users_by_role"] as $row): ?>
<tr><td>New Users - <?php echo htmlspecialchars($row["role"]); ?></td><td><?php echo (int)$row["total"]; ?></td></tr>
<?php endforeach; ?>
<tr><td>Total Jobs Posted</td><td><?php echo (int)$report["jobs_posted"]; ?></td></tr>
<tr><td>Total Applications</td><td><?php echo (int)$report["applications"]; ?></td></tr>
<tr><td>Top Category</td><td><?php echo htmlspecialchars($report["top_category"]["name"]); ?> (<?php echo (int)$report["top_category"]["total"]; ?>)</td></tr>
<tr><td>Top Employer</td><td><?php echo htmlspecialchars($report["top_employer"]["name"]); ?> (<?php echo (int)$report["top_employer"]["total"]; ?>)</td></tr>
<tr><td>Most Active Recruiter</td><td><?php echo htmlspecialchars($report["most_active_recruiter"]["name"]); ?> (<?php echo (int)$report["most_active_recruiter"]["total"]; ?>)</td></tr>
<tr><td>Complaints Opened</td><td><?php echo (int)$report["complaints_opened"]; ?></td></tr>
<tr><td>Complaints Resolved</td><td><?php echo (int)$report["complaints_resolved"]; ?></td></tr>
</table>
</div>
</body>
</html>
