<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/config/db.php';

if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));

function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function csrf_field() { echo '<input type="hidden" name="csrf" value="'.h($_SESSION['csrf']).'">'; }
function verify_csrf() { if (!hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? ''))) { http_response_code(403); exit('Invalid CSRF token'); } }
function valid_id($value) { $n = filter_var($value, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]); return $n === false ? null : $n; }
function profile_id($conn) { return (int)$_SESSION['profile_id']; }

function page_start($title) {
    echo '<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>'.h($title).'</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: system-ui, -apple-system, sans-serif; }
        .top-nav { padding: 1.2rem 2.5rem; background: transparent; }
        .top-nav a { color: #0b5394; text-decoration: none; margin-right: 20px; font-size: 1rem; }
        .top-nav a.active { font-weight: 700; color: #000; }
        .app-card { max-width: 750px; margin: 30px auto; padding: 35px 40px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.03); border: 1px solid #e9ecef; }
        .btn-navy { background-color: #0b5394; color: #ffffff; font-weight: 500; border: none; }
        .btn-navy:hover { background-color: #083d6c; color: #ffffff; }
        .form-label { font-weight: 600; color: #212529; }
        .form-control, .form-select { padding: 9px 12px; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="top-nav">
        <a href="index.php">Resume</a>
        <a href="skills.php" class="active">Manage Skills</a>
        <a href="skill_create.php">+ Add Skill</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="app-card">
        <h2 class="fw-bold mb-4" style="color: #0b5394;">'.h($title).'</h2>';
}

function page_end() { 
    echo '</div></body></html>'; 
}
