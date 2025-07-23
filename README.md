# ระบบจัดการผู้ใช้ (User CRUD) ด้วย PHP MVC

ระบบนี้เป็นโปรเจกต์สำหรับจัดการข้อมูลผู้ใช้ พัฒนาด้วยภาษา PHP ในรูปแบบ OOP และสถาปัตยกรรม MVC

## ฟีเจอร์หลัก
- เพิ่มผู้ใช้ใหม่
- แก้ไขข้อมูลผู้ใช้
- ลบผู้ใช้
- แสดงรายการผู้ใช้ทั้งหมด

## เทคโนโลยีที่ใช้
- PHP (OOP)
- รูปแบบ MVC
- Bootstrap 5 (สำหรับการออกแบบ UI)

## วิธีการติดตั้งและใช้งาน

### 1. เตรียมโปรเจกต์
- แตกไฟล์โปรเจกต์ไปยังโฟลเดอร์เซิร์ฟเวอร์จำลอง เช่น `htdocs` (ใน XAMPP)

### 2. สร้างฐานข้อมูล
1. เปิดโปรแกรม phpMyAdmin หรือเครื่องมือจัดการฐานข้อมูลของคุณ
2. สร้างฐานข้อมูลใหม่ เช่น `user_crud_mvc`
3. สร้างตาราง `users` ด้วย SQL ด้านล่างนี้:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
