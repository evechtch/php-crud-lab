<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'auth.php'; 
require 'config/db.php';

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
        $sql = 'SELECT id, profile_id, password_hash FROM users WHERE email = ? LIMIT 1';
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['profile_id'] = $user['profile_id'];
                $_SESSION['email'] = $email;

                header('Location: skills.php');
                exit;
            } else {
                $error = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Database query failed.';
        }
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/crud.css">
</head>
<body>
<main class="wrap">
    <div class="panel">
        <h1>Resume Lab · Login</h1>
        <p>PHP Session + MySQL Authentication</p>
        <?php if(isset($_GET['registered'])): ?><p class="ok">Registration complete. Please login.</p><?php endif; ?>
        <?php if(isset($_GET['logged_out'])): ?><p class="ok">Logged out.</p><?php endif; ?>
        <?php if($error): ?><p class="err"><?=auth_escape($error)?></p><?php endif; ?>
        <form method="post">
            <input type="hidden" name="auth_csrf" value="<?=auth_escape(auth_token())?>">
            <label>Email</label>
            <input name="email" type="email" required autocomplete="username">
            <label>Password</label>
            <input name="password" type="password" required autocomplete="current-password">
            <p><button>Login</button></p>
        </form>
        <p>New user? <a href="register.php">Register</a></p>
    </div>
</main>
</body>
</html>