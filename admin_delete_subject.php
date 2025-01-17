<?php
session_start();
require 'config.php';

// ตรวจสอบว่ามี ID ถูกส่งมา
if (!isset($_GET['id'])) {
    header("Location: admin_manage_subject.php");
    exit();
}

$id = $_GET['id'];

// ลบนักเรียน
$stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Student deleted successfully.";
    header("Location: admin_manage_subject.php");
    exit();
} else {
    $_SESSION['error_message'] = "Error deleting student: " . $stmt->error;
    header("Location: admin_manage_subject.php");
    exit();
}

$stmt->close();
?>
