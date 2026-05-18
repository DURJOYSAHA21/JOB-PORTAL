<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: login-view.php");
    exit();
}

require_once "../model/company-info-model.php";

$userId = $_SESSION['user']['id'];
$companyInfo = getCompanyInfo($userId);
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Profile - HireHub</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/company-profile.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
                <a href="dashboard-view.php">Dashboard</a>
                <a href="company-profile-view.php" class="active">Company Profile</a>
                <a href="jobs/post-job-view.php">Post a Job</a>
                <a href="jobs/manage-jobs-view.php">Manage Jobs</a>
                <a href="jobs/view-applications-view.php">Applications</a>
                <a href="jobs/shortlisted-view.php">Shortlisted</a>
                <a href="company-analytics-view.php">Analytics</a>
                <a href="jobs/recruiter-relationships-view.php">Recruiters</a>
                <a href="jobs/submit-complaint-view.php">Complaints</a>
                <a href="../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="profile-container">
                <?php if($success): ?>
                    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <div class="profile-header">
                    <?php if($companyInfo && $companyInfo['logo_path']): ?>
                        <img src="../uploads/logos/<?php echo htmlspecialchars($companyInfo['logo_path']); ?>" alt="Company Logo" class="company-logo">
                    <?php else: ?>
                        <div class="company-logo-placeholder">C</div>
                    <?php endif; ?>
                    <div class="profile-title">
                        <h1><?php echo $companyInfo ? htmlspecialchars($companyInfo['company_name']) : 'Complete Your Profile'; ?></h1>
                        <p>Manage your company information and public profile</p>
                    </div>
                </div>

                <form action="../controller/company-info-controller.php" method="POST" enctype="multipart/form-data">
                    <div class="form-section">
                        <h3>Basic Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="companyname">Company Name *</label>
                                <input type="text" name="companyname" id="companyname" value="<?php echo htmlspecialchars($companyInfo['company_name'] ?? ''); ?>" required>
                                <span class="error"><?php echo $errors['companyname'] ?? ''; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="industry">Industry</label>
                                <input type="text" name="industry" id="industry" value="<?php echo htmlspecialchars($companyInfo['industry'] ?? ''); ?>">
                                <span class="error"><?php echo $errors['industry'] ?? ''; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="companysize">Company Size</label>
                                <select name="companysize" id="companysize">
                                    <option value="">Select Company Size</option>
                                    <?php
                                    $sizes = ['1-50', '51-200', '201-500', '501-1000', '1001-5000', '5001-10000', '10000+'];
                                    foreach($sizes as $size):
                                        $selected = ($companyInfo['company_size'] ?? '') == $size ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $size; ?>" <?php echo $selected; ?>><?php echo $size; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" name="website" id="website" value="<?php echo htmlspecialchars($companyInfo['website'] ?? ''); ?>">
                                <span class="error"><?php echo $errors['website'] ?? ''; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Contact & Location</h3>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($companyInfo['address'] ?? ''); ?>">
                            <span class="error"><?php echo $errors['address'] ?? ''; ?></span>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Company Description</h3>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="6"><?php echo htmlspecialchars($companyInfo['description'] ?? ''); ?></textarea>
                            <span class="error"><?php echo $errors['description'] ?? ''; ?></span>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Company Logo</h3>
                        <div class="form-group">
                            <div class="file-upload">
                                <input type="file" name="logo" id="logo" accept="image/*" onchange="previewLogo(this)">
                                <label for="logo" class="file-upload-label">Choose New Logo</label>
                            </div>
                            <span class="error"><?php echo $errors['logo'] ?? ''; ?></span>
                            <div class="current-logo-preview">
                                <p>Current logo:</p>
                                <?php if($companyInfo && $companyInfo['logo_path']): ?>
                                    <img src="../uploads/logos/<?php echo htmlspecialchars($companyInfo['logo_path']); ?>" alt="Current Logo">
                                <?php else: ?>
                                    <p>No logo uploaded yet</p>
                                <?php endif; ?>
                                <img id="logoPreview" style="display:none; margin-top:10px;">
                            </div>
                        </div>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn-primary" name="update_profile">Save Changes</button>
                        <a href="dashboard-view.php" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="../assets/js/company-profile.js"></script>
</body>
</html>