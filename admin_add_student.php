<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_code = $_POST['student_code'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $class = $_POST['class'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // ตรวจสอบข้อมูลซ้ำ
    $stmt = $conn->prepare("SELECT * FROM student WHERE student_code = ?");
    $stmt->bind_param("s", $student_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Student code already exists.";
    } else {
        // เพิ่มข้อมูลนักเรียน
        $stmt = $conn->prepare("INSERT INTO student (student_code, Fname, Lname, class, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $student_code, $fname, $lname, $class, $address, $phone);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Student added successfully.";
            header("Location: admin_manage_student.php");
            exit();
        } else {
            $error = "Failed to add student.";
        }
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
    <title>Add Student</title>
</head>
<body>
    <h1 id="title">เพิ่มนักเรียน</h1>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <a href="admin_manage_student.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"> <?php echo htmlspecialchars($error); ?> </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="student_code" class="form-label" style="color: #333;">รหัสนักเรียน</label>
                <input type="text" name="student_code" id="student_code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fname" class="form-label" style="color: #333;">ชื่อ</label>
                <input type="text" name="fname" id="fname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label" style="color: #333;">นามสกุล</label>
                <input type="text" name="lname" id="lname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="class" class="form-label" style="color: #333;">ชั้น</label>
                <input type="text" name="class" id="class" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label" style="color: #333;">ที่อยู่</label>
                <input type="text" name="address" id="address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label" style="color: #333;">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">บันทึก</button>
        </form>
    </div>

    <style>
        body {
            background-color: #eef2f7;
            font-family: Arial, sans-serif;
        }

        #title {
            text-align: center;
            padding: 15px;
            background-color: limegreen;
            color: white;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .btn {
            font-size: 1rem;
        }
    </style>
</body>
</html>