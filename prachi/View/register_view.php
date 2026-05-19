<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Job Seeker Register</title>
</head>
<body>
    <h1>Job Seeker Registration</h1>

    <?php
    if (isset($_SESSION['error']['register'])) {
        echo '<p style="color:red;">' . $_SESSION['error']['register'] . '</p>';
        unset($_SESSION['error']['register']);
    }

    if (isset($_SESSION['success']['register'])) {
        echo '<p style="color:green;">' . $_SESSION['success']['register'] . '</p>';
        unset($_SESSION['success']['register']);
    }
    ?>

    <form method="post" action="../Controller/register_controller.php">
        Full Name: <input type="text" name="name" placeholder="Enter your full name" required><br><br>
        Email: <input type="email" name="email" placeholder="Enter your email" required><br><br>
        Phone: <input type="text" name="phone" placeholder="Enter your phone number" required><br><br>
        Password: <input type="password" name="password" placeholder="Enter your password" required><br>
        <small>Password must be at least 8 characters and include uppercase, lowercase, number, and special character.</small><br><br>
        Confirm Password: <input type="password" name="confirm_password" placeholder="Confirm your password" required><br><br>
        <input type="submit" value="Register">
    </form>

    <p>Already have an account? <a href="login_view.php">Login here</a></p>
</body>
</html>