<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { header("Location: recruiter-login-view.php"); exit(); }
require_once "../../model/recruiter/recruiter-profile-model.php";
$userId = $_SESSION['user']['id'];
$profile = getRecruiterProfile($userId);
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agency Profile - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/recruiter/profile.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
    <a href="recruiter-dashboard-view.php">Dashboard</a>
    <a href="recruiter-profile-view.php">Agency Profile</a>
    <a href="client/manage-clients-view.php">Client Companies</a>
    <a href="job/post-job-view.php">Post a Job</a>
    <a href="job/manage-jobs-view.php">Manage Jobs</a>
    <a href="job/all-jobs-view.php">All Jobs</a>
    <a href="candidate/candidate-search-view.php">Search Candidates</a>
    <a href="outreach/outreach-list-view.php">Outreach</a>
    <a href="application/view-applications-view.php">Applications</a>
    <a href="candidate/candidate-pipeline-view.php">Pipeline</a>
    <a href="placement/placement-history-view.php">Placements</a>
    <a href="analytics/recruiter-analytics-view.php">Analytics</a>
    <a href="report/client-report-view.php">Reports</a>
    <a href="../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
</nav>
        </aside>
        <main class="main-content">
            <div class="profile-container">
                <?php if($success): ?><div class="success-message"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
                <div class="profile-header">
                    <div class="profile-title">
                        <h1><?php echo $profile ? htmlspecialchars($profile['agency_name']) : 'Complete Your Profile'; ?></h1>
                        <p>Manage your agency information</p>
                    </div>
                </div>
                <form action="../../controller/recruiter/recruiter-profile-controller.php" method="POST">
                    <div class="form-section">
                        <h3>Agency Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="agencyname">Agency Name *</label>
                                <input type="text" name="agencyname" id="agencyname" value="<?php echo htmlspecialchars($profile['agency_name'] ?? ''); ?>" required>
                                <span class="error"><?php echo $errors['agencyname'] ?? ''; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="specialization">Specialization</label>
                                <input type="text" name="specialization" id="specialization" value="<?php echo htmlspecialchars($profile['specialization'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="4"><?php echo htmlspecialchars($profile['description'] ?? ''); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" name="website" id="website" value="<?php echo htmlspecialchars($profile['website'] ?? ''); ?>">
                                <span class="error"><?php echo $errors['website'] ?? ''; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3>Contact Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" disabled>
                                <small>Contact admin to change email</small>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" value="<?php echo htmlspecialchars($_SESSION['user']['phone'] ?? ''); ?>" disabled>
                                <small>Contact admin to change phone</small>
                            </div>
                        </div>
                    </div>
                    <div class="btn-container">
                        <button type="submit" class="btn-primary">Save Changes</button>
                        <a href="recruiter-dashboard-view.php" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>