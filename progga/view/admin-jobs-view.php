<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../controller/auth-check-controller.php";
checkRole("admin");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Jobs</title>
    <link rel="stylesheet" href="../view/assets/admin.css">
</head>
<body>

<?php require_once __DIR__ . "/admin-menu.php"; ?>

<h1>All Job Postings & Featured Listings</h1>

<form class="filter" method="get" action="../controller/admin-jobs-controller.php">
    <input 
        type="text" 
        name="q" 
        placeholder="Search title, location, employer, recruiter" 
        value="<?php echo htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8'); ?>"
    >

    <select name="status">
        <option value="">All Status</option>
        <?php foreach (["active", "closed", "draft", "removed"] as $s): ?>
            <option value="<?php echo $s; ?>" <?php echo ($status ?? '') === $s ? 'selected' : ''; ?>>
                <?php echo ucfirst($s); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="employer_id">
        <option value="">All Employers</option>
        <?php foreach ($employers as $employer): ?>
            <option 
                value="<?php echo (int)$employer["id"]; ?>" 
                <?php echo ((string)($employerId ?? '') === (string)$employer["id"]) ? 'selected' : ''; ?>
            >
                <?php echo htmlspecialchars($employer["name"], ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="recruiter_id">
        <option value="">All Recruiters</option>
        <?php foreach ($recruiters as $recruiter): ?>
            <option 
                value="<?php echo (int)$recruiter["id"]; ?>" 
                <?php echo ((string)($recruiterId ?? '') === (string)$recruiter["id"]) ? 'selected' : ''; ?>
            >
                <?php echo htmlspecialchars($recruiter["name"], ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Filter</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Category</th>
        <th>Employer</th>
        <th>Recruiter</th>
        <th>Status</th>
        <th>Type</th>
        <th>Location</th>
        <th>Featured</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($jobs as $job): ?>
        <tr>
            <td><?php echo (int)$job["id"]; ?></td>

            <td>
                <?php echo htmlspecialchars($job["title"], ENT_QUOTES, 'UTF-8'); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($job["category_name"] ?? "", ENT_QUOTES, 'UTF-8'); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($job["employer_name"] ?? "", ENT_QUOTES, 'UTF-8'); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($job["recruiter_name"] ?? "-", ENT_QUOTES, 'UTF-8'); ?>
            </td>

            <td>
                <span class="badge">
                    <?php echo htmlspecialchars($job["status"], ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </td>

            <td>
                <?php echo htmlspecialchars($job["job_type"], ENT_QUOTES, 'UTF-8'); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($job["location"], ENT_QUOTES, 'UTF-8'); ?>
            </td>

            <td id="featured-status-<?php echo (int)$job["id"]; ?>">
                <?php echo !empty($job["is_featured"]) ? "Featured" : "Normal"; ?>
            </td>

            <td>
                <button 
                    type="button" 
                    onclick="toggleFeatured(<?php echo (int)$job['id']; ?>)"
                >
                    AJAX Toggle Featured
                </button>

                <form class="inline-form" method="post" action="../controller/admin-jobs-controller.php">
                    <input type="hidden" name="job_id" value="<?php echo (int)$job["id"]; ?>">
                    <input type="hidden" name="action" value="remove">
                    <button class="danger" type="submit">Remove Listing</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script src="../controller/ajax.js"></script>

</body>
</html>