

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Where You Want - HireHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1f36 0%, #2d3250 50%, #1a1f36 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
            padding: 40px;
        }

        .logo {
            margin-bottom: 40px;
        }

        .logo-icon {
            font-size: 72px;
            display: block;
            margin-bottom: 10px;
            color: #7c5dfa;
        }

        .logo-name {
            font-size: 42px;
            font-weight: bold;
            color: white;
            letter-spacing: 3px;
        }

        .subtitle {
            font-size: 16px;
            color: #a0aec0;
            margin-bottom: 50px;
        }

        .button-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .login-btn {
            display: block;
            padding: 25px 30px;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .login-btn:hover {
            background: #7c5dfa;
            border-color: #7c5dfa;
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(124, 93, 250, 0.4);
        }

        .login-btn .role-icon {
            display: block;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .login-btn .role-name {
            display: block;
        }

        .login-btn .role-desc {
            display: block;
            font-size: 12px;
            font-weight: 400;
            color: #a0aec0;
            margin-top: 5px;
            letter-spacing: 0;
        }

        .login-btn:hover .role-desc {
            color: rgba(255, 255, 255, 0.8);
        }

        .footer {
            margin-top: 50px;
            color: #718096;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <span class="logo-icon">H</span>
            <span class="logo-name">HIREHUB</span>
        </div>
        <p class="subtitle">Select your portal to continue</p>

        <div class="button-grid">
            <a href="durjoy/view/login-view.php" class="login-btn">
                <span class="role-icon">E</span>
                <span class="role-name">Employer</span>
                <span class="role-desc">Post jobs & manage hiring</span>
            </a>

            <a href="progga/view/login-view.php" class="login-btn">
                <span class="role-icon">A</span>
                <span class="role-name">Admin</span>
                <span class="role-desc">Manage platform operations</span>
            </a>

            <a href="prachi/view/login_view.php" class="login-btn">
                <span class="role-icon">S</span>
                <span class="role-name">Job Seeker</span>
                <span class="role-desc">Find jobs & apply</span>
            </a>

            <a href="durjoy/view/recruiter/recruiter-login-view.php" class="login-btn">
                <span class="role-icon">R</span>
                <span class="role-name">Recruiter</span>
                <span class="role-desc">Manage clients & candidates</span>
            </a>
        </div>

        <p class="footer">The Future of Recruitment Starts Here</p>
    </div>
</body>
</html>