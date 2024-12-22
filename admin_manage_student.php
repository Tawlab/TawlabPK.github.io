<?php
session_start();
require 'config.php';

$search = $_GET['search'] ?? "";
$search_param = "%$search%";

// ดึงข้อมูลนักเรียน
$stmt = $conn->prepare("SELECT id, student_code, Fname, Lname, class, phone, address FROM student WHERE student_code LIKE ? OR Fname LIKE ? OR Lname LIKE ? OR class LIKE ?");
$stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e8f5e9; /* เขียวอ่อน */
            font-family: Arial, sans-serif;
        }

        #title {
            text-align: center;
            padding: 15px;
            background-color: #1b5e20; /* เขียวเข้ม */
            color: white;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            font-size: 1rem;
        }

        .btn-edit {
            color: #fff;
            background-color: #4caf50; /* เขียว */
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .btn-delete {
            color: #fff;
            background-color: #e53935; /* แดง */
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .btn-logout {
            background-color: #e53935; /* แดง */
            color: white;
        }

        .btn-back {
            background-color: #1b5e20; /* เขียวเข้ม */
            color: white;
        }

        .form-control {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4caf50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
    <title>Manage Students</title>
</head>
<body>
    <h1 id="title">Manage Students</h1>

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <a href="admin_dashboard.php" class="btn btn-back">Back</a>
            <a href="index.php" class="btn btn-logout">Logout</a>
        </div>

        <form method="GET" class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="ค้นหานักเรียน..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary mt-2">ค้นหา</button>
        </form>
        <div class="d-flex justify-content-between mb-3">
            <a href="admin_add_student.php" class="btn btn-success">Add Student</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>รหัสนักเรียน</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>ชั้นเรียน</th>
                    <th>เบอร์โทร</th>
                    <th>ที่อยู่</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['student_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['Fname']); ?></td>
                            <td><?php echo htmlspecialchars($row['Lname']); ?></td>
                            <td><?php echo htmlspecialchars($row['class']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td class="action-buttons">
                                <a href="admin_edit_student.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </a>
                                <a href="admin_delete_student.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบนักเรียนคนนี้?');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 5h4a.5.5 0 0 1 .5.5V6h1v-.5A1.5 1.5 0 0 0 10.5 4h-5A1.5 1.5 0 0 0 4 5.5V6h1v-.5zM4.118 7L4 7.059V14.5A1.5 1.5 0 0 0 5.5 16h5a1.5 1.5 0 0 0 1.5-1.5V7.059L11.882 7H4.118zM2.5 6h11a.5.5 0 0 1 .5.5V7a.5.5 0 0 1-.5.5H2.5A.5.5 0 0 1 2 7V6.5a.5.5 0 0 1 .5-.5z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">ไม่พบข้อมูลนักเรียน</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
