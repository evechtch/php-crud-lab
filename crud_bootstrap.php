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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>'.h($title).'</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f7f9fc; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
        }
        .top-nav { 
            max-width: 900px;
            margin: 20px auto 10px;
            padding: 0 10px;
        }
        .top-nav a { 
            color: #0b5c96; 
            text-decoration: underline; 
            margin-right: 15px; 
            font-size: 0.95rem; 
        }
        .app-card { 
            max-width: 900px; 
            margin: 10px auto 40px; 
            padding: 40px 45px; 
            background: #ffffff; 
            border-radius: 16px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); 
            border: 1px solid #e2e8f0; 
        }
        h2, h1, .h2 { 
            color: #0b5c96; 
            font-weight: 700; 
            font-size: 1.85rem; 
            margin-bottom: 20px;
        }
        
        /* สไตล์ Alert แถบเตือนของอาจารย์ */
        .alert, div.alert { 
            background-color: #fef8dd; 
            color: #524312; 
            border: none; 
            border-left: 4px solid #d4a72c; 
            border-radius: 4px; 
            padding: 12px 16px; 
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .alert-success, div.alert-success { 
            background-color: #e6f6ee !important; 
            color: #1b5e3a !important; 
            border-left: none !important; 
            border-radius: 8px !important;
            padding: 12px 16px !important;
        }

        /* สไตล์ Form Elements */
        label { 
            display: block; 
            font-weight: 700; 
            color: #1a202c; 
            margin-top: 15px; 
            margin-bottom: 6px; 
            font-size: 0.9rem;
        }
        input[type="text"], input:not([type]), select, .form-control, .form-select { 
            width: 100%;
            max-width: 500px;
            padding: 8px 12px; 
            border: 1px solid #cbd5e1; 
            border-radius: 8px; 
            outline: none;
            font-size: 0.95rem;
            margin-bottom: 10px;
        }
        input[type="text"]:focus, select:focus {
            border-color: #0b5c96;
            box-shadow: 0 0 0 2px rgba(11, 92, 150, 0.2);
        }

        /* สไตล์ Buttons */
        button, .btn-primary, button[type="submit"]:not(.danger) { 
            background-color: #0b5c96; 
            color: #ffffff; 
            border: none; 
            padding: 8px 18px; 
            border-radius: 8px; 
            font-weight: 500; 
            font-size: 0.9rem;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover { background-color: #084875; }
        
        button.danger, button[type="submit"].danger { 
            background-color: #b91c1c; 
            color: #ffffff; 
        }
        button.danger:hover { background-color: #991b1b; }

        /* สไตล์ Table หน้า skills.php */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
        }
        th { 
            text-align: left; 
            padding: 12px 8px; 
            font-weight: 700; 
            color: #1a202c; 
            border-bottom: 1px solid #e2e8f0; 
            font-size: 0.9rem;
        }
        td { 
            padding: 14px 8px; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 0.95rem;
            color: #334155;
        }
        td a { 
            color: #0b5c96; 
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="top-nav">
        <a href="index.php">Resume</a>
        <a href="skills.php">Manage Skills</a>
        <a href="skill_create.php">+ Add Skill</a>
    </div>
    <div class="app-card">
        <h2>'.h($title).'</h2>';
}

function page_end() { 
    echo '</div></body></html>'; 
}
