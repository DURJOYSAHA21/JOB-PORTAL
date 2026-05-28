<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { 
    header("Location: ../recruiter-login-view.php"); exit(); 
}

require_once "../../../model/recruiter/recruiter-candidate-search-model.php";

$seeker_user_id = (int)($_GET['seeker_id'] ?? 0);
if($seeker_user_id <= 0) { header("Location: candidate-search-view.php"); exit(); }

$profile = getSeekerPublicProfile($seeker_user_id);
if(!$profile) { header("Location: candidate-search-view.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($profile['name']); ?> - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/candidate-profile.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
<nav>
    <a href="../recruiter-dashboard-view.php">Dashboard</a>
    <a href="../recruiter-profile-view.php">Agency Profile</a>
    <a href="../client/manage-clients-view.php">Client Companies</a>
    <a href="../job/post-job-view.php">Post a Job</a>
    <a href="../job/manage-jobs-view.php">Manage Jobs</a>
    <a href="../job/all-jobs-view.php">All Jobs</a>
    <a href="candidate-search-view.php">Search Candidates</a>
    <a href="../outreach/outreach-list-view.php">Outreach</a>
    <a href="../application/view-applications-view.php">Applications</a>
    <a href="candidate-pipeline-view.php">Pipeline</a>
    <a href="../placement/placement-history-view.php">Placements</a>
    <a href="../analytics/recruiter-analytics-view.php">Analytics</a>
    <a href="../report/client-report-view.php">Reports</a>
    <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
</nav>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <h1>Candidate Profile</h1>
                <a href="candidate-search-view.php" class="btn-back">Back to Search</a>
                <a href="../outreach/send-outreach-view.php?seeker_id=<?php echo $seeker_user_id; ?>" class="btn btn-primary">Send Outreach</a>
            </div>

            <div class="profile-grid">
                <!-- Basic Info Card -->
                <div class="profile-card">
                    <h2>Personal Information</h2>
                    <div class="info-row">
                        <span class="info-label">Full Name</span>
                        <span class="info-value"><strong><?php echo htmlspecialchars($profile['name']); ?></strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?php echo htmlspecialchars($profile['email']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value"><?php echo htmlspecialchars($profile['phone'] ?? 'Not provided'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Headline</span>
                        <span class="info-value"><?php echo htmlspecialchars($profile['headline'] ?? 'Not provided'); ?></span>
                    </div>
                </div>

                <!-- Professional Info Card -->
                <div class="profile-card">
                    <h2>Professional Details</h2>
                    <div class="info-row">
                        <span class="info-label">Experience</span>
                        <span class="info-value"><?php echo htmlspecialchars($profile['years_experience'] ?? 'N/A'); ?> years</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Education</span>
                        <span class="info-value"><?php echo htmlspecialchars($profile['education_level'] ?? 'Not specified'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Current Salary</span>
                        <span class="info-value"><?php echo $profile['current_salary'] ? '$' . number_format($profile['current_salary']) : 'Not disclosed'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Expected Salary</span>
                        <span class="info-value"><?php echo $profile['expected_salary'] ? '$' . number_format($profile['expected_salary']) : 'Not disclosed'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Preferred Location</span>
                        <span class="info-value"><?php echo htmlspecialchars($profile['preferred_location'] ?? 'Not specified'); ?></span>
                    </div>
                </div>

                <!-- Skills Card -->
                <div class="profile-card full-width">
                    <h2>Skills</h2>
                    <?php if(!empty($profile['skills'])): ?>
                        <div class="skills-list">
                            <?php foreach(explode(',', $profile['skills']) as $skill): ?>
                                <span class="skill-tag"><?php echo htmlspecialchars(trim($skill)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No skills listed.</p>
                    <?php endif; ?>
                </div>

                <!-- Summary Card -->
                <?php if(!empty($profile['summary'])): ?>
                <div class="profile-card full-width">
                    <h2>Summary</h2>
                    <div class="summary-box"><?php echo nl2br(htmlspecialchars($profile['summary'])); ?></div>
                </div>
                <?php endif; ?>

                <!-- Resume Card -->
                <div class="profile-card full-width">
                    <h2>Resume</h2>
                    <?php if(!empty($profile['resume_path'])): ?>
                        <a href="../../../uploads/resumes/<?php echo htmlspecialchars($profile['resume_path']); ?>" 
                           target="_blank" class="btn btn-download">Download Resume</a>
                    <?php else: ?>
                        <p class="text-muted">Resume not publicly available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>