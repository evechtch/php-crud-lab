<?php
$host = "localhost";     // บนเซิร์ฟเวอร์ก็ใช้ localhost ได้เลย
$user = "s68160379";     // รหัสนิสิตของคุณ
$password = "รหัสผ่านของคุณ"; // รหัสผ่านที่ใช้เข้า phpMyAdmin ของ Angsila (ห้ามเอาโค้ดส่วนนี้ขึ้น GitHub เด็ดขาด)
$dbname = "s68160379";   // ชื่อฐานข้อมูลบน Angsila (มักจะเป็นรหัสนิสิต)

// สร้างการเชื่อมต่อ
$conn = mysqli_connect($host, $user, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// กำหนด charset ให้รองรับภาษาไทย
mysqli_set_charset($conn, "utf8mb4");
?>