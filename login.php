<?php
require 'auth.php';
require 'config/db.php';

// ถ้าล็อกอินอยู่แล้ว ให้เปลี่ยนหน้าไปยัง skills.php
if (!empty($_SESSION['user_id'])) { 
    header('Location: skills.php'); 
    exit; 
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_auth_token();
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter email and password.';
    } else {
        // TODO 1: Query a single user by email using a prepared statement.
        $sql = 'SELECT id, profile_id, password_hash FROM users WHERE email = ? LIMIT 1';
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // TODO 2: bind_param, execute, bind_result, fetch
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $userId, $profileId, $hash);

            if (mysqli_stmt_fetch($stmt)) {
                // TODO 3: Verify plaintext password with password_verify
                if (password_verify($password, $hash)) {
                    mysqli_stmt_close($stmt);

                    // TODO 4: session_regenerate_id(true) & set session values
                    session_regenerate_id(true);
                    $_SESSION['user_id']    = $userId;
                    $_SESSION['profile_id'] = $profileId;
                    $_SESSION['email']      = $email;

                    // TODO 5: Redirect to skills.php and exit
                    header('Location: skills.php');
                    exit;
                }
            }
            mysqli_stmt_close($stmt);
        }
        $error = 'Invalid email or password.';
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Resume Lab · Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .top-nav {
            padding: 1.2rem 2.5rem;
            background: transparent;
        }
        .top-nav a {
            color: #0b5394;
            text-decoration: none;
            margin-right: 20px;
            font-size: 1rem;
        }
        .top-nav a.active {
            font-weight: 700;
            color: #000;
        }
        .login-card {
            max-width: 650px;
            margin: 30px auto;
            padding: 35px 40px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
            border: 1px solid #e9ecef;
        }
        .task-alert {
            background-color: #fff9db;
            border-left: 4px solid #fcc419;
            padding: 12px 16px;
            font-size: 0.9rem;
            color: #212529;
            margin-bottom: 24px;
            border-radius: 0 4px 4px 0;
        }
        .form-label {
            font-weight: 600;
            color: #212529;
            margin-bottom: 6px;
        }
        .form-control {
            padding: 9px 12px;
            border-radius: 6px;
            border: 1px solid #ced4da;
        }
        .btn-navy {
            background-color: #0b5394;
            color: #ffffff;
            font-weight: 500;
            padding: 10px;
            border-radius: 6px;
            border: none;
        }
        .btn-navy:hover {
            background-color: #083d6c;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Header Navbar ด้านบน -->
    <div class="top-nav">
        <a href="index.php">Resume</a>
        <a href="#">About</a>
        <a href="login.php" class="active">Login</a>
        <a href="register.php">Register</a>
    </div>

    <div class="login-card">
        <h2 class="fw-bold mb-4" style="color: #0b5394;">Resume Lab · Login</h2>

        <!-- กล่องแจ้งเตือนสีเหลืองตามแบบ -->
        <div class="task-alert">
            Student task: Implement PHP session login with MySQL authentication.<br>
            Current state: <?= isset($_GET['logged_out']) ? 'Logged out.' : 'Not logged in.' ?>
        </div>

        <?php if(isset($_GET['registered'])): ?>
            <div class="alert alert-success py-2">Registration complete. Please login.</div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger py-2"><?= auth_escape($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="auth_csrf" value="<?= auth_escape(auth_token()) ?>">
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control" required autocomplete="username">
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn btn-navy w-100 mb-3">Login</button>
        </form>

        <div class="mt-2">
            <span class="text-muted">New user?</span> 
            <a href="register.php" class="text-decoration-none">Register</a>
        </div>
    </div>

</body>
</html>
