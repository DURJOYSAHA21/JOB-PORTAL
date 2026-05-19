<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['is_verified'] != 1) {
    header("Location: ../login-view.php");
    exit();
}

require_once "../../model/complaint-model.php";

$user_id = (int)$_SESSION['user']['id'];
$subjects = getComplaintSubjects($user_id);

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_input']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Complaint - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/complaints.css">
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
                <a href="submit-complaint-view.php" class="active">Complaints</a>
                <a href="../../controller/logout-controller.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Submit a Complaint</h1>
                <a href="my-complaints-view.php" class="btn-back">My Complaints</a>
            </div>

            <?php if(!empty($errors)): ?>
                <div class="alert alert-error">
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        <?php foreach($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="info-note">
                Use this form to report inappropriate behavior or policy violations. All complaints are reviewed by platform administrators.
            </div>

            <div class="form-card">
                <form method="POST" action="../../controller/complaint-controller.php">
                    <div class="form-group">
                        <label><span class="required">*</span> Who are you complaining about?</label>
                        <select name="subject_id" class="<?php echo isset($errors['subject']) ? 'input-error' : ''; ?>">
                            <option value="">-- Select Person --</option>
                            <?php foreach($subjects as $subject): ?>
                                <option value="<?php echo $subject['id']; ?>" <?php echo (isset($old['subject_id']) && $old['subject_id'] == $subject['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($subject['name']); ?> (<?php echo ucfirst($subject['role']); ?><?php echo $subject['agency_name'] ? ' - ' . htmlspecialchars($subject['agency_name']) : ''; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['subject'])): ?>
                            <span class="error-msg"><?php echo htmlspecialchars($errors['subject']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label><span class="required">*</span> Complaint Description</label>
                        <textarea name="description" class="<?php echo isset($errors['description']) ? 'input-error' : ''; ?>" placeholder="Please describe your complaint in detail..." onkeyup="updateCharCount()" maxlength="2000"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
                        <div class="char-count">
                            <span id="char-count"><?php echo strlen($old['description'] ?? ''); ?></span>/2000 characters
                        </div>
                        <?php if(isset($errors['description'])): ?>
                            <span class="error-msg"><?php echo htmlspecialchars($errors['description']); ?></span>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-submit">Submit Complaint</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        function updateCharCount() {
            const textarea = document.querySelector('textarea[name="description"]');
            document.getElementById('char-count').textContent = textarea.value.length;
        }
    </script>
</body>
</html>