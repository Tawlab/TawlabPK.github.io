<?php
session_start();
require 'config.php';

$username = $_SESSION['username'];

// ดึงข้อมูลครู
$stmt = $conn->prepare("SELECT id, t_Fname, t_Lname FROM teacher WHERE t_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$teacher_data = $result->fetch_assoc();
$teacher_id = $teacher_data['id'];
$teacher_name = $teacher_data['t_Fname'] . ' ' . $teacher_data['t_Lname'];

// ตรวจสอบว่ามีการส่งค่า ID เข้ามาหรือไม่
if (!isset($_GET['id'])) {
    header("Location: admin_manage_attendance.php");
    exit();
}

$attendance_id = $_GET['id'];

// ดึงข้อมูลการเช็คชื่อ
$stmt = $conn->prepare("
    SELECT a.id, a.status, a.date, s.Fname, s.Lname, s.class, sub.subject_name 
    FROM attendance a 
    JOIN student s ON a.student_id = s.id 
    LEFT JOIN subjects sub ON a.subject_id = sub.id 
    WHERE a.id = ?
");
$stmt->bind_param("i", $attendance_id);
$stmt->execute();
$attendance_result = $stmt->get_result();

if ($attendance_result->num_rows === 0) {
    header("Location: admin_manage_attendance.php");
    exit();
}

$attendance = $attendance_result->fetch_assoc();

// บันทึกการแก้ไข
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE attendance SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $attendance_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Status updated successfully.";
        header("Location:admin_manage_attendance.php");
        exit();
    } else {
        $error_message = "Failed to update status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Status</title>
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
        h2, h4, h5 {
            color: #333;
        }
        .date-display {
            text-align: right;
            font-size: 1rem;
            color: #555;
        }
    </style>
</head>
<body>
    <h1 id="title">แก้ไขสถานะเข้าเรียน</h1>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <a href="admin_manage_attendance.php" class="btn btn-secondary">ย้อนกลับ</a>
            <p class="date-display"><?php echo htmlspecialchars($attendance['date']); ?></p>
        </div>

        <h2>Student: <?php echo htmlspecialchars($attendance['Fname'] . ' ' . $attendance['Lname']); ?></h2>
        <h4>Class: <?php echo htmlspecialchars($attendance['class']); ?></h4>
        <h5>Subject: <?php echo htmlspecialchars($attendance['subject_name']); ?></h5>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"> <?php echo htmlspecialchars($error_message); ?> </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="status" class="form-label">สถานะเข้าเรียน</label>
                <select name="status" id="status" class="form-select">
                    <option value="Present" <?php if ($attendance['status'] === "Present") echo 'selected'; ?>>มาเรียน</option>
                    <option value="Late" <?php if ($attendance['status'] === "Late") echo 'selected'; ?>>สาย</option>
                    <option value="Absent" <?php if ($attendance['status'] === "Absent") echo 'selected'; ?>>ขาด</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">บันทึก</button>
        </form>
    </div>
</body>
</html>
