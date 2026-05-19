<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/application-model.php";

$app_id = (int)($_GET['app_id'] ?? 0);
$employer_id = (int)$_SESSION['user']['id'];

if($app_id <= 0) {
    header("Location: view-applications-view.php");
    exit();
}

$application = getApplicationById($app_id, $employer_id);

if(!$application) {
    $_SESSION['errors'] = ['app' => 'Application not found or access denied'];
    header("Location: view-applications-view.php");
    exit();
}
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/applicant-detail.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
                <a href="../dashboard-view.php">Dashboard</a>
                <a href="../company-profile-view.php">Company Profile</a>
                <a href="post-job-view.php">Post a Job</a>
                <a href="manage-jobs-view.php">Manage Jobs</a>
                <a href="view-applications-view.php" class="active">Applications</a>
                <a href="shortlisted-view.php">Shortlisted</a>
                <a href="../company-analytics-view.php">Analytics</a>
                <a href="recruiter-relationships-view.php">Recruiters</a>
                <a href="submit-complaint-view.php">Complaints</a>
                <a href="../../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Applicant Detail</h1>
                <a href="view-applications-view.php" class="btn-back">Back to Applications</a>
            </div>

            <div class="detail-grid">
                <div class="detail-card">
                    <h2>Seeker Profile</h2>
                    <div class="info-row">
                        <span class="info-label">Full Name</span>
                        <span class="info-value"><strong><?php echo $application['applicant_name']; ?></strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?php echo $application['applicant_email']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value"><?php echo $application['applicant_phone'] ?? 'Not provided'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Headline</span>
                        <span class="info-value"><?php echo $application['headline'] ?? 'Not provided'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Experience</span>
                        <span class="info-value"><?php echo $application['years_experience'] ?? 'N/A'; ?> years</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Education</span>
                        <span class="info-value"><?php echo $application['education_level'] ?? 'Not specified'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Current Salary</span>
                        <span class="info-value"><?php echo $application['current_salary'] ? '$' . number_format($application['current_salary']) : 'Not disclosed'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Expected Salary</span>
                        <span class="info-value"><?php echo $application['expected_salary'] ? '$' . number_format($application['expected_salary']) : 'Not disclosed'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Location</span>
                        <span class="info-value"><?php echo htmlspecialchars($application['preferred_location'] ?? 'Not specified'); ?></span>
                    </div>
                    <?php if(!empty($application['skills'])): ?>
                    <div class="info-row">
                        <span class="info-label">Skills</span>
                        <span class="info-value">
                            <div class="skills-list">
                                <?php foreach(explode(',', $application['skills']) as $skill): ?>
                                    <span class="skill-tag"><?php echo trim($skill); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($application['summary'])): ?>
                    <div class="info-row">
                        <span class="info-label">Summary</span>
                        <span class="info-value"><?php echo nl2br($application['summary']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="detail-card">
                    <h2>Application Details</h2>
                    <div class="info-row">
                        <span class="info-label">Job Applied</span>
                        <span class="info-value"><strong><?php echo $application['job_title']; ?></strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="status-badge status-<?php echo strtolower($application['status']); ?>"><?php echo ucfirst($application['status']); ?></span>
                            <select id="status-dropdown" class="status-dropdown" onchange="updateStatus(<?php echo $application['id']; ?>)">
                                <option value="submitted" <?php echo $application['status'] === 'submitted' ? 'selected' : ''; ?>>Submitted</option>
                                <option value="reviewed" <?php echo $application['status'] === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                <option value="shortlisted" <?php echo $application['status'] === 'shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                                <option value="interview" <?php echo $application['status'] === 'interview' ? 'selected' : ''; ?>>Interview</option>
                                <option value="rejected" <?php echo $application['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                            <span id="status-message" class="status-msg"></span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Applied On</span>
                        <span class="info-value"><?php echo date('F d, Y - h:i A', strtotime($application['applied_at'])); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Resume</span>
                        <span class="info-value">
                            <?php $resumeFile = $application['resume_path'] ?: ($application['seeker_resume'] ?? ''); ?>
                            <?php if($resumeFile): ?>
                                <a href="../../uploads/resumes/<?php echo $resumeFile; ?>" target="_blank" class="btn btn-download">Download Resume</a>
                            <?php else: ?>
                                <span style="color: #a0aec0;">No resume uploaded</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"></span>
                        <span class="info-value">
                            <a href="message-applicant-view.php?app_id=<?php echo $application['id']; ?>" class="btn btn-message">Send Message</a>
                            <a href="submit-complaint-view.php?subject_id=<?php echo $application['seeker_user_id']; ?>" class="btn btn-complain">Report</a>
                        </span>
                    </div>
                </div>

                <div class="detail-card full-width">
                    <h2>Cover Letter</h2>
                    <?php if(!empty($application['cover_letter'])): ?>
                        <div class="cover-letter-box"><?php echo nl2br($application['cover_letter']); ?></div>
                    <?php else: ?>
                        <div class="cover-letter-empty">No cover letter submitted.</div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="../../controller/api/applicant-detail.js"></script>
</body>
</html>