<?php
// app/controllers/UserController.php

require_once __DIR__ . '/../models/UserModel.php'; 
require_once __DIR__ . '/../../config/database.php'; // เรียกใช้ $pdo

class UserController {
    private $userModel;

    public function __construct() {
        global $pdo; // ใช้ global เพื่อเข้าถึง $pdo จาก database.php
        $this->userModel = new UserModel($pdo);
        $this->userModel->createTable(); // ตรวจสอบและสร้างตาราง (ถ้ายังไม่มี)
    }

    // แสดงรายการผู้ใช้ทั้งหมด
    public function index() {
        $users = $this->userModel->getAllUsers();
        require_once __DIR__ . '/../views/user_list.php';
    }

    // แสดงฟอร์มสำหรับเพิ่มผู้ใช้ใหม่
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirm_password = trim($_POST['confirm_password'] ?? '');

            $errors = [];

            // ตรวจสอบข้อมูลที่รับมา
            if (empty($username)) {
                $errors[] = "Username is required.";
            }
            if (empty($email)) {
                $errors[] = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
            if (empty($password)) {
                $errors[] = "Password is required.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters long.";
            }
            if ($password !== $confirm_password) {
                $errors[] = "Passwords do not match.";
            }

            if (empty($errors)) {
                if ($this->userModel->createUser($username, $email, $password)) {
                    $_SESSION['success_message'] = "User added successfully!";
                    header('Location: /user_crud/public/index.php?action=index'); // Redirect ไปหน้ารายการ
                    exit();
                } else {
                    $_SESSION['error_message'] = "Error: Username or email already exists.";
                    // ไม่ต้อง redirect ให้ฟอร์มแสดงข้อผิดพลาด
                }
            } else {
                $_SESSION['error_message'] = implode("<br>", $errors);
                // ไม่ต้อง redirect ให้ฟอร์มแสดงข้อผิดพลาด
            }
        }
        require_once __DIR__ . '/../views/user_add.php';
    }

    // แสดงฟอร์มสำหรับแก้ไขผู้ใช้และจัดการการอัพเดท
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $_SESSION['error_message'] = "Invalid user ID.";
            header('Location: /user_crud/public/index.php?action=index');
            exit();
        }

        $user = $this->userModel->getUserById($id);

        if (!$user) {
            $_SESSION['error_message'] = "User not found.";
            header('Location: /user_crud/public/index.php?action=index');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? ''); // ไม่บังคับเปลี่ยน
            $confirm_password = trim($_POST['confirm_password'] ?? '');

            $errors = [];

            // ตรวจสอบข้อมูลที่รับมา
            if (empty($username)) {
                $errors[] = "Username is required.";
            }
            if (empty($email)) {
                $errors[] = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }

            if (!empty($password) || !empty($confirm_password)) { // ถ้ามีการป้อนรหัสผ่านใหม่
                if (strlen($password) < 6) {
                    $errors[] = "New password must be at least 6 characters long.";
                }
                if ($password !== $confirm_password) {
                    $errors[] = "New passwords do not match.";
                }
            }

            if (empty($errors)) {
                $update_password = !empty($password) ? $password : null; // ส่ง null ถ้าไม่ต้องการเปลี่ยนรหัสผ่าน
                if ($this->userModel->updateUser($id, $username, $email, $update_password)) {
                    $_SESSION['success_message'] = "User updated successfully!";
                    header('Location: /user_crud/public/index.php?action=index');
                    exit();
                } else {
                    $_SESSION['error_message'] = "Error: Username or email already exists for another user.";
                    // ไม่ต้อง redirect ให้ฟอร์มแสดงข้อผิดพลาด
                }
            } else {
                $_SESSION['error_message'] = implode("<br>", $errors);
                // ไม่ต้อง redirect ให้ฟอร์มแสดงข้อผิดพลาด
            }
        }
        require_once __DIR__ . '/../views/user_edit.php';
    }

    // ลบผู้ใช้
    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $_SESSION['error_message'] = "Invalid user ID.";
        } elseif ($this->userModel->deleteUser($id)) {
            $_SESSION['success_message'] = "User deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Error deleting user.";
        }
        header('Location: /user_crud/public/index.php?action=index');
        exit();
    }
}