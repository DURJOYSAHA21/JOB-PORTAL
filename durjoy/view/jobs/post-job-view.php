<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/job-model.php";
$categories = getAllCategories();

$old = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post a Job - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/post-job.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Post a New Job</h1>
            <a href="manage-jobs-view.php" class="back-btn">Back to Jobs</a>
        </div>

        <?php if(!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form-card" method="POST" action="../../controller/job-create-controller.php" novalidate>
            <div class="form-section">
                <h2 class="section-title">Basic Information</h2>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label><span class="required">*</span> Job Title</label>
                        <input type="text" name="title" class="<?php echo isset($errors['title']) ? 'input-error' : ''; ?>" placeholder="e.g., Senior Software Engineer" value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" maxlength="255">
                        <?php if(isset($errors['title'])): ?>
                            <span class="error-msg"><?php echo htmlspecialchars($errors['title']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><span class="required">*</span> Category</label>
                        <select name="category_id" class="<?php echo isset($errors['category_id']) ? 'input-error' : ''; ?>">
                            <option value="">-- Select Category --</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($old['category_id']) && $old['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['category_id'])): ?>
                            <span class="error-msg"><?php echo htmlspecialchars($errors['category_id']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label><span class="required">*</span> Job Type</label>
                        <select name="job_type" class="<?php echo isset($errors['job_type']) ? 'input-error' : ''; ?>">
                            <option value="">-- Select Job Type --</option>
                            <option value="full-time" <?php echo (isset($old['job_type']) && $old['job_type'] == 'full-time') ? 'selected' : ''; ?>>Full-time</option>
                            <option value="part-time" <?php echo (isset($old['job_type']) && $old['job_type'] == 'part-time') ? 'selected' : ''; ?>>Part-time</option>
                            <option value="remote" <?php echo (isset($old['job_type']) && $old['job_type'] == 'remote') ? 'selected' : ''; ?>>Remote</option>
                            <option value="contract" <?php echo (isset($old['job_type']) && $old['job_type'] == 'contract') ? 'selected' : ''; ?>>Contract</option>
                        </select>
                        <?php if(isset($errors['job_type'])): ?>
                            <span class="error-msg"><?php echo htmlspecialchars($errors['job_type']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-row three-col">
                    <div class="form-group">
                        <label><span class="required">*</span> Experience Level</label>
                        <select name="experience_level" class="<?php echo isset($errors['experience_level']) ? 'input-error' : ''; ?>">
                            <option value="">-- Select Level --</option>
                            <option value="entry" <?php echo (isset($old['experience_level']) && $old['experience_level'] == 'entry') ? 'selected' : ''; ?>>Entry Level</option>
                            <option value="mid" <?php echo (isset($old['experience_level']) && $old['experience_level'] == 'mid') ? 'selected' : ''; ?>>Mid Level</option>
                            <option value="senior" <?php echo (isset($old['experience_level']) && $old['experience_level'] == 'senior') ? 'selected' : ''; ?>>Senior Level</option>
                        </select>
                        <?php if(isset($errors['experience_level'])): ?>
                            <span class="error-msg"><?php echo htmlspecialchars($errors['experience_level']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label><span class="required">*</span> Location</label>
                        <input type="text" name="location" class="<?php echo isset($errors['location']) ? 'input-error' : ''; ?>" placeholder="e.g., New York, NY" value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>">
                        <?php if(isset($errors['location'])): ?>
                            <span class="error-msg"><?php echo htmlspecialchars($errors['location']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label><span class="required">*</span> Application Deadline</label>
                        <input type="date" name="deadline" class="<?php echo isset($errors['deadline']) ? 'input-error' : ''; ?>" value="<?php echo htmlspecialchars($old['deadline'] ?? ''); ?>" min="<?php echo date('Y-m-d'); ?>">
                        <?php if(isset($errors['deadline'])): ?>
                            <span class="error-msg"><?php echo htmlspecialchars($errors['deadline']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2 class="section-title">Salary Range (Optional)</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label>Minimum Salary ($)</label>
                        <input type="number" name="salary_min" placeholder="e.g., 50000" value="<?php echo htmlspecialchars($old['salary_min'] ?? ''); ?>" min="0" step="1000">
                    </div>
                    <div class="form-group">
                        <label>Maximum Salary ($)</label>
                        <input type="number" name="salary_max" placeholder="e.g., 80000" value="<?php echo htmlspecialchars($old['salary_max'] ?? ''); ?>" min="0" step="1000">
                    </div>
                </div>
                <?php if(isset($errors['salary_range'])): ?>
                    <span class="error-msg"><?php echo htmlspecialchars($errors['salary_range']); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-section">
                <h2 class="section-title">Job Details</h2>
                <div class="form-row full">
                    <div class="form-group">
                        <label><span class="required">*</span> Description</label>
                        <textarea name="description" class="<?php echo isset($errors['description']) ? 'input-error' : ''; ?>" placeholder="Describe the role, responsibilities..." rows="5"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
                        <?php if(isset($errors['description'])): ?>
                            <span class="error-msg"><?php echo htmlspecialchars($errors['description']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Requirements</label>
                        <textarea name="requirements" placeholder="List required skills, qualifications..." rows="4"><?php echo htmlspecialchars($old['requirements'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Benefits</label>
                        <textarea name="benefits" placeholder="List benefits, perks..." rows="4"><?php echo htmlspecialchars($old['benefits'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="action" value="draft" class="btn btn-draft">Save as Draft</button>
                <button type="submit" name="action" value="publish" class="btn btn-publish">Publish Job</button>
            </div>
        </form>
    </div>
</body>
</html>