<?php
// app/models/UserModel.php

class UserModel {
    private $pdo;
    private $table_name = 'users'; // ชื่อตารางในฐานข้อมูล

    public function __construct($db) {
        $this->pdo = $db;
    }

    // สร้างตาราง Users ถ้ายังไม่มี (สำหรับเริ่มต้น)
    public function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($query);
    }

    // อ่านข้อมูล User ทั้งหมด
    public function getAllUsers() {
        $query = "SELECT id, username, email, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // อ่านข้อมูล User ตาม ID
    public function getUserById($id) {
        $query = "SELECT id, username, email FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // เพิ่ม User ใหม่
    public function createUser($username, $email, $password) {
        // ตรวจสอบว่า username หรือ email ซ้ำหรือไม่ (ป้องกันการเพิ่มข้อมูลซ้ำ)
        if ($this->isUsernameOrEmailExists($username, $email)) {
            return false; // หรือโยน Exception
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // แฮชรหัสผ่านเพื่อความปลอดภัย

        $query = "INSERT INTO " . $this->table_name . " (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->pdo->prepare($query);

        // Bind parameters เพื่อป้องกัน SQL Injection
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        return $stmt->execute();
    }

    // อัพเดทข้อมูล User
    public function updateUser($id, $username, $email, $password = null) {
        // ตรวจสอบว่า username หรือ email ซ้ำกับคนอื่นหรือไม่ (ยกเว้นตัวเอง)
        if ($this->isUsernameOrEmailExists($username, $email, $id)) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET username = :username, email = :email";
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query .= ", password = :password";
        }
        $query .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        if ($password) {
            $stmt->bindParam(':password', $hashed_password);
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // ลบ User
    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ตรวจสอบว่า username หรือ email ซ้ำหรือไม่ (สำหรับความปลอดภัยและ UX)
    private function isUsernameOrEmailExists($username, $email, $exclude_id = null) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE username = :username OR email = :email";
        if ($exclude_id) {
            $query .= " AND id != :exclude_id";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        if ($exclude_id) {
            $stmt->bindParam(':exclude_id', $exclude_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>