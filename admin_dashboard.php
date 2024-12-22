<?php
session_start();

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
            max-width: 800px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-dashboard {
            display: block;
            width: 100%;
            text-align: center;
            padding: 15px;
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: white;
            border: none;
            border-radius: 10px;
        }

        .btn-teacher {
            background-color: #388e3c; /* เขียวขี้ม้า */
        }

        .btn-student {
            background-color: #4caf50; /* เขียว */
        }

        .btn-subject {
            background-color: #66bb6a; /* เขียวอ่อน */
        }

        .btn-attendance {
            background-color: #2e7d32; /* เขียวเข้ม */
        }

        .btn-dashboard:hover {
            opacity: 0.8;
        }
    </style>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1 id="title">Admin Dashboard</h1>

    <div class="container">
        <a href="admin_manage_teacher.php" class="btn btn-dashboard btn-teacher">Manage Teacher</a>
        <a href="admin_manage_student.php" class="btn btn-dashboard btn-student">Manage Student</a>
        <a href="admin_manage_subject.php" class="btn btn-dashboard btn-subject">Manage Subject</a>
        <a href="admin_manage_attendance.php" class="btn btn-dashboard btn-attendance">Manage Attendance</a>
        <a href="index.php" class="btn btn-dashboard btn-danger">Logout</a>
    </div>
</body>
</html>

