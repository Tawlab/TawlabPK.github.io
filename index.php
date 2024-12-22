<?php
session_start();
require 'config.php';

// การตรวจสอบการล็อกอิน
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login-username']) && isset($_POST['login-password'])) {
    $username = $_POST['login-username'];
    $password = $_POST['login-password'];

    if ($username == 'admin' && $password == '0000') {
        $_SESSION['login-username'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }

    // ตรวจสอบการล็อกอินของ Teacher
    $stmt = $conn->prepare("SELECT * FROM teacher WHERE t_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['t_password'])) {
        // เก็บข้อมูลผู้ใช้ลงใน session
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];

        // เปลี่ยนเส้นทางไปยังหน้า teacher_dashboard.php
        header("Location: teacher_dashboard.php");
        exit();
    } else {
        echo "<script>alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'); window.location.href = 'index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>เข้าสู่ระบบ</title>
</head>

<body>
    <h1 id="title">โรงเรียนบ้านปากกาง(ประชาราษฎร์รังสฤษฏ์)</h1>

    <div class="container">
        <h1>เข้าสู่ระบบ</h1>
        <hr>
        <form action="index.php" method="POST" id="login-form">
            <label class="form-label">ชื่อผู้ใช้</label>
            <input type="text" class="form-control mb-2" placeholder="กรอกชื่อผู้ใช้" name="login-username" required>

            <label class="form-label">รหัสผ่าน</label>
            <input type="password" class="form-control mb-2" placeholder="กรอกรหัสผ่าน" id="password" name="login-password" required>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="togglePassword">
                <label class="form-check-label" for="togglePassword">แสดงรหัสผ่าน</label>
            </div>

            <button class="btn btn-primary" type="submit">เข้าสู่ระบบ</button>
            <button class="btn btn-link" type="button" onclick="window.location.href='register.php'">ไปยังหน้าลงทะเบียน</button>
        </form>
    </div>

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

<script>
        // ฟังก์ชันสำหรับแสดงหรือซ่อนรหัสผ่าน
        document.getElementById('togglePassword').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const type = this.checked ? 'text' : 'password';
            passwordField.type = type;
            confirmPasswordField.type = type;
            
        });
    </script>

</body>

</html>