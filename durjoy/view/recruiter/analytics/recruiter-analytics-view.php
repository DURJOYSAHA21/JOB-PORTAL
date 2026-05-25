<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { 
    header("Location: ../recruiter-login-view.php"); exit(); 
}

require_once "../../../model/recruiter/recruiter-analytics-model.php";
require_once "../../../model/recruiter/recruiter-client-model.php";

$recruiter_user_id = (int)$_SESSION['user']['id'];
$recruiterProfileId = getRecruiterProfileId($recruiter_user_id);
$analytics = getRecruiterAnalytics($recruiterProfileId);
$clientStats = getClientSuccessRates($recruiterProfileId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recruiter Analytics - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/analytics.css">
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
                <a href="../candidate/candidate-search-view.php">Search Candidates</a>
                <a href="../outreach/outreach-list-view.php">Outreach</a>
                <a href="../application/view-applications-view.php">Applications</a>
                <a href="../candidate/candidate-pipeline-view.php">Pipeline</a>
                <a href="../placement/placement-history-view.php">Placements</a>
                <a href="recruiter-analytics-view.php" class="active">Analytics</a>
                <a href="../report/client-report-view.php">Reports</a>
                <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <h1>Recruiter Analytics</h1>
                <p>Performance metrics for your agency</p>
            </div>

            <!-- KPI Cards -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-number"><?php echo $analytics['total_outreach']; ?></div>
                    <div class="kpi-label">Total Outreach Sent</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number"><?php echo $analytics['response_rate']; ?>%</div>
                    <div class="kpi-label">Response Rate</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number"><?php echo $analytics['total_applications']; ?></div>
                    <div class="kpi-label">Applications Managed</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number"><?php echo $analytics['placement_rate']; ?>%</div>
                    <div class="kpi-label">Placement Success Rate</div>
                </div>
            </div>

            <div class="analytics-grid">
                <!-- Outreach Breakdown -->
                <div class="analytics-card">
                    <h2>Outreach Breakdown</h2>
                    <div class="breakdown-list">
                        <div class="breakdown-row">
                            <span class="breakdown-label">Sent</span>
                            <div class="breakdown-bar-wrapper">
                                <div class="breakdown-bar" style="width: 100%; background: #4299e1;"><?php echo $analytics['outreach_sent']; ?></div>
                            </div>
                        </div>
                        <div class="breakdown-row">
                            <span class="breakdown-label">Read</span>
                            <div class="breakdown-bar-wrapper">
                                <div class="breakdown-bar" style="width: <?php echo ($analytics['outreach_sent'] > 0 ? ($analytics['outreach_read'] / $analytics['outreach_sent']) * 100 : 0); ?>%; background: #ed8936;"><?php echo $analytics['outreach_read']; ?></div>
                            </div>
                        </div>
                        <div class="breakdown-row">
                            <span class="breakdown-label">Responded</span>
                            <div class="breakdown-bar-wrapper">
                                <div class="breakdown-bar" style="width: <?php echo ($analytics['outreach_sent'] > 0 ? ($analytics['outreach_responded'] / $analytics['outreach_sent']) * 100 : 0); ?>%; background: #48bb78;"><?php echo $analytics['outreach_responded']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Status Breakdown -->
                <div class="analytics-card">
                    <h2>Application Statuses</h2>
                    <div class="breakdown-list">
                        <?php 
                        $statuses = [
                            ['label' => 'Submitted', 'count' => (int)($analytics['app_submitted'] ?? 0), 'color' => '#4299e1'],
                            ['label' => 'Reviewed', 'count' => (int)($analytics['app_reviewed'] ?? 0), 'color' => '#ed8936'],
                            ['label' => 'Shortlisted', 'count' => (int)($analytics['app_shortlisted'] ?? 0), 'color' => '#48bb78'],
                            ['label' => 'Interview', 'count' => (int)($analytics['app_interview'] ?? 0), 'color' => '#9f7aea'],
                            ['label' => 'Hired', 'count' => (int)($analytics['app_hired'] ?? 0), 'color' => '#38a169'],
                        ];
                        $maxApp = max($statuses[0]['count'], $statuses[1]['count'], $statuses[2]['count'], $statuses[3]['count'], $statuses[4]['count'], 1);
                        foreach($statuses as $s): 
                            $w = ($s['count'] / $maxApp) * 100;
                        ?>
                            <div class="breakdown-row">
                                <span class="breakdown-label"><?php echo $s['label']; ?></span>
                                <div class="breakdown-bar-wrapper">
                                    <div class="breakdown-bar" style="width: <?php echo $w; ?>%; background: <?php echo $s['color']; ?>;"><?php echo $s['count']; ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Client Success Rates -->
                <div class="analytics-card full-width">
                    <h2>Placement Success Rate Per Client</h2>
                    <?php if(empty($clientStats)): ?>
                        <p class="empty-text">No client data available yet.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Total Applications</th>
                                    <th>Hired</th>
                                    <th>Success Rate</th>
                                    <th>Rate Bar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($clientStats as $cs): 
                                    $rate = $cs['total'] > 0 ? round(($cs['hired'] / $cs['total']) * 100) : 0;
                                ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($cs['client_name']); ?></strong></td>
                                        <td><?php echo $cs['total']; ?></td>
                                        <td><?php echo $cs['hired']; ?></td>
                                        <td><?php echo $rate; ?>%</td>
                                        <td>
                                            <div class="mini-bar-wrapper">
                                                <div class="mini-bar" style="width: <?php echo $rate; ?>%; background: <?php echo $rate >= 50 ? '#48bb78' : ($rate >= 25 ? '#ed8936' : '#fc8181'); ?>;"></div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>