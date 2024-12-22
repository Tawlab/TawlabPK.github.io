<?php
session_start();
require 'config.php';

// ตรวจสอบการเข้าสู่ระบบ

// รับ ID ของครูจาก URL
if (!isset($_GET['id'])) {
    header("Location: manage_teacher.php");
    exit();
}

$teacher_id = $_GET['id'];

// ดึงข้อมูลครูจากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM teacher WHERE id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_teacher.php");
    exit();
}

$teacher = $result->fetch_assoc();

// อัปเดตข้อมูลครู
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['t_username'];
    $password = $_POST['t_password'];
    $first_name = $_POST['t_Fname'];
    $last_name = $_POST['t_Lname'];
    $email = $_POST['t_email'];
    $address = $_POST['t_address'];
    $phone = $_POST['t_phone'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE teacher SET t_username = ?, t_password = ?, t_Fname = ?, t_Lname = ?, t_email = ?, t_address = ?, t_phone = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $username, $hashed_password, $first_name, $last_name, $email, $address, $phone, $teacher_id);
    } else {
        $stmt = $conn->prepare("UPDATE teacher SET t_username = ?, t_Fname = ?, t_Lname = ?, t_email = ?, t_address = ?, t_phone = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $username, $first_name, $last_name, $email, $address, $phone, $teacher_id);
    }

    if ($stmt->execute()) {
        header("Location: admin_manage_teacher.php");
        exit();
    } else {
        echo "<script>alert('ไม่สามารถอัปเดตข้อมูลได้');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #e8f5e9;
            font-family: Arial, sans-serif;
        }

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

        #title {
            text-align: center;
            padding: 15px;
            background-color: #1b5e20;
            color: white;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            font-size: 1rem;
        }

        .btn-back {
            background-color: #1b5e20;
            color: white;
        }

        .btn-update {
            background-color: #4caf50;
            color: white;
        }
    </style>
    <title>Edit Teacher</title>
</head>

<body>
    <h1 id="title">Edit Teacher</h1>

    <div class="container">
        <form method="POST">
            <div class="mb-3">
                <label for="t_username" class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="t_username" name="t_username" value="<?php echo htmlspecialchars($teacher['t_username']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="t_password" class="form-label">รหัสผ่าน (กรณีไม่เปลี่ยนให้เว้นว่าง)</label>
                <input type="password" class="form-control" id="t_password" name="t_password">
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="togglePassword">
                <label class="form-check-label" for="togglePassword" style="color: black;">แสดงรหัสผ่าน</label>
            </div>
            <script>
                // ฟังก์ชันสำหรับแสดงหรือซ่อนรหัสผ่าน
                document.getElementById('togglePassword').addEventListener('change', function() {
                    const passwordField = document.getElementById('t_password');
                    const type = this.checked ? 'text' : 'password';
                    passwordField.type = type;
                    confirmPasswordField.type = type;

                });
            </script>

            <div class="mb-3">
                <label for="t_Fname" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="t_Fname" name="t_Fname" value="<?php echo htmlspecialchars($teacher['t_Fname']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="t_Lname" class="form-label">นามสกุล</label>
                <input type="text" class="form-control" id="t_Lname" name="t_Lname" value="<?php echo htmlspecialchars($teacher['t_Lname']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="t_email" class="form-label">อีเมล</label>
                <input type="email" class="form-control" id="t_email" name="t_email" value="<?php echo htmlspecialchars($teacher['t_email']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="t_address" class="form-label">ที่อยู่</label>
                <textarea class="form-control" id="t_address" name="t_address" required><?php echo htmlspecialchars($teacher['t_address']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="t_phone" class="form-label">เบอร์โทร</label>
                <input type="text" class="form-control" id="t_phone" name="t_phone" value="<?php echo htmlspecialchars($teacher['t_phone']); ?>" pattern="\d{10}" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="admin_manage_teacher.php" class="btn btn-back">Back</a>
                <button type="submit" class="btn btn-update">Update</button>
            </div>
        </form>
    </div>
</body>

</html>