<?php
session_start();
require 'config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// ดึงข้อมูลครู
$stmt = $conn->prepare("SELECT t_Fname FROM teacher WHERE t_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$teacher_data = $result->fetch_assoc();
$fname = $teacher_data['t_Fname'] ?? "Teacher";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Teacher Dashboard</title>
</head>
<body>
    <h1 id="title">
    โรงเรียนบ้านปากกาง(ประชาราษฎร์รังสฤษฏ์)
    <br>
        <span style="font-size: 24px;;">ยินดีต้องรับ <?php echo htmlspecialchars($fname);?></span>
    </h1>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12 mb-3">
                <a href="manage_students.php" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
                    จัดการนักเรียน
                </a>
            </div>
            <div class="col-12 mb-3">
                <a href="attendance_history.php" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-journal-text" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2 15V1a1 1 0 0 1 1-1h9.5a1 1 0 0 1 1 1v1H3v13h9.5v1H3a1 1 0 0 1-1-1zm11-1H3v-1h10.5a.5.5 0 0 0 .5-.5V4.118a.5.5 0 0 0-.175-.388L12.475 2H11V1h.5a1 1 0 0 1 1 1v12z"/>
                        <path d="M5 8h5v1H5V8zm0-3h5v1H5V5zm0 6h3v1H5v-1z"/>
                    </svg>
                    ประวัติการเช็คชื่อ
                </a>
            </div>
            <div class="col-12 mb-3">
                <a href="add_subject.php" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-journal-plus" viewBox="0 0 16 16">
                        <path d="M6 9.5a.5.5 0 0 1 .5-.5H8v-1.5a.5.5 0 0 1 1 0V9h1.5a.5.5 0 0 1 0 1H9v1.5a.5.5 0 0 1-1 0V10H6.5a.5.5 0 0 1-.5-.5z"/>
                        <path d="M2 1h10.5a1 1 0 0 1 1 1v1H3v12h9.5a1 1 0 0 1 1 1H3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                        <path d="M4.5 1V0h1v1h1v1h-1V1h-1zm4 0V0h1v1h1v1h-1V1h-1z"/>
                    </svg>
                    เพิ่มรายวิชา
                </a>
            </div>
            <div class="col-12 mb-3">
                <a href="all_subject.php" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                        <path d="M2 2.5v11A1.5 1.5 0 0 0 3.5 15H12v1H3.5A2.5 2.5 0 0 1 1 13.5v-11A2.5 2.5 0 0 1 3.5 0H12v1H3.5A1.5 1.5 0 0 0 2 2.5z"/>
                        <path d="M12 1h.5A2.5 2.5 0 0 1 15 3.5v10A2.5 2.5 0 0 1 12.5 16H12V1z"/>
                    </svg>
                    รายวิชาทั้งหมด
                </a>
            </div>
            <div class="col-12 mb-3">
                <a href="mark_attendance.php" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zm-1 1H2v12h11V2z"/>
                        <path d="M10.854 6.854a.5.5 0 0 0-.708-.708L7 9.293 5.854 8.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
                    </svg>
                    เช็คชื่อ
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a href="index.php" class="btn btn-danger">ออกจากระบบ</a>
            </div>
        </div>
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
            max-width: 900px;
            margin: auto;
        }

        .btn {
            display: block;
            width: 100%;
            font-size: 1.2rem;
            padding: 10px;
            text-align: center;
        }

        .row {
            justify-content: center;
        }
    </style>
</body>
</html>

