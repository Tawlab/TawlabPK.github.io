<?php
session_start();
require 'config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่า student_code ถูกส่งเข้ามาหรือไม่
if (!isset($_GET['student_code'])) {
    header("Location: attendance_history.php");
    exit();
}

$student_code = $_GET['student_code'];

// ดึงข้อมูลนักเรียน
$stmt = $conn->prepare("SELECT student_code, Fname, Lname FROM student WHERE student_code = ?");
$stmt->bind_param("s", $student_code);
$stmt->execute();
$student_result = $stmt->get_result();

if ($student_result->num_rows === 0) {
    header("Location: attendance_history.php");
    exit();
}

$student = $student_result->fetch_assoc();

// ดึงประวัติการเช็คชื่อของนักเรียน พร้อมชื่อวิชา
$stmt = $conn->prepare(
    "SELECT a.date, a.status, sb.subject_name 
     FROM attendance a 
     JOIN subjects sb ON a.subject_id = sb.id 
     WHERE a.student_id = (SELECT id FROM student WHERE student_code = ?) 
     ORDER BY a.date DESC"
);
$stmt->bind_param("s", $student_code);
$stmt->execute();
$attendance_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Student Attendance History</title>
</head>

<body>
    <h1 id="title">รายละเอียดประวัติการเช็คชื่อรายบุคคล</h1>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <a href="attendance_history.php" class="btn btn-secondary">ย้อนกลับ</a>
            <a href="index.php" class="btn btn-danger">ออกจากระบบ</a>
        </div>

        <h2>ชื่อ-นามสกุล: <?php echo htmlspecialchars($student['Fname'] . ' ' . $student['Lname']); ?></h2>
        <h4>รหัสนักเรียน: <?php echo htmlspecialchars($student['student_code']); ?></h4>


        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>รายวิชา</th>
                    <th>สถานะเข้าเรียน</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($attendance_result->num_rows > 0): ?>
                    <?php while ($row = $attendance_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">ไม่พบประวัติ.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <style>
        body {
            background-color: #eef2f7;
            font-family: Arial, sans-serif;
        }

        #title {
            text-align: center;
            padding: 15px;
            background-color: #276FBF;
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

        .table {
            margin-top: 20px;
        }

        .btn {
            font-size: 1rem;
        }

        h2,
        h4 {
            color: #333;
        }
    </style>
</body>

</html>