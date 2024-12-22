<?php
session_start();
require 'config.php';

// ตรวจสอบว่าได้รับ ID ของวิชา
if (!isset($_GET['id'])) {
    header("Location:admin_manage_subject.php");
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
    header("Location: admin_edit_subject.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_code = $_POST['subject_code'];
    $subject_name = $_POST['subject_name'];
    $credits = $_POST['credits'];
    $teacher_name = $_POST['teacher_name'];
    $subject_description = $_POST['subject_description'];

    // อัปเดตข้อมูลวิชา
    $stmt = $conn->prepare("UPDATE subjects SET subject_code = ?, subject_name = ?, credits = ?, teacher_name = ?, subject_description = ? WHERE id = ?");
    $stmt->bind_param("ssissi", $subject_code, $subject_name, $credits, $teacher_name, $subject_description, $subject_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Subject updated successfully.";
        header("Location: admin_edit_subject.php");
        exit();
    } else {
        $error = "Failed to update subject.";
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
    <title>Edit Subject</title>
</head>
<body>
    <h1 id="title">แก้ไขรายวิชา</h1>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <a href="admin_edit_subject.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"> <?php echo htmlspecialchars($error); ?> </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label" style="color: #333;">รหัสรายวิชา</label>
                <input type="text" name="subject_code" id="subject_code" class="form-control" value="<?php echo htmlspecialchars($subject['subject_code']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label" style="color: #333;">ชื่อรายวิชา</label>
                <input type="text" name="subject_name" id="subject_name" class="form-control" value="<?php echo htmlspecialchars($subject['subject_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="credits" class="form-label" style="color: #333;">หน่วยกิต</label>
                <input type="number" name="credits" id="credits" class="form-control" value="<?php echo htmlspecialchars($subject['credits']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="teacher_name" class="form-label" style="color: #333;">ชื่อครูผู้สอน</label>
                <input type="text" name="teacher_name" id="teacher_name" class="form-control" value="<?php echo htmlspecialchars($subject['teacher_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="subject_description" class="form-label" style="color: #333;">รายละเอียดรายวิชา</label>
                <textarea name="subject_description" id="subject_description" class="form-control" rows="4" required><?php echo htmlspecialchars($subject['subject_description']); ?></textarea>
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
