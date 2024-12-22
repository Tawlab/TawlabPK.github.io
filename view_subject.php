<?php
session_start();
require 'config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่าได้รับ ID ของวิชา
if (!isset($_GET['id'])) {
    header("Location: all_subject.php");
    exit();
}

$subject_id = $_GET['id'];

// ดึงข้อมูลวิชา
$stmt = $conn->prepare("SELECT * FROM subjects WHERE id = ?");
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$result = $stmt->get_result();
$subject = $result->fetch_assoc();

if (!$subject) {
    header("Location: all_subject.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>View Subject</title>
</head>
<body>
    <h1 id="title">รายละเอียดรายวิชา</h1>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <a href="all_subject.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>รหัสรายวิชา</th>
                <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
            </tr>
            <tr>
                <th>ชื่อรายวิชา</th>
                <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
            </tr>
            <tr>
                <th>หน่วยกิต</th>
                <td><?php echo htmlspecialchars($subject['credits']); ?></td>
            </tr>
            <tr>
                <th>ชื่อครูผู้สอน</th>
                <td><?php echo htmlspecialchars($subject['teacher_name']); ?></td>
            </tr>
            <tr>
                <th>รายละเอียดวิชา</th>
                <td><?php echo htmlspecialchars($subject['subject_description']); ?></td>
            </tr>
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

        .btn {
            font-size: 1rem;
        }

        .table {
            margin-top: 20px;
        }

        th {
            width: 30%;
            background-color: #f8f9fa;
        }
    </style>
</body>
</html>
