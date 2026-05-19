<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/job-model.php";

$job_id = (int)($_GET['job_id'] ?? 0);
$employer_id = (int)$_SESSION['user']['id'];
$job = getJobById($job_id, $employer_id);

if(!$job) {
    header("Location: manage-jobs-view.php");
    exit();
}

$categories = getAllCategories();
$old = $_SESSION['old_input'] ?? $job;
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['errors']);
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/post-job.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Edit Job <span class="status-badge status-<?php echo $job['status']; ?>"><?php echo ucfirst($job['status']); ?></span></h1>
            <a href="manage-jobs-view.php" class="back-btn">Back to Jobs</a>
        </div>

        <?php if(!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form-card" method="POST" action="../../controller/job-edit-controller.php">
            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">

            <div class="form-section">
                <h2 class="section-title">Basic Information</h2>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label><span class="required">*</span> Job Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" maxlength="255">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><span class="required">*</span> Category</label>
                        <select name="category_id">
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($old['category_id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><span class="required">*</span> Job Type</label>
                        <select name="job_type">
                            <option value="full-time" <?php echo ($old['job_type'] == 'full-time') ? 'selected' : ''; ?>>Full-time</option>
                            <option value="part-time" <?php echo ($old['job_type'] == 'part-time') ? 'selected' : ''; ?>>Part-time</option>
                            <option value="remote" <?php echo ($old['job_type'] == 'remote') ? 'selected' : ''; ?>>Remote</option>
                            <option value="contract" <?php echo ($old['job_type'] == 'contract') ? 'selected' : ''; ?>>Contract</option>
                        </select>
                    </div>
                </div>
                <div class="form-row three-col">
                    <div class="form-group">
                        <label><span class="required">*</span> Experience Level</label>
                        <select name="experience_level">
                            <option value="entry" <?php echo ($old['experience_level'] == 'entry') ? 'selected' : ''; ?>>Entry</option>
                            <option value="mid" <?php echo ($old['experience_level'] == 'mid') ? 'selected' : ''; ?>>Mid</option>
                            <option value="senior" <?php echo ($old['experience_level'] == 'senior') ? 'selected' : ''; ?>>Senior</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><span class="required">*</span> Location</label>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label><span class="required">*</span> Deadline</label>
                        <input type="date" name="deadline" value="<?php echo htmlspecialchars($old['deadline'] ?? ''); ?>" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2 class="section-title">Salary Range</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label>Min Salary ($)</label>
                        <input type="number" name="salary_min" value="<?php echo htmlspecialchars($old['salary_min'] ?? ''); ?>" min="0" step="1000">
                    </div>
                    <div class="form-group">
                        <label>Max Salary ($)</label>
                        <input type="number" name="salary_max" value="<?php echo htmlspecialchars($old['salary_max'] ?? ''); ?>" min="0" step="1000">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2 class="section-title">Job Details</h2>
                <div class="form-row full">
                    <div class="form-group">
                        <label><span class="required">*</span> Description</label>
                        <textarea name="description" rows="5"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Requirements</label>
                        <textarea name="requirements" rows="4"><?php echo htmlspecialchars($old['requirements'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Benefits</label>
                        <textarea name="benefits" rows="4"><?php echo htmlspecialchars($old['benefits'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="action" value="draft" class="btn btn-draft">Save as Draft</button>
                <button type="submit" name="action" value="publish" class="btn btn-publish">Update & Publish</button>
            </div>
        </form>
    </div>
</body>
</html>