<?php
$servername = "localhost";
$username = "root";
$password = ""; // หรือ 'your_password'
$dbname = "pk_dbms";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
