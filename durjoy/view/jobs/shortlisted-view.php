<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/application-model.php";

$employer_id = (int)$_SESSION['user']['id'];
$candidates = getShortlistedCandidates($employer_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shortlisted Candidates - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/shortlisted.css">
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
                <a href="view-applications-view.php">Applications</a>
                <a href="shortlisted-view.php" class="active">Shortlisted</a>
                <a href="../company-analytics-view.php">Analytics</a>
                <a href="recruiter-relationships-view.php">Recruiters</a>
                <a href="submit-complaint-view.php">Complaints</a>
                <a href="../../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Shortlisted Candidates</h1>
                <p>All shortlisted candidates across your job postings</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Shortlisted</h2>
                    <span class="count-badge"><?php echo count($candidates); ?> candidate(s)</span>
                </div>

                <?php if(empty($candidates)): ?>
                    <div class="empty-state">
                        <p>No shortlisted candidates yet.</p>
                        <a href="view-applications-view.php" class="btn btn-view">Browse Applications</a>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Headline</th>
                                <th>Skills</th>
                                <th>Experience</th>
                                <th>Job Title</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($candidates as $candidate): ?>
                                <tr>
                                    <td>
                                        <div class="candidate-name"><?php echo htmlspecialchars($candidate['applicant_name']); ?></div>
                                        <div class="candidate-email"><?php echo htmlspecialchars($candidate['applicant_email']); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($candidate['headline'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if(!empty($candidate['skills'])): ?>
                                            <div class="skills-list">
                                                <?php 
                                                $skills = array_slice(explode(',', $candidate['skills']), 0, 3);
                                                foreach($skills as $skill): ?>
                                                    <span class="skill-tag"><?php echo htmlspecialchars(trim($skill)); ?></span>
                                                <?php endforeach; ?>
                                                <?php if(count(explode(',', $candidate['skills'])) > 3): ?>
                                                    <span class="skill-tag">+more</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">--</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($candidate['years_experience'] ?? 'N/A'); ?> yrs</td>
                                    <td><?php echo htmlspecialchars($candidate['job_title']); ?></td>
                                    <td><span class="status-badge status-shortlisted">Shortlisted</span></td>
                                    <td><?php echo date('M d, Y', strtotime($candidate['applied_at'])); ?></td>
                                    <td>
                                        <div class="actions-cell">
                                            <a href="applicant-detail-view.php?app_id=<?php echo $candidate['id']; ?>" class="btn btn-view">View</a>
                                            <a href="message-applicant-view.php?app_id=<?php echo $candidate['id']; ?>" class="btn btn-message">Message</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>