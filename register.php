<?php
session_start();
require 'config.php';

// การตรวจสอบการลงทะเบียน
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['t_username'])) {
    $username = $_POST['t_username'];
    $first_name = $_POST['t_Fname'];
    $last_name = $_POST['t_Lname'];
    $email = $_POST['t_email'];
    $password = $_POST['t_password'];
    $confirm_password = $_POST['confirm-password'];
    $address = $_POST['t_address'];
    $phone = $_POST['t_phone'];

    // เช็คความยาวของรหัสผ่านและเงื่อนไขให้แน่นหนา
    if (strlen($password) < 6) {
        echo "<script>alert('รหัสผ่านต้องมีอย่างน้อย 6 ตัว และประกอบด้วยตัวพิมพ์ใหญ่ ตัวพิมพ์เล็ก ตัวเลข และอักขระพิเศษ'); window.history.back();</script>";
        exit();
    }

    // เช็คว่ารหัสผ่านตรงกับการยืนยันหรือไม่
    if ($password !== $confirm_password) {
        echo "<script>alert('รหัสผ่านไม่ตรงกัน'); window.history.back();</script>";
        exit();
    }

    // เช็คว่าอีเมลมีในฐานข้อมูลหรือยัง
    $stmt = $conn->prepare("SELECT * FROM teacher WHERE t_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('อีเมลนี้ถูกใช้ลงทะเบียนแล้ว'); window.history.back();</script>";
        exit();
    }

    // ทำการแฮชรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ทำการเพิ่มข้อมูลลงในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO teacher (t_username, t_Fname, t_Lname, t_email, t_password, t_address, t_phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $first_name, $last_name, $email, $hashed_password, $address, $phone);

    if ($stmt->execute()) {
        // ลงทะเบียนสำเร็จ
        $_SESSION['t_username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('ลงทะเบียนไม่สำเร็จ'); window.history.back();</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>ลงทะเบียน</title>
</head>

<body>
    <h1 id="title">โรงเรียนบ้านปากกาง(ประชาราษฎร์รังสฤษฏ์)</h1>

    <div class="container">
        <h1>ลงทะเบียน</h1>
        <hr>
        <form action="register.php" method="POST" id="register-form">
            <label class="form-label">ชื่อผู้ใช้</label>
            <input type="text" class="form-control mb-2" placeholder="กรอกชื่อผู้ใช้" name="t_username" required>

            <label class="form-label">รหัสผ่าน</label>
            <div class="input-group mb-3">
                <input type="password" class="form-control" placeholder="กรอกรหัสผ่าน" name="t_password" id="password" required>
            </div>

            <label class="form-label">ยืนยันรหัสผ่าน</label>
            <div class="input-group mb-3">
                <input type="password" class="form-control" placeholder="กรอกยืนยันรหัสผ่าน" name="confirm-password" id="confirm-password" required>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="togglePassword">
                <label class="form-check-label" for="togglePassword">แสดงรหัสผ่าน</label>
            </div>
            <!-- <div class="form-check"> -->

                <label class="form-label">ชื่อ</label>
                <input type="text" class="form-control mb-2" placeholder="กรอกชื่อ" name="t_Fname" required>

                <label class="form-label">นามสกุล</label>
                <input type="text" class="form-control mb-2" placeholder="กรอกนามสกุล" name="t_Lname" required>

                <label class="form-label">ที่อยู่</label>
                <input type="text" class="form-control mb-2" placeholder="กรอกที่อยู่" name="t_address" required>

                <label class="form-label">อีเมล</label>
                <input type="email" class="form-control mb-2" placeholder="กรอกอีเมล" name="t_email" required pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$">

                <label class="form-label">เบอร์โทรศัพท์</label>
                <input type="number" class="form-control mb-3" placeholder="กรอกเบอร์โทรศัพท์" name="t_phone" required>

                <button class="btn btn-primary" type="submit">ลงทะเบียน</button>
                <button class="btn btn-link" type="button" onclick="window.location.href='index.php'">กลับไปยังหน้าเข้าสู่ระบบ</button>
        </form>
    <!-- </div> -->

    <script>
        // ฟังก์ชันสำหรับแสดงหรือซ่อนรหัสผ่าน
        document.getElementById('togglePassword').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirm-password');
            const type = this.checked ? 'text' : 'password';
            passwordField.type = type;
            confirmPasswordField.type = type;
            
        });
    </script>
    <style>
        .form-check {
            margin-top: 10px;
        }

        .form-check-label {
            font-size: 1rem;
            color: #fff;
        }

        .form-check-input {
            margin-right: 10px;
        }
        /* ซ่อนปุ่มเพิ่ม/ลดใน Chrome, Edge และ Safari */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* ซ่อนปุ่มใน Firefox */
    input[type="number"] {
        -moz-appearance: textfield;
    }
    </style>

</body>

</html>