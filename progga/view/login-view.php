<?php
session_start();

$errors = [];
$success = "";
$old_email = "";

if (isset($_SESSION["errors"])) {
    $errors = $_SESSION["errors"];
    unset($_SESSION["errors"]);
}

if (isset($_SESSION["success"])) {
    $success = $_SESSION["success"];
    unset($_SESSION["success"]);
}

if (isset($_SESSION["old_email"])) {
    $old_email = $_SESSION["old_email"];
    unset($_SESSION["old_email"]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Job Portal</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="auth-page">

<div class="auth-box">
    <div class="auth-left">
        <h2>Job Portal</h2>
        <p>Login to continue to your dashboard.</p>
        <img src="assets/login-picture.jpg" alt="Login picture">
    </div>

    <div class="auth-form">
        <h1>Login</h1>

        <?php if ($success != ""): ?>
            <p class="message-success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <?php if (isset($errors["login"])): ?>
            <p class="message-error"><?php echo htmlspecialchars($errors["login"]); ?></p>
        <?php endif; ?>

        <form method="post" action="../controller/login-controller.php">
            <div class="form-group">
                <label>Email</label><br>
                <input 
                    class="form-input" 
                    type="email" 
                    name="email" 
                    placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($old_email); ?>"
                >

                <?php if (isset($errors["email"])): ?>
                    <p class="message-error"><?php echo htmlspecialchars($errors["email"]); ?></p>
                <?php endif; ?>
            </div>
            <br>


            <div class="form-group">
                <label>Password</label><br>
                <input 
                    class="form-input" 
                    type="password" 
                    name="password" 
                    placeholder="Enter your password"
                >

                <?php if (isset($errors["password"])): ?>
                    <p class="message-error"><?php echo htmlspecialchars($errors["password"]); ?></p>
                <?php endif; ?>
            </div>

            <br>
            
            <button type="submit">Login</button>
        </form>

        <p>Do not have an account? <a href="signup-view.php">Sign Up</a></p>
        <p class="note">Default admin: <b>admin@gmail.com</b> / <b>123456</b></p>
    </div>
</div>

</body>
</html>