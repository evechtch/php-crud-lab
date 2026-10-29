<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Lab · Login</title>
    <!-- สามารถรวมไฟล์ crud_bootstrap.php หรือ CDN ของ Bootstrap มาใส่ตรงนี้ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-custom {
            padding: 1rem 2rem;
            background-color: transparent;
        }
        .navbar-custom a {
            color: #0d6efd;
            text-decoration: none;
            margin-right: 15px;
        }
        .navbar-custom a.active {
            color: #212529;
            font-weight: bold;
        }
        .card-container {
            max-width: 650px;
            margin: 40px auto;
            padding: 35px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
        }
        .alert-custom-yellow {
            background-color: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 12px 16px;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 25px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }
        .btn-custom-blue {
            background-color: #0b5ed7;
            color: white;
            border: none;
            padding: 10px;
            font-weight: 500;
        }
        .btn-custom-blue:hover {
            background-color: #0a58ca;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar ด้านบน -->
    <div class="navbar-custom">
        <a href="index.php">Resume</a>
        <a href="about.php">About</a>
        <a href="login.php" class="active">Login</a>
        <a href="register.php">Register</a>
    </div>

    <!-- กล่อง Content การล็อกอิน (Card Container) -->
    <div class="card-container">
        <h2 class="fw-bold mb-4" style="color: #0f4c81;">Resume Lab · Login</h2>

        <!-- กล่องแจ้งเตือนสีเหลือง (Alert Message) -->
        <div class="alert-custom-yellow">
            Student task: Implement PHP session login with MySQL authentication.<br>
            Current state: <?php echo isset($_GET['logged_out']) ? 'Logged out.' : 'Not logged in.'; ?>
        </div>

        <!-- ฟอร์มเข้าสู่ระบบ (เรียงป้ายชื่อไว้ด้านบน ช่องกรอกไว้ด้านล่าง) -->
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-custom-blue w-100 mb-3">Login</button>
        </form>

        <div>
            <span class="text-muted">New user?</span> 
            <a href="register.php" class="text-decoration-none">Register</a>
        </div>
    </div>

</body>
</html>
