<?php
session_start();
require 'config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

// ดึงข้อมูลรายวิชาทั้งหมด
$query = "SELECT id, subject_code, subject_name, teacher_name FROM subjects ORDER BY subject_name ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>All Subjects</title>
</head>
<body>
    <h1 id="title">รายวิชาทั้งหมด</h1>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <a href="teacher_dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
            <a href="index.php" class="btn btn-danger">ออกจากระบบ</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>รหัสวิชา</th>
                    <th>ชื่อวิชา</th>
                    <th>ชื่อครูผู้สอน</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['subject_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                            <td class="d-flex justify-content-around">
                                <a href="view_subject.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8a13.134 13.134 0 0 1-1.66 2.043C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                                    </svg>
                                </a>
                                <a href="edit_subject.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                    </svg>
                                </a>
                                <a href="delete_subject.php?id=<?php echo $row['id']; ?>" style="background-color:aqua;filter: invert(100%);border-style:none;" class="btn btn-delete" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบวิชานี้?');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 5h4a.5.5 0 0 1 .5.5V6h1v-.5A1.5 1.5 0 0 0 10.5 4h-5A1.5 1.5 0 0 0 4 5.5V6h1v-.5zM4.118 7L4 7.059V14.5A1.5 1.5 0 0 0 5.5 16h5a1.5 1.5 0 0 0 1.5-1.5V7.059L11.882 7H4.118zM2.5 6h11a.5.5 0 0 1 .5.5V7a.5.5 0 0 1-.5.5H2.5A.5.5 0 0 1 2 7V6.5a.5.5 0 0 1 .5-.5z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No subjects found.</td>
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
            max-width: 900px;
            margin: auto;
        }

        .btn {
            font-size: 1rem;
        }
    </style>
</body>
</html>
