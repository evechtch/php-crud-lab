<?php
require 'auth.php';
require 'config/db.php';

if (!empty($_SESSION['user_id'])) { 
    header('Location: skills.php'); 
    exit; 
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_auth_token();
    $name = trim((string)($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $pass = (string)($_POST['password'] ?? '');

    if (mb_strlen($name) < 2 || mb_strlen($name) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190 || strlen($pass) < 10) {
        $error = 'Provide a name, valid email, and password of at least 10 characters.';
    } else {
        mysqli_begin_transaction($conn);
        try {
            $q = mysqli_prepare($conn, 'INSERT INTO profile (full_name, email, headline) VALUES (?, ?, ?)');
            $headline = 'Student'; 
            mysqli_stmt_bind_param($q, 'sss', $name, $email, $headline);
            if (!mysqli_stmt_execute($q)) throw new Exception('profile');
            $pid = mysqli_insert_id($conn); 
            mysqli_stmt_close($q);

            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $q = mysqli_prepare($conn, 'INSERT INTO users (profile_id, email, password_hash) VALUES (?, ?, ?)');
            mysqli_stmt_bind_param($q, 'iss', $pid, $email, $hash);
            if (!mysqli_stmt_execute($q)) throw new Exception('user');
            mysqli_stmt_close($q);

            mysqli_commit($conn); 
            header('Location: login.php?registered=1'); 
            exit;
        } catch (Throwable $e) {
            mysqli_rollback($conn); 
            $error = 'Could not create account. Email may already be in use.';
        }
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resume Lab · Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: system-ui, -apple-system, sans-serif; }
        .top-nav { padding: 1.2rem 2.5rem; background: transparent; }
        .top-nav a { color: #0b5394; text-decoration: none; margin-right: 20px; font-size: 1rem; }
        .top-nav a.active { font-weight: 700; color: #000; }
        .app-card { max-width: 650px; margin: 30px auto; padding: 35px 40px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.03); border: 1px solid #e9ecef; }
        .form-label { font-weight: 600; color: #212529; margin-bottom: 6px; }
        .form-control { padding: 9px 12px; border-radius: 6px; border: 1px solid #ced4da; }
        .btn-navy { background-color: #0b5394; color: #ffffff; font-weight: 500; padding: 10px; border-radius: 6px; border: none; }
        .btn-navy:hover { background-color: #083d6c; color: #ffffff; }
    </style>
</head>
<body>

    <div class="top-nav">
        <a href="index.php">Resume</a>
        <a href="#">About</a>
        <a href="login.php">Login</a>
        <a href="register.php" class="active">Register</a>
    </div>

    <div class="app-card">
        <h2 class="fw-bold mb-1" style="color: #0b5394;">Register</h2>
        <p class="text-muted mb-4">สร้างบัญชีสำหรับ Resume CRUD Lab</p>

        <?php if($error): ?>
            <div class="alert alert-danger py-2"><?= auth_escape($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="auth_csrf" value="<?= auth_escape(auth_token()) ?>">
            
            <div class="mb-3">
                <label class="form-label">Full name</label>
                <input name="full_name" class="form-control" required maxlength="150">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control" required maxlength="190">
            </div>

            <div class="mb-4">
                <label class="form-label">Password (10+ characters)</label>
                <input name="password" type="password" class="form-control" required minlength="10">
            </div>

            <button type="submit" class="btn btn-navy w-100 mb-3">Create account</button>
        </form>

        <div class="mt-2">
            <span class="text-muted">Already have an account?</span> 
            <a href="login.php" class="text-decoration-none">Login</a>
        </div>
    </div>

</body>
</html>
