<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/application-model.php";
require_once "../../model/job-model.php";

$job_id = (int)($_GET['job_id'] ?? 0);
$employer_id = (int)$_SESSION['user']['id'];

if($job_id <= 0) {
    header("Location: manage-jobs-view.php");
    exit();
}

$funnel = getJobApplicationFunnel($job_id, $employer_id);
$timeData = getApplicationsOverTime($job_id, $employer_id);

if(!$funnel) {
    $_SESSION['errors'] = ['analytics' => 'Job not found or access denied'];
    header("Location: manage-jobs-view.php");
    exit();
}

$total = (int)$funnel['total'];
$submitted = (int)$funnel['submitted'];
$reviewed = (int)$funnel['reviewed'];
$shortlisted = (int)$funnel['shortlisted'];
$interview = (int)$funnel['interview'];
$rejected = (int)$funnel['rejected'];
$jobTitle = $funnel['job_title'];

$totalDivisor = max($total, 1);
$reviewedDivisor = max($reviewed, 1);
$reviewedRate = round(($reviewed / $totalDivisor) * 100);
$shortlistedRate = round(($shortlisted / $totalDivisor) * 100);
$interviewRate = round(($interview / $totalDivisor) * 100);
$reviewedToShortlistedRate = round(($shortlisted / $reviewedDivisor) * 100);
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/job-analytics.css">
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
                <a href="shortlisted-view.php">Shortlisted</a>
                <a href="../company-analytics-view.php">Analytics</a>
                <a href="recruiter-relationships-view.php">Recruiters</a>
                <a href="submit-complaint-view.php">Complaints</a>
                <a href="../../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1>Job Analytics</h1>
                    <p><?php echo htmlspecialchars($jobTitle); ?></p>
                </div>
                <a href="manage-jobs-view.php" class="btn-back">Back to Jobs</a>
            </div>

            <div class="summary-grid">
                <div class="summary-item">
                    <div class="number"><?php echo $total; ?></div>
                    <div class="label">Total Applications</div>
                </div>
                <div class="summary-item">
                    <div class="number"><?php echo $reviewed; ?></div>
                    <div class="label">Reviewed</div>
                </div>
                <div class="summary-item">
                    <div class="number"><?php echo $shortlisted; ?></div>
                    <div class="label">Shortlisted</div>
                </div>
                <div class="summary-item">
                    <div class="number"><?php echo $interview; ?></div>
                    <div class="label">Interview</div>
                </div>
                <div class="summary-item">
                    <div class="number"><?php echo $rejected; ?></div>
                    <div class="label">Rejected</div>
                </div>
            </div>

            <div class="analytics-grid">
                <div class="analytics-card">
                    <h2>Application Funnel</h2>
                    <div class="funnel-container">
                        <?php 
                        $steps = [
                            ['label' => 'Submitted', 'count' => $submitted, 'class' => 'bar-submitted'],
                            ['label' => 'Reviewed', 'count' => $reviewed, 'class' => 'bar-reviewed'],
                            ['label' => 'Shortlisted', 'count' => $shortlisted, 'class' => 'bar-shortlisted'],
                            ['label' => 'Interview', 'count' => $interview, 'class' => 'bar-interview'],
                        ];
                        $maxCount = max($submitted, $reviewed, $shortlisted, $interview, 1);
                        foreach($steps as $step): 
                            $width = ($step['count'] / $maxCount) * 100;
                        ?>
                            <div class="funnel-step">
                                <span class="funnel-label"><?php echo $step['label']; ?></span>
                                <div class="funnel-bar-wrapper">
                                    <div class="funnel-bar <?php echo $step['class']; ?>" style="width: <?php echo $width; ?>%;">
                                        <?php echo $step['count']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="analytics-card">
                    <h2>Conversion Rates</h2>
                    <div class="conversion-grid">
                        <div class="conversion-item">
                            <h3><?php echo $reviewedRate; ?>%</h3>
                            <p>Submitted to Reviewed</p>
                        </div>
                        <div class="conversion-item">
                            <h3><?php echo $shortlistedRate; ?>%</h3>
                            <p>Total to Shortlisted</p>
                        </div>
                        <div class="conversion-item">
                            <h3><?php echo $interviewRate; ?>%</h3>
                            <p>Total to Interview</p>
                        </div>
                        <div class="conversion-item">
                            <h3><?php echo $reviewedToShortlistedRate; ?>%</h3>
                            <p>Reviewed to Shortlisted</p>
                        </div>
                    </div>
                </div>

                <div class="analytics-card full-width">
                    <h2>Applications Over Time</h2>
                    <?php if(empty($timeData)): ?>
                        <div class="chart-empty">No application data available yet.</div>
                    <?php else: ?>
                        <div class="chart-container">
                            <div class="bar-chart">
                                <?php 
                                $maxVal = 1;
                                foreach($timeData as $point) {
                                    if($point['count'] > $maxVal) $maxVal = $point['count'];
                                }
                                foreach($timeData as $point): 
                                    $count = $point['count'];
                                    $height = ($count / $maxVal) * 100;
                                ?>
                                    <div class="bar-item">
                                        <div class="bar-value"><?php echo $count; ?></div>
                                        <div class="bar" style="height: <?php echo $height; ?>%;" title="<?php echo $point['date'] . ': ' . $count . ' applications'; ?>"></div>
                                        <div class="bar-date"><?php echo date('M d', strtotime($point['date'])); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>