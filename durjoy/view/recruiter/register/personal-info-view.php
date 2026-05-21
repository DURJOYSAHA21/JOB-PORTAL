<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = $_SESSION['errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_input']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register Recruiter - HireHub</title>
    <link rel="stylesheet" href="../../../assets/css/recruiter/personal-info.css">
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
                <h1 class="main-panel-title">Create Recruiter Account</h1>
                <p class="main-panel-sub">Already have an account? <a href="../recruiter-login-view.php">Log in</a></p>
            </header>
        </div>

        <div class="main-panel-form">
            <h3>Personal Information</h3>
            <form method="POST" action="../../../controller/recruiter/recruiter-personal-info-controller.php" id="register-form" onsubmit="return validateRegisterForm()">
                <div class="personal-form">
                    <label for="fullname">Full Name</label>
                    <input type="text" name="fullname" id="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($oldInput['fullname'] ?? ''); ?>" required>
                    <span class="error" id="fullname-error"><?php echo $errors['fullname'] ?? ''; ?></span>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($oldInput['email'] ?? ''); ?>" onkeyup="checkEmail()" required>
                    <span id="emailresponse" class="availability-msg"></span>
                    <span class="error" id="email-error"><?php echo $errors['email'] ?? ''; ?></span>

                    <label for="phone">Phone</label>
                    <input type="tel" name="phone" id="phone" placeholder="Phone" value="<?php echo htmlspecialchars($oldInput['phone'] ?? ''); ?>" required>
                    <span class="error" id="phone-error"><?php echo $errors['phone'] ?? ''; ?></span>

                    <label for="password">Password</label>
                    <div class="password-field">
                        <div class="password-warp">
                            <input type="password" name="password" id="password" placeholder="Password" required>
                            <svg class="eye-icon" viewBox="0 0 20 20" fill="none"><path d="M1 10s3.5-6 9-6 9 6 9 6-3.5 6-9 6-9-6-9-6z" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
                        </div>
                        <span class="error" id="password-error"><?php echo $errors['password'] ?? ''; ?></span>
                    </div>

                    <label for="confirm-password">Confirm Password</label>
                    <div class="password-field">
                        <div class="password-warp">
                            <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required>
                            <svg class="eye-icon" viewBox="0 0 20 20" fill="none"><path d="M3 3l14 14M8.5 8.7A2.5 2.5 0 0013 10M1 10s3.5-6 9-6c1.4 0 2.7.3 3.85.85M19 10s-1.2 2-3.15 3.85M5.5 5.7C3.2 7 1 10 1 10s3.5 6 9 6a8.7 8.7 0 004.5-1.2" stroke="currentColor" stroke-width="1.5"/></svg>
                        </div>
                        <span class="error" id="confirm-password-error"><?php echo $errors['confirm-password'] ?? ''; ?></span>
                    </div>

                    <button type="submit" name="register">Register</button>
                </div>
            </form>
            <button type="button" class="btnnext" onclick="window.location.href='recruiter-agency-info-view.php';">Next Step</button>
        </div>
    </main>

    <script src="../../assets/js/recruiter/personal-info.js"></script>
    <script src="../../controller/api/checkEmail.js"></script>
</body>
</html>