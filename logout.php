<?php
require 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_auth_token();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    header('Location: login.php?logged_out=1');
    exit;
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resume Lab · Logout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: system-ui, -apple-system, sans-serif; }
        .top-nav { padding: 1.2rem 2.5rem; background: transparent; }
        .top-nav a { color: #0b5394; text-decoration: none; margin-right: 20px; font-size: 1rem; }
        .top-nav a.active { font-weight: 700; color: #000; }
        .app-card { max-width: 500px; margin: 40px auto; padding: 35px 40px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.03); border: 1px solid #e9ecef; text-align: center; }
        .btn-navy { background-color: #0b5394; color: #ffffff; font-weight: 500; border: none; }
        .btn-navy:hover { background-color: #083d6c; color: #ffffff; }
    </style>
</head>
<body>

    <div class="top-nav">
        <a href="index.php">Resume</a>
        <a href="skills.php">Manage Skills</a>
        <a href="logout.php" class="active">Logout</a>
    </div>

    <div class="app-card">
        <h3 class="fw-bold mb-3" style="color: #0b5394;">Log out?</h3>
        <p class="text-muted mb-4">คุณต้องการออกจากระบบหรือไม่?</p>

        <form method="post">
            <input type="hidden" name="auth_csrf" value="<?= auth_escape(auth_token()) ?>">
            <button type="submit" class="btn btn-navy w-100 mb-2">Confirm logout</button>
            <a href="skills.php" class="btn btn-light border w-100">Cancel</a>
        </form>
    </div>

</body>
</html>
