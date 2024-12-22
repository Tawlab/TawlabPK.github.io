<?php
session_start();
require 'config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่ามี ID ถูกส่งมา
if (!isset($_GET['id'])) {
    header("Location: manage_students.php");
    exit();
}

$id = $_GET['id'];

// ลบนักเรียน
$stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Student deleted successfully.";
    header("Location: manage_students.php");
    exit();
} else {
    $_SESSION['error_message'] = "Error deleting student: " . $stmt->error;
    header("Location: manage_students.php");
    exit();
}

$stmt->close();
?>
