<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_code = $_POST['subject_code'];
    $subject_name = $_POST['subject_name'];
    $credits = $_POST['credits'];
    $teacher_name = $_POST['teacher_name'];
    $subject_description = $_POST['subject_description'];

    // ตรวจสอบข้อมูลซ้ำ
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_code = ?");
    $stmt->bind_param("s", $subject_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Subject code already exists.";
    } else {
        // เพิ่มข้อมูลวิชา
        $stmt = $conn->prepare("INSERT INTO subjects (subject_code, subject_name, credits, teacher_name, subject_description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $subject_code, $subject_name, $credits, $teacher_name, $subject_description);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Subject added successfully.";
            header("Location: admin_manage_subject.php");
            exit();
        } else {
            $error = "Failed to add subject.";
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
    <title>Add Subject</title>
</head>
<body>
    <h1 id="title" class=".text-success">เพิ่มรายวิชา</h1>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <a href="admin_manage_subject.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"> <?php echo htmlspecialchars($error); ?> </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label" style="color: #333;">รหัสวิชา</label>
                <input type="text" name="subject_code" id="subject_code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label" style="color: #333;">ชื่อวิชา</label>
                <input type="text" name="subject_name" id="subject_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="credits" class="form-label" style="color: #333;">หน่วยกิต</label>
                <input type="number" name="credits" id="credits" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="teacher_name" class="form-label" style="color: #333;">ชื่อครูผู้สอน</label>
                <input type="text" name="teacher_name" id="teacher_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="subject_description" class="form-label" style="color: #333;">รายละเอียดวิชา</label>
                <textarea name="subject_description" id="subject_description" class="form-control" rows="4" required></textarea>
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
