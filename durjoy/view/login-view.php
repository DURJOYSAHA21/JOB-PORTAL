<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = $_SESSION["errors"]["login"] ?? null;
$success = $_SESSION["success"] ?? null;
$oldEmail = $_SESSION['old_input']['email'] ?? '';
unset($_SESSION["errors"]["login"], $_SESSION["success"], $_SESSION['old_input']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employer Login - HireHub</title>
    <style>* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; display: flex; min-height: 100vh; }

.brand-panel { width: 40%; background: linear-gradient(135deg, #1a1f36 0%, #2d3250 100%); color: white; display: flex; align-items: center; justify-content: center; position: fixed; left: 0; top: 0; bottom: 0; }
.brand-panel-inner { text-align: center; padding: 40px; }
.brand-logo { margin-bottom: 30px; }
.brand-logo-icon { font-size: 64px; display: block; margin-bottom: 10px; color: #7c5dfa; }
.brand-logo-name { font-size: 36px; font-weight: bold; letter-spacing: 2px; }
.brand-text { margin-top: 20px; }
.brand-headline { font-size: 24px; margin-bottom: 15px; font-weight: 600; }
.brand-slogan { font-size: 14px; color: #a0aec0; line-height: 1.6; }

.main-panel { width: 60%; margin-left: 40%; padding: 40px 60px; min-height: 100vh; display: flex; flex-direction: column; justify-content: center; }
.main-panel-inner { max-width: 450px; width: 100%; margin: 0 auto; }
.main-panel-header { margin-bottom: 30px; }
.main-panel-title { font-size: 28px; color: #1a1f36; margin-bottom: 8px; }
.main-panel-sub { font-size: 14px; color: #718096; }
.main-panel-sub a { color: #7c5dfa; text-decoration: none; font-weight: 600; }
.main-panel-sub a:hover { text-decoration: underline; }

.main-panel-form { max-width: 450px; width: 100%; margin: 0 auto; }
.main-panel-form h3 { font-size: 18px; color: #1a1f36; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; }

.personal-form { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
.personal-form label { font-weight: 600; color: #4a5568; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: block; }
.personal-form input[type="email"],
.personal-form input[type="password"] { width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; font-family: inherit; color: #2d3748; margin-bottom: 15px; }
.personal-form input:focus { outline: none; border-color: #7c5dfa; box-shadow: 0 0 0 3px rgba(124,93,250,0.1); }
.personal-form input::placeholder { color: #a0aec0; }

.password-field { position: relative; }
.password-warp { position: relative; }
.password-warp input { padding-right: 45px !important; }
.eye-icon { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; cursor: pointer; color: #a0aec0; stroke: currentColor; }
.eye-icon:hover { color: #7c5dfa; }

.error { color: #e53e3e; font-size: 12px; font-weight: 500; display: block; margin-top: 2px; margin-bottom: 10px; }

.personal-form button[type="submit"] { width: 100%; padding: 12px; background: #7c5dfa; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; margin-top: 15px; }
.personal-form button[type="submit"]:hover { background: #6b4ce0; } </style>
</head>
<body>

    <aside class="brand-panel">
        <div class="brand-panel-inner">
            <div class="brand-logo">
                <span class="brand-logo-icon">H</span>
                <span class="brand-logo-name">HireHub</span>
            </div>
            <div class="brand-text">
                <h2 class="brand-headline">The Future of Recruitment Starts Here</h2>
                <p class="brand-slogan">A modern platform for job seekers, employers, and recruiters.</p>
            </div>
        </div>
    </aside>

    <main class="main-panel">
        <div class="main-panel-inner">
            <header class="main-panel-header">
                <h1 class="main-panel-title">Employer Login</h1>
                <p class="main-panel-sub">Don't have an account? <a href="register/personal-info-view.php">Register</a></p>
            </header>
        </div>

        <div class="main-panel-form">
            <h3>Sign In</h3>
            <form method="post" action="../controller/login-controller.php" id="loginForm" onsubmit="return validateLogin()">
                <div class="personal-form">
                    
                    <?php if($error): ?>
                        <span class="error"><?php echo htmlspecialchars($error); ?></span>
                    <?php endif; ?>
                    <?php if($success): ?>
                        <span class="error" style="color: #38a169;"><?php echo htmlspecialchars($success); ?></span>
                    <?php endif; ?>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($oldEmail); ?>" required>
                    <span class="error" id="email_error"></span>

                    <label for="password">Password</label>
                    <div class="password-field">
                        <div class="password-warp">
                            <input type="password" name="password" id="password" placeholder="Password" required>
                            <svg class="eye-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M1 10s3.5-6 9-6 9 6 9 6-3.5 6-9 6-9-6-9-6z" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
                        </div>
                        <span class="error" id="password_error"></span>
                    </div>

                    <button type="submit" name="login">Login</button>
                </div>
            </form>
        </div>
    </main>

    <script src="../assets/js/login.js"></script>
</body>
</html>