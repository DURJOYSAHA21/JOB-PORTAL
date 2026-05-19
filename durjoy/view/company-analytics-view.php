<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: login-view.php");
    exit();
}

require_once "../model/application-model.php";

$employer_id = (int)$_SESSION['user']['id'];
$analytics = getCompanyAnalytics($employer_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Analytics - HireHub</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/company-analytics.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
                <a href="dashboard-view.php">Dashboard</a>
                <a href="company-profile-view.php">Company Profile</a>
                <a href="jobs/post-job-view.php">Post a Job</a>
                <a href="jobs/manage-jobs-view.php">Manage Jobs</a>
                <a href="jobs/view-applications-view.php">Applications</a>
                <a href="jobs/shortlisted-view.php">Shortlisted</a>
                <a href="company-analytics-view.php" class="active">Analytics</a>
                <a href="jobs/recruiter-relationships-view.php">Recruiters</a>
                <a href="jobs/submit-complaint-view.php">Complaints</a>
                <a href="../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Company Recruitment Analytics</h1>
                <p>Overall performance across all your job postings</p>
            </div>

            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-number"><?php echo $analytics['total_jobs']; ?></div>
                    <div class="kpi-label">Total Jobs Posted</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number"><?php echo $analytics['active_jobs']; ?></div>
                    <div class="kpi-label">Active Jobs</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number"><?php echo $analytics['total_applications']; ?></div>
                    <div class="kpi-label">Total Applications</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number"><?php echo $analytics['avg_days_to_shortlist']; ?> days</div>
                    <div class="kpi-label">Avg Time to Shortlist</div>
                </div>
            </div>

            <div class="analytics-grid">
                <div class="analytics-card">
                    <h2>Application Status Breakdown</h2>
                    <?php 
                    $totalApps = max($analytics['total_applications'], 1);
                    $statuses = [
                        ['name' => 'Submitted', 'count' => $analytics['submitted'], 'color' => '#4299e1'],
                        ['name' => 'Reviewed', 'count' => $analytics['reviewed'], 'color' => '#ed8936'],
                        ['name' => 'Shortlisted', 'count' => $analytics['shortlisted'], 'color' => '#48bb78'],
                        ['name' => 'Interview', 'count' => $analytics['interview'], 'color' => '#9f7aea'],
                        ['name' => 'Rejected', 'count' => $analytics['rejected'], 'color' => '#fc8181'],
                    ];
                    ?>
                    <div class="status-breakdown">
                        <?php foreach($statuses as $s): 
                            $pct = ($s['count'] / $totalApps) * 100;
                        ?>
                            <div class="status-row">
                                <span class="status-name"><?php echo $s['name']; ?></span>
                                <div class="status-bar-wrapper">
                                    <div class="status-bar" style="width: <?php echo $pct; ?>%; background: <?php echo $s['color']; ?>;">
                                        <?php echo $s['count'] > 0 ? $s['count'] : ''; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="analytics-card">
                    <h2>Applications Per Job</h2>
                    <?php if(empty($analytics['apps_per_job'])): ?>
                        <div class="empty-text">No jobs posted yet.</div>
                    <?php else: ?>
                        <div class="job-list">
                            <?php foreach($analytics['apps_per_job'] as $job): ?>
                                <div class="job-row">
                                    <span class="job-title"><?php echo htmlspecialchars($job['title']); ?></span>
                                    <span class="job-count"><?php echo $job['app_count']; ?> apps</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="analytics-card full-width">
                    <h2>Monthly Application Trends</h2>
                    <?php if(empty($analytics['monthly_trend'])): ?>
                        <div class="empty-text">No application data yet.</div>
                    <?php else: ?>
                        <div class="trend-chart">
                            <?php 
                            $maxTrend = 1;
                            foreach($analytics['monthly_trend'] as $m) {
                                if($m['count'] > $maxTrend) $maxTrend = $m['count'];
                            }
                            foreach($analytics['monthly_trend'] as $m):
                                $count = (int)$m['count'];
                                $height = ($count / $maxTrend) * 100;
                            ?>
                                <div class="trend-bar-wrapper">
                                    <div class="trend-value"><?php echo $count; ?></div>
                                    <div class="trend-bar" style="height: <?php echo $height; ?>%;" title="<?php echo $m['month'] . ': ' . $count; ?>"></div>
                                    <div class="trend-month"><?php echo date('M Y', strtotime($m['month'] . '-01')); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>