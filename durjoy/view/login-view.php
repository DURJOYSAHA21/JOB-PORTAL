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
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="box">
        <h1>Employer Login</h1>

        <?php if($error): ?>
            <p class="server-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

       

        <form method="post" action="../controller/login-controller.php" id="loginForm" onsubmit="return validateLogin()">
            <input type="email" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($oldEmail); ?>">
            <span class="error-msg" id="email_error"></span>

            <input type="password" name="password" id="password" placeholder="Password">
            <span class="error-msg" id="password_error"></span>

            <input type="submit" value="Login">
        </form>
        <a href="register/personal-info-view.php">Don't have an account? Register</a>
    </div>

    <script src="../assets/js/login.js"></script>
</body>
</html>