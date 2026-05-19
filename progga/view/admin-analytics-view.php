<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");
function renderTable($title, $rows) {
    echo "<h2>" . htmlspecialchars($title) . "</h2>";
    if (empty($rows)) { echo "<p>No data found.</p>"; return; }
    echo "<table><tr>";
    foreach (array_keys($rows[0]) as $head) { echo "<th>" . htmlspecialchars(ucwords(str_replace('_', ' ', $head))) . "</th>"; }
    echo "</tr>";
    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $value) { echo "<td>" . htmlspecialchars((string)$value) . "</td>"; }
        echo "</tr>";
    }
    echo "</table>";
}
?>
<!DOCTYPE html>
<html>
<head><title>Platform Analytics</title><link rel="stylesheet" href="../view/assets/admin.css"></head>
<body>
<?php require_once __DIR__ . "/admin-menu.php"; ?>
<h1>Platform-wide Analytics</h1>
<?php
renderTable("Total Jobs Posted Per Category", $analytics["jobs_per_category"] ?? []);
renderTable("Application Volume Over Time", $analytics["applications_over_time"] ?? []);
renderTable("Top-performing Employers", $analytics["top_employers"] ?? []);
renderTable("Most Active Recruiters", $analytics["active_recruiters"] ?? []);
renderTable("Popular Locations", $analytics["popular_locations"] ?? []);
renderTable("Popular Job Types", $analytics["popular_job_types"] ?? []);
renderTable("User Growth Report: New Registrations Per Role Per Month", $analytics["user_growth"] ?? []);
?>
</div>
</body>
</html>
