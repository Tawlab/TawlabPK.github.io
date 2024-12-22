<?php
session_start();
require 'config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// ดึงข้อมูล t_Fname ของครู
$stmt = $conn->prepare("SELECT t_Fname FROM teacher WHERE t_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$fname = $user_data['t_Fname'] ?? "Teacher";

// ดึงข้อมูลชั้นเรียน
$class_query = "SELECT DISTINCT class FROM student";
$class_result = $conn->query($class_query);

// ดึงข้อมูลวิชา
$subject_query = "SELECT id, subject_name FROM subjects ORDER BY subject_name ASC";
$subject_result = $conn->query($subject_query);

$selected_class = $_GET['class'] ?? "";
$selected_subject = $_GET['subject_id'] ?? "";
$status_filter = $_GET['status'] ?? "";
$search = $_GET['search'] ?? "";
$search_param = "%$search%";

// ดึงประวัติการเช็คชื่อพร้อมตัวกรอง
$attendance_query = "SELECT a.id as attendance_id, s.student_code, s.Fname, s.Lname, s.class, sb.subject_name, a.date, a.status, t.t_Fname, t.t_Lname 
                     FROM attendance a 
                     JOIN student s ON a.student_id = s.id 
                     JOIN subjects sb ON a.subject_id = sb.id 
                     LEFT JOIN teacher t ON a.modified_by = t.id 
                     WHERE (s.class = ? OR ? = '')
                     AND (a.subject_id = ? OR ? = '')
                     AND (a.status = ? OR ? = '')
                     AND (s.student_code LIKE ? OR s.Fname LIKE ? OR s.Lname LIKE ?)
                     ORDER BY a.date DESC, s.class, s.Fname";
$stmt = $conn->prepare($attendance_query);
$stmt->bind_param("sssssssss", $selected_class, $selected_class, $selected_subject, $selected_subject, $status_filter, $status_filter, $search_param, $search_param, $search_param);
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
    <title>Attendance History</title>
</head>

<body>
    <h1 id="title">ประวัติการเช็คชื่อ</h1>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <a href="teacher_dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
            <a href="index.php" class="btn btn-danger">ออกจากระบบ</a>
        </div>

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="class" class="form-label">เลือกชั้นเรียน</label>
                    <select name="class" id="class" class="form-select">
                        <option value="">-- ชั้นเรียนทั้งหมด --</option>
                        <?php while ($class_row = $class_result->fetch_assoc()): ?>
                            <option value="<?php echo $class_row['class']; ?>" <?php if ($selected_class == $class_row['class']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($class_row['class']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="subject_id" class="form-label">เลือกรายวิชา</label>
                    <select name="subject_id" id="subject_id" class="form-select">
                        <option value="">-- รายวิชาทั้งหมด --</option>
                        <?php while ($subject = $subject_result->fetch_assoc()): ?>
                            <option value="<?php echo $subject['id']; ?>" <?php if ($selected_subject == $subject['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($subject['subject_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">สถานะเข้าเรียน</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- สถานะเข้าเรียนทั้งหมด --</option>
                        <option value="Present" <?php if ($status_filter == "Present") echo 'selected'; ?>>มาเรียน</option>
                        <option value="Late" <?php if ($status_filter == "Late") echo 'selected'; ?>>มาสาย</option>
                        <option value="Absent" <?php if ($status_filter == "Absent") echo 'selected'; ?>>ขาดเรียน</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">ค้นหา</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="ชื่อหรือรหัสนักเรียน" value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary">ยืนยัน</button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>ชั้นเรียน</th>
                    <th>รหัสนักเรียน</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>รายวิชา</th>
                    <th>สถานะเข้าเรียน</th>
                    <th>ครูที่แก้ไข</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($attendance_result->num_rows > 0): ?>
                    <?php while ($row = $attendance_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['class']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['Fname']); ?></td>
                            <td><?php echo htmlspecialchars($row['Lname']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td>
                                <?php
                                switch ($row['status']) {
                                    case 'present':
                                        echo 'มาเรียน';
                                        break;
                                    case 'late':
                                        echo 'มาสาย';
                                        break;
                                    case 'absent':
                                        echo 'ขาดเรียน';
                                        break;
                                    default:
                                        echo htmlspecialchars($row['status']);
                                }
                                ?>
                            </td>

                            <td><?php echo htmlspecialchars($row['t_Fname'] . ' ' . $row['t_Lname']); ?></td>
                            <td>
                                <a href="edit_status.php?id=<?php echo $row['attendance_id']; ?>" class="btn btn-warning btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                    </svg>
                                </a>
                                <a href="student_history.php?student_code=<?php echo $row['student_code']; ?>" class="btn btn-info btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.85-3.85zm-5.596 0a5.5 5.5 0 1 1 7.78-7.78 5.5 5.5 0 0 1-7.78 7.78z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No attendance records found.</td>
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

        .table {
            margin-top: 20px;
        }

        .btn {
            font-size: 1rem;
        }
    </style>
</body>

</html>