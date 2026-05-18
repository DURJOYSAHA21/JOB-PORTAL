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
    <title>Company Info - HireHub</title>
    <link rel="stylesheet" href="../../assets/css/company-info.css">
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
                <h1 class="main-panel-title">Create Account</h1>
                <p class="main-panel-sub">Already have an account? <a href="../login-view.php">Log in</a></p>
            </header>
        </div>

        <div class="main-panel-form">
            <h3>Company Information</h3>
            <form method="POST" action="../../controller/company-info-controller.php" enctype="multipart/form-data">
                <div class="company-form">
                    <label for="companyname">Company Name</label>
                    <input type="text" name="companyname" id="companyname" placeholder="Company Name" value="<?php echo htmlspecialchars($oldInput['companyname'] ?? ''); ?>" required>
                    <span class="error"><?php echo $errors['companyname'] ?? ''; ?></span>

                    <label for="industry">Industry</label>
                    <input type="text" name="industry" id="industry" placeholder="Industry" value="<?php echo htmlspecialchars($oldInput['industry'] ?? ''); ?>">
                    <span class="error"><?php echo $errors['industry'] ?? ''; ?></span>

                    <label for="companysize">Company Size</label>
                    <select name="companysize" id="companysize">
                        <option value="" selected disabled>Company Size</option>
                        <option value="1-50" <?php echo (isset($oldInput['companysize']) && $oldInput['companysize'] == '1-50') ? 'selected' : ''; ?>>1-50</option>
                        <option value="51-200" <?php echo (isset($oldInput['companysize']) && $oldInput['companysize'] == '51-200') ? 'selected' : ''; ?>>51-200</option>
                        <option value="201-500" <?php echo (isset($oldInput['companysize']) && $oldInput['companysize'] == '201-500') ? 'selected' : ''; ?>>201-500</option>
                        <option value="501-1000" <?php echo (isset($oldInput['companysize']) && $oldInput['companysize'] == '501-1000') ? 'selected' : ''; ?>>501-1000</option>
                        <option value="1001-5000" <?php echo (isset($oldInput['companysize']) && $oldInput['companysize'] == '1001-5000') ? 'selected' : ''; ?>>1001-5000</option>
                        <option value="5001-10000" <?php echo (isset($oldInput['companysize']) && $oldInput['companysize'] == '5001-10000') ? 'selected' : ''; ?>>5001-10000</option>
                        <option value="10000+" <?php echo (isset($oldInput['companysize']) && $oldInput['companysize'] == '10000+') ? 'selected' : ''; ?>>10000+</option>
                    </select>
                    <span class="error"><?php echo $errors['companysize'] ?? ''; ?></span>

                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Description"><?php echo htmlspecialchars($oldInput['description'] ?? ''); ?></textarea>
                    <span class="error"><?php echo $errors['description'] ?? ''; ?></span>

                    <label for="website">Website</label>
                    <input type="url" name="website" id="website" placeholder="Website" value="<?php echo htmlspecialchars($oldInput['website'] ?? ''); ?>">
                    <span class="error"><?php echo $errors['website'] ?? ''; ?></span>

                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" placeholder="Address" value="<?php echo htmlspecialchars($oldInput['address'] ?? ''); ?>">
                    <span class="error"><?php echo $errors['address'] ?? ''; ?></span>

                    <label for="logo">Logo</label>
                    <input type="file" name="logo" id="logo">
                    <span class="error"><?php echo $errors['logo'] ?? ''; ?></span>

                    <input type="hidden" name="id" value="<?php echo $_SESSION['user']['id'] ?? ''; ?>">
                    <button type="submit" name="register">Register</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>