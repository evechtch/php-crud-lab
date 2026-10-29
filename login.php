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
        // Query หา user จาก email
        $sql = 'SELECT id, profile_id, password_hash FROM users WHERE email = ? LIMIT 1';
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $userId, $profileId, $hash);

            if (mysqli_stmt_fetch($stmt)) {
                // ตรวจสอบรหัสผ่าน
                if (password_verify($password, $hash)) {
                    mysqli_stmt_close($stmt);

                    // เก็บค่าเข้า Session
                    session_regenerate_id(true);
                    $_SESSION['user_id']    = $userId;
                    $_SESSION['profile_id'] = $profileId;
                    $_SESSION['email']      = $email;

                    // เปลี่ยนหน้าไปยัง skills.php
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
