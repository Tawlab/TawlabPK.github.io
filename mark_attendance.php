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
$stmt = $conn->prepare("SELECT id, t_Fname FROM teacher WHERE t_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$teacher_id = $user_data['id'];
$fname = $user_data['t_Fname'] ?? "Teacher";

// ดึงข้อมูลชั้นเรียนและวิชา
$class_query = "SELECT DISTINCT class FROM student";
$class_result = $conn->query($class_query);

$subject_query = "SELECT id, subject_name FROM subjects ORDER BY subject_name ASC";
$subject_result = $conn->query($subject_query);

$selected_class = $_GET['class'] ?? "";
$selected_subject = $_GET['subject_id'] ?? "";

// ดึงข้อมูลนักเรียนในชั้นเรียนที่เลือก
$students = [];
if ($selected_class) {
    $stmt = $conn->prepare("SELECT * FROM student WHERE class = ?");
    $stmt->bind_param("s", $selected_class);
    $stmt->execute();
    $students = $stmt->get_result();
}

// การบันทึกการเช็คชื่อ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance_data = $_POST['attendance'] ?? [];
    $subject_id = $_POST['subject_id'];

    if (empty($subject_id)) {
        $error_message = "Please select a subject.";
    } elseif (count($attendance_data) < $students->num_rows) {
        $error_message = "คุณยังเช็คชื่อไม่ครบ";
    } else {
        foreach ($attendance_data as $student_id => $status) {
            $stmt = $conn->prepare(
                "INSERT INTO attendance (student_id, subject_id, date, status, checked_by, date_checked) 
                 VALUES (?, ?, CURDATE(), ?, ?, CURDATE()) 
                 ON DUPLICATE KEY UPDATE status = ?, checked_by = ?, date_checked = CURDATE()"
            );
            $stmt->bind_param("iisssi", $student_id, $subject_id, $status, $teacher_id, $status, $teacher_id);
            $stmt->execute();
        }

        $_SESSION['success_message'] = "บันทึกสำเร็จ.";
        header("Location: teacher_dashboard.php");
        exit();
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
    <title>Attendance Check</title>
</head>
<body>
    <h1 id="title">เช็คชื่อ</h1>

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <a href="teacher_dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
            <a href="index.php" class="btn btn-danger">ออกจากระบบ</a>
        </div>

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
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
                <div class="col-md-6">
                    <label for="class" class="form-label">เลือกชั้นเรียน</label>
                    <select name="class" id="class" class="form-select" onchange="this.form.submit()">
                        <option value="">-- ชั้นเรียนทั้งหมด --</option>
                        <?php while ($class_row = $class_result->fetch_assoc()): ?>
                            <option value="<?php echo $class_row['class']; ?>" <?php if ($selected_class == $class_row['class']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($class_row['class']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </form>

        <?php if ($selected_class && $students->num_rows > 0): ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($selected_subject); ?>">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>รหัสนักเรียน</th>
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th>สถานะเข้าเรียน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($student = $students->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_code']); ?></td>
                                <td><?php echo htmlspecialchars($student['Fname']); ?></td>
                                <td><?php echo htmlspecialchars($student['Lname']); ?></td>
                                <td>
                                    <div>
                                        <div class="form-check mb-1">
                                            <input type="radio" name="attendance[<?php echo $student['id']; ?>]" value="Present" class="form-check-input" id="present-<?php echo $student['id']; ?>">
                                            <label class="form-check-label" for="present-<?php echo $student['id']; ?>">มาเรียน</label>
                                        </div>
                                        <div class="form-check mb-1">
                                            <input type="radio" name="attendance[<?php echo $student['id']; ?>]" value="Late" class="form-check-input" id="late-<?php echo $student['id']; ?>">
                                            <label class="form-check-label" for="late-<?php echo $student['id']; ?>">มาสาย</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" name="attendance[<?php echo $student['id']; ?>]" value="Absent" class="form-check-input" id="absent-<?php echo $student['id']; ?>" checked>
                                            <label class="form-check-label" for="absent-<?php echo $student['id']; ?>">ขาดเรียน</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">บันทึก</button>
            </form>
        <?php elseif ($selected_class): ?>
            <p class="text-center">กรุณาเลือกชั้นเรียน.</p>
        <?php endif; ?>
    </div>

    <style>
        body {
            background-color: #eef2f7;
            font-family: Arial, sans-serif;
        }

        #title {
            text-align: center;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #276FBF;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            font-size: 1rem;
        }
    </style>
</body>
</html>
