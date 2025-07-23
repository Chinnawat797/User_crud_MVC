<?php
// public/index.php

session_start(); // เริ่มต้น session ทุกครั้งที่โหลดหน้า

require_once __DIR__ . '/../app/controllers/UserController.php';

$controller = new UserController();

$action = $_GET['action'] ?? 'index'; // Default action คือ 'index'

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'delete':
        $controller->delete();
        break;
    default:
        // ถ้า action ไม่ถูกต้อง ให้ redirect ไปที่หน้าหลัก หรือแสดงหน้า 404
        header('Location: index.php?action=index');
        exit();
}