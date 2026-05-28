<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { header("Location: ../recruiter-login-view.php"); exit(); }

require_once "../../../model/recruiter/recruiter-client-model.php";
require_once "../../../model/job-model.php";

$recruiter_id = (int)$_SESSION['user']['id'];
$clients = getRecruiterClients($recruiter_id);
$categories = getAllCategories();
$old = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['errors']);

// Get pre-selected client if coming from client page
$selectedClientId = $_GET['client_id'] ?? ($old['client_id'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Job for Client - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/post-job.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
    <a href="../recruiter-dashboard-view.php">Dashboard</a>
    <a href="../recruiter-profile-view.php">Agency Profile</a>
    <a href="../client/manage-clients-view.php">Client Companies</a>
    <a href="post-job-view.php">Post a Job</a>
    <a href="manage-jobs-view.php">Manage Jobs</a>
    <a href="all-jobs-view.php">All Jobs</a>
    <a href="../candidate/candidate-search-view.php">Search Candidates</a>
    <a href="../outreach/outreach-list-view.php">Outreach</a>
    <a href="../application/view-applications-view.php">Applications</a>
    <a href="../candidate/candidate-pipeline-view.php">Pipeline</a>
    <a href="../placement/placement-history-view.php">Placements</a>
    <a href="../analytics/recruiter-analytics-view.php">Analytics</a>
    <a href="../report/client-report-view.php">Reports</a>
    <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
</nav>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <h1>Post Job for Client</h1>
                <a href="manage-jobs-view.php" class="btn-back">Back to Jobs</a>
            </div>

            <?php if(!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul>
                </div>
            <?php endif; ?>

            <?php if(empty($clients)): ?>
                <div class="alert alert-warning">
                    <p>You need to add a client company first before posting a job.</p>
                    <a href="../client/add-client-view.php" class="btn btn-primary">Add Client</a>
                </div>
            <?php else: ?>
                <form class="form-card" method="POST" action="../../../controller/recruiter/recruiter-job-create-controller.php" novalidate>
                    
                    <!-- Client Selection (Recruiter-specific) -->
                    <div class="form-section">
                        <h2 class="section-title">Client Company</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label><span class="required">*</span> Posting For</label>
                                <select name="client_id" class="<?php echo isset($errors['client_id']) ? 'input-error' : ''; ?>" required>
                                    <option value="">-- Select Client Company --</option>
                                    <?php foreach($clients as $client): ?>
                                        <option value="<?php echo $client['id']; ?>" <?php echo ($selectedClientId == $client['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($client['company_name']); ?> 
                                            (<?php echo $client['is_registered'] ? 'Registered' : 'Standalone'; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(isset($errors['client_id'])): ?><span class="error-msg"><?php echo $errors['client_id']; ?></span><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Job Details (same as employer) -->
                    <div class="form-section">
                        <h2 class="section-title">Basic Information</h2>
                        <div class="form-row full">
                            <div class="form-group">
                                <label><span class="required">*</span> Job Title</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" maxlength="255" required>
                                <?php if(isset($errors['title'])): ?><span class="error-msg"><?php echo $errors['title']; ?></span><?php endif; ?>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label><span class="required">*</span> Category</label>
                                <select name="category_id" required>
                                    <option value="">-- Select Category --</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo (isset($old['category_id']) && $old['category_id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><span class="required">*</span> Job Type</label>
                                <select name="job_type" required>
                                    <option value="">-- Select --</option>
                                    <option value="full-time" <?php echo (isset($old['job_type']) && $old['job_type']=='full-time')?'selected':''; ?>>Full-time</option>
                                    <option value="part-time" <?php echo (isset($old['job_type']) && $old['job_type']=='part-time')?'selected':''; ?>>Part-time</option>
                                    <option value="remote" <?php echo (isset($old['job_type']) && $old['job_type']=='remote')?'selected':''; ?>>Remote</option>
                                    <option value="contract" <?php echo (isset($old['job_type']) && $old['job_type']=='contract')?'selected':''; ?>>Contract</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label><span class="required">*</span> Experience</label>
                                <select name="experience_level" required>
                                    <option value="">-- Select --</option>
                                    <option value="entry" <?php echo (isset($old['experience_level']) && $old['experience_level']=='entry')?'selected':''; ?>>Entry</option>
                                    <option value="mid" <?php echo (isset($old['experience_level']) && $old['experience_level']=='mid')?'selected':''; ?>>Mid</option>
                                    <option value="senior" <?php echo (isset($old['experience_level']) && $old['experience_level']=='senior')?'selected':''; ?>>Senior</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><span class="required">*</span> Location</label>
                                <input type="text" name="location" value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label><span class="required">*</span> Deadline</label>
                                <input type="date" name="deadline" value="<?php echo htmlspecialchars($old['deadline'] ?? ''); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="section-title">Salary Range (Optional)</h2>
                        <div class="form-row">
                            <div class="form-group"><label>Min ($)</label><input type="number" name="salary_min" value="<?php echo htmlspecialchars($old['salary_min'] ?? ''); ?>"></div>
                            <div class="form-group"><label>Max ($)</label><input type="number" name="salary_max" value="<?php echo htmlspecialchars($old['salary_max'] ?? ''); ?>"></div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="section-title">Job Details</h2>
                        <div class="form-row full">
                            <div class="form-group"><label><span class="required">*</span> Description</label><textarea name="description" rows="5" required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label>Requirements</label><textarea name="requirements" rows="4"><?php echo htmlspecialchars($old['requirements'] ?? ''); ?></textarea></div>
                            <div class="form-group"><label>Benefits</label><textarea name="benefits" rows="4"><?php echo htmlspecialchars($old['benefits'] ?? ''); ?></textarea></div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="action" value="draft" class="btn btn-draft">Save as Draft</button>
                        <button type="submit" name="action" value="publish" class="btn btn-publish">Publish Job</button>
                    </div>
                </form>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
