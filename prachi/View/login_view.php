<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Job Seeker Login</title>
</head>
<body>
    <h1>Job Seeker Login</h1>

    <?php
    if (isset($_SESSION['error']['login'])) {
        echo '<p style="color:red;">' . $_SESSION['error']['login'] . '</p>';
        unset($_SESSION['error']['login']);
    }
    ?>

    <form method="post" action="../Controller/login_controller.php">
        Email: <input type="email" name="email" placeholder="Enter your email" required><br><br>
        Password: <input type="password" name="password" placeholder="Enter your password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <p>New seeker? <a href="register_view.php">Register here</a></p>
</body>
</html>