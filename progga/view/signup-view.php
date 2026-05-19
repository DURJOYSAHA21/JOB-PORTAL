<?php
session_start();

$errors = $_SESSION["errors"]["signup"] ?? [];
$old = $_SESSION["old"]["signup"] ?? [
    "name" => "",
    "email" => "",
    "phone" => "",
    "role" => ""
];

unset($_SESSION["errors"]["signup"]);
unset($_SESSION["old"]["signup"]);

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Job Portal</title>
    <link rel="stylesheet" href="assets/admin.css">

    <style>
        .field-error {
            color: red;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .signup-image {
    width: 990px;
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
    display: block;
    margin: 40px auto 0;
}
    </style>
</head>
<body class="auth-page">

<div class="auth-box">
    <div class="auth-left">
        <h2>Create Account</h2>
       <br>
        <img class="signup-image" src="assets/signup-picture.jpg" alt="Signup picture">
    </div>

    <div class="auth-form">
        <h1>Sign Up</h1>

        <form method="post" action="../controller/signup-controller.php">

            <div class="form-group">
                <label>Full Name</label><br>
                <input 
                    class="form-input" 
                    type="text" 
                    name="name" 
                    placeholder="Your name"
                    value="<?php echo e($old["name"]); ?>"
                >

                <?php if (isset($errors["name"])): ?>
                    <br><span class="field-error"><?php echo e($errors["name"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Email</label><br>
                <input 
                    class="form-input" 
                    type="email" 
                    name="email" 
                    placeholder="example@gmail.com"
                    value="<?php echo e($old["email"]); ?>"
                >

                <?php if (isset($errors["email"])): ?>
                    <br><span class="field-error"><?php echo e($errors["email"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Phone</label><br>
                <input 
                    class="form-input" 
                    type="text" 
                    name="phone" 
                    placeholder="01XXXXXXXXX"
                    value="<?php echo e($old["phone"]); ?>"
                >

                <?php if (isset($errors["phone"])): ?>
                    <br><span class="field-error"><?php echo e($errors["phone"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Role</label><br>
                <select class="form-input" name="role">
                    <option value="">Select Role</option>

                    <option value="seeker" <?php echo $old["role"] === "seeker" ? "selected" : ""; ?>>
                        Job Seeker
                    </option>

                    <option value="employer" <?php echo $old["role"] === "employer" ? "selected" : ""; ?>>
                        Employer
                    </option>

                    <option value="recruiter" <?php echo $old["role"] === "recruiter" ? "selected" : ""; ?>>
                        Recruiter
                    </option>
                </select>

                <?php if (isset($errors["role"])): ?>
                    <br><span class="field-error"><?php echo e($errors["role"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Password</label><br>
                <input 
                    class="form-input" 
                    type="password" 
                    name="password" 
                    placeholder="Minimum 6 characters"
                >

                <?php if (isset($errors["password"])): ?>
                    <br><span class="field-error"><?php echo e($errors["password"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Confirm Password</label><br>
                <input 
                    class="form-input" 
                    type="password" 
                    name="confirm_password" 
                    placeholder="Retype password"
                >

                <?php if (isset($errors["confirm_password"])): ?>
                    <br><span class="field-error"><?php echo e($errors["confirm_password"]); ?></span>
                <?php endif; ?>
            </div>

            <button type="submit">Create Account</button>
        </form>

        <p>Already have an account? <a href="login-view.php">Login</a></p>
    </div>
</div>

</body>
</html>