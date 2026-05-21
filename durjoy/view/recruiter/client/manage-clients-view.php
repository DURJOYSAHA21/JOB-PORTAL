<?php
if(session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) { header("Location: ../recruiter-login-view.php"); exit(); }
require_once "../../../model/recruiter/recruiter-client-model.php";
$recruiter_id = (int)$_SESSION['user']['id'];
$clients = getRecruiterClients($recruiter_id);
$success = $_SESSION['success'] ?? null;
$errors = $_SESSION['errors'] ?? null;
unset($_SESSION['success'], $_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Companies - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/recruiter/clients.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">HireHub</div>
            <nav>
                <a href="../recruiter-dashboard-view.php">Dashboard</a>
                <a href="../recruiter-profile-view.php">Agency Profile</a>
                <a href="manage-clients-view.php" class="active">Client Companies</a>
                <a href="../job/post-job-view.php">Post a Job</a>
                <a href="../job/manage-jobs-view.php">Manage Jobs</a>
                <a href="../candidate/candidate-search-view.php">Search Candidates</a>
                <a href="../../../controller/recruiter/recruiter-logout-controller.php">Logout</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="page-header">
                <h1>Client Companies</h1>
                <a href="add-client-view.php" class="btn btn-primary">+ Add Client</a>
            </div>
            <?php if($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
            <?php if($errors): ?><div class="alert alert-error"><?php foreach($errors as $e) echo htmlspecialchars($e).'<br>'; ?></div><?php endif; ?>

            <?php if(empty($clients)): ?>
                <div class="empty-state"><p>No client companies added yet.</p></div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header"><h2>All Clients (<?php echo count($clients); ?>)</h2></div>
                    <table>
                        <thead><tr><th>Company</th><th>Type</th><th>Jobs Posted</th><th>Added Date</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach($clients as $client): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($client['company_name']); ?></strong></td>
                                    <td><span class="badge badge-<?php echo $client['is_registered'] ? 'registered' : 'standalone'; ?>"><?php echo $client['is_registered'] ? 'Registered' : 'Standalone'; ?></span></td>
                                    <td><?php echo $client['jobs_posted']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($client['added_at'])); ?></td>
                                    <td>
                                        <a href="../job/post-job-view.php?client_id=<?php echo $client['id']; ?>" class="btn btn-sm btn-post">Post Job</a>
                                        <a href="remove-client-view.php?client_id=<?php echo $client['id']; ?>" class="btn btn-sm btn-remove" onclick="return confirm('Remove this client?')">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>