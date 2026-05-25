<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { 
    header("Location: ../recruiter-login-view.php"); exit(); 
}

require_once "../../../model/recruiter/recruiter-candidate-search-model.php";
$allCandidates = getAllCandidates();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Candidates - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/candidate-search.css">
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
                <h1>Search Candidates</h1>
                <p>Find the perfect candidate by skills, location, experience, and salary</p>
            </div>

            <!-- Search Form -->
            <div class="search-card">
                <div class="search-row">
                    <div class="search-group">
                        <label>Keywords / Skills</label>
                        <input type="text" id="search-keyword" placeholder="e.g., PHP, React, Manager" onkeyup="debounceSearch()">
                    </div>
                    <div class="search-group">
                        <label>Location</label>
                        <input type="text" id="search-location" placeholder="e.g., New York, Remote" onkeyup="debounceSearch()">
                    </div>
                    <div class="search-group">
                        <label>Experience Level</label>
                        <select id="search-experience" onchange="searchCandidates()">
                            <option value="">All Levels</option>
                            <option value="entry">Entry Level (0-2 yrs)</option>
                            <option value="mid">Mid Level (3-5 yrs)</option>
                            <option value="senior">Senior Level (5+ yrs)</option>
                        </select>
                    </div>
                    <div class="search-group">
                        <label>Max Expected Salary ($)</label>
                        <input type="number" id="search-salary" placeholder="e.g., 80000" onkeyup="debounceSearch()">
                    </div>
                    <div class="search-group" style="justify-content: flex-end;">
                        <button type="button" class="btn btn-reset" onclick="resetSearch()">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="results-card">
                <div class="results-header">
                    <h2>Candidates</h2>
                    <span class="result-count" id="result-count"><?php echo count($allCandidates); ?> found</span>
                </div>
                <div id="candidates-results">
                    <?php if(empty($allCandidates)): ?>
                        <p class="empty-state">No candidates found in the database.</p>
                    <?php else: ?>
                        <?php foreach($allCandidates as $c): ?>
                            <div class="candidate-card">
                                <div class="candidate-header">
                                    <div>
                                        <div class="candidate-name"><?php echo htmlspecialchars($c['name']); ?></div>
                                        <div class="candidate-headline"><?php echo htmlspecialchars($c['headline'] ?? 'No headline'); ?></div>
                                    </div>
                                    <a href="candidate-profile-view.php?seeker_id=<?php echo $c['user_id']; ?>" class="btn btn-view">View Profile</a>
                                </div>
                                <div class="candidate-meta">
                                    <span>Exp: <?php echo htmlspecialchars($c['years_experience'] ?? 'N/A'); ?> yrs</span>
                                    <span>Location: <?php echo htmlspecialchars($c['preferred_location'] ?? 'N/A'); ?></span>
                                    <span>Education: <?php echo htmlspecialchars($c['education_level'] ?? 'N/A'); ?></span>
                                    <span>Expected: $<?php echo number_format($c['expected_salary'] ?? 0); ?></span>
                                </div>
                                <?php if(!empty($c['skills'])): ?>
                                    <div class="skills-list">
                                        <?php foreach(array_slice(explode(',', $c['skills']), 0, 5) as $skill): ?>
                                            <span class="skill-tag"><?php echo htmlspecialchars(trim($skill)); ?></span>
                                        <?php endforeach; ?>
                                        <?php if(count(explode(',', $c['skills'])) > 5): ?>
                                            <span class="skill-tag">+more</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($c['summary'])): ?>
                                    <div class="candidate-summary"><?php echo htmlspecialchars(substr($c['summary'], 0, 200)); ?>...</div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="../../../controller/api/recruiter/candidate-search.js"></script>
</body>
</html>