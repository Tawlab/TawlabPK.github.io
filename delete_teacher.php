<?php
session_start();
require 'config.php';

// ตรวจสอบว่ามีการส่ง id มาใน URL หรือไม่
if (isset($_GET['id'])) {
    $teacher_id = $_GET['id'];

    // ลบข้อมูลครูจากฐานข้อมูล
    $stmt = $conn->prepare("DELETE FROM teacher WHERE id = ?");
    $stmt->bind_param("i", $teacher_id);

    if ($stmt->execute()) {
        // การลบสำเร็จ
        $_SESSION['success_message'] = "ลบข้อมูลครูสำเร็จ";
    } else {
        // การลบไม่สำเร็จ
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการลบข้อมูล";
    }

    $stmt->close();
}

// นำผู้ใช้กลับไปยังหน้า manage_teacher.php
header("Location: admin_manage_teacher.php");
exit();
?>
