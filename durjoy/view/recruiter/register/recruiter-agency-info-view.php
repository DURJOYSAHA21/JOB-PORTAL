<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = $_SESSION['errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_input']);

if(!isset($_SESSION['user'])) {
    header("Location: personal-info-view.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agency Info - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/recruiter/agency-info.css">
</head>
<body>
    <aside class="brand-panel">
        <div class="brand-panel-inner">
            <div class="brand-logo">
                <span class="brand-logo-icon">H</span>
                <span class="brand-logo-name">HireHub</span>
            </div>
            <div class="brand-text">
                <h2 class="brand-headline">Connect Top Talent with Leading Companies</h2>
                <p class="brand-slogan">Manage multiple client companies and candidates from one platform.</p>
            </div>
        </div>
    </aside>

    <main class="main-panel">
        <div class="main-panel-inner">
            <header class="main-panel-header">
                <h1 class="main-panel-title">Create Account</h1>
                <p class="main-panel-sub">Already have an account? <a href="../recruiter-login-view.php">Log in</a></p>
            </header>
        </div>

        <div class="main-panel-form">
            <h3>Agency Information</h3>
            <form method="POST" action="../../../controller/recruiter/recruiter-agency-info-controller.php" enctype="multipart/form-data">
                <div class="agency-form">
                    <label for="agencyname">Agency Name</label>
                    <input type="text" name="agencyname" id="agencyname" placeholder="Agency Name" value="<?php echo htmlspecialchars($oldInput['agencyname'] ?? ''); ?>" required>
                    <span class="error"><?php echo $errors['agencyname'] ?? ''; ?></span>

                    <label for="specialization">Specialization</label>
                    <input type="text" name="specialization" id="specialization" placeholder="e.g., IT, Healthcare, Finance" value="<?php echo htmlspecialchars($oldInput['specialization'] ?? ''); ?>">
                    <span class="error"><?php echo $errors['specialization'] ?? ''; ?></span>

                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Describe your agency..."><?php echo htmlspecialchars($oldInput['description'] ?? ''); ?></textarea>
                    <span class="error"><?php echo $errors['description'] ?? ''; ?></span>

                    <label for="website">Website</label>
                    <input type="url" name="website" id="website" placeholder="https://your-agency.com" value="<?php echo htmlspecialchars($oldInput['website'] ?? ''); ?>">
                    <span class="error"><?php echo $errors['website'] ?? ''; ?></span>

                    <input type="hidden" name="id" value="<?php echo $_SESSION['user']['id'] ?? ''; ?>">
                    <button type="submit" name="register">Register</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>