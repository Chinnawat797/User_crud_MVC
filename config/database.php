<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // เปลี่ยนเป็น username ของคุณ
define('DB_PASS', '');     // เปลี่ยนเป็น password ของคุณ
define('DB_NAME', 'user_crud_db'); // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // echo "Connected to database successfully!"; // สำหรับทดสอบการเชื่อมต่อ
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>