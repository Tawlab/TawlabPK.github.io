<?php
session_start();
require 'config.php';

$id = $_GET['id'];

// ดึงข้อมูลนักเรียนที่ต้องการแก้ไข
$stmt = $conn->prepare("SELECT * FROM student WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: admin_manage_student.php");
    exit();
}

$student = $result->fetch_assoc();

// การแก้ไขข้อมูลนักเรียน
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_code = $_POST['student_code'];
    $first_name = $_POST['Fname'];
    $last_name = $_POST['Lname'];
    $class = $_POST['class'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // ตรวจสอบค่าซ้ำสำหรับ student_code
    $check_stmt = $conn->prepare("SELECT * FROM student WHERE student_code = ? AND id != ?");
    $check_stmt->bind_param("si", $student_code, $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_message = "Student code already exists. Please use a unique student code.";
    } else {
        $update_stmt = $conn->prepare("UPDATE student SET student_code = ?, Fname = ?, Lname = ?, class = ?, address = ?, phone = ? WHERE id = ?");
        $update_stmt->bind_param("ssssssi", $student_code, $first_name, $last_name, $class, $address, $phone, $id);

        if ($update_stmt->execute()) {
            header("Location: admin_manage_student.php");
            exit();
        } else {
            $error_message = "Error updating student: " . $update_stmt->error;
        }

        $update_stmt->close();
    }

    $check_stmt->close();
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Edit Student</title>
</head>
<body>
    <h1 id="title" class="text-center">Edit Student</h1>

    <div class="container mt-4">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="admin_edit_student.php?id=<?php echo $id; ?>" method="POST">
            <div class="mb-3">
                <label for="student_code" class="form-label">Student Code</label>
                <input type="text" class="form-control" id="student_code" name="student_code" value="<?php echo htmlspecialchars($student['student_code']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="Fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="Fname" name="Fname" value="<?php echo htmlspecialchars($student['Fname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="Lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="Lname" name="Lname" value="<?php echo htmlspecialchars($student['Lname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="class" class="form-label">Class</label>
                <input type="text" class="form-control" id="class" name="class" value="<?php echo htmlspecialchars($student['class']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($student['address']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required pattern="\d{10}" title="Please enter a valid 10-digit phone number">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="admin_manage_student.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <style>
        body {
            background-color: #f5f5f5;
        }
        label {
            color: black;
        }
        #title {
            text-align: center;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #4caf50;
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
        .alert {
            margin-bottom: 20px;
        }
    </style>
</body>
</html>
