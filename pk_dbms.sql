-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2024 at 09:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pk_dbms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','late') NOT NULL,
  `checked_by` int(11) NOT NULL,
  `date_checked` date NOT NULL DEFAULT curdate(),
  `remarks` text DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `subject_id`, `date`, `status`, `checked_by`, `date_checked`, `remarks`, `modified_by`) VALUES
(70, 13, 7, '2024-12-22', 'present', 4, '2024-12-22', NULL, NULL),
(71, 23, 7, '2024-12-22', 'late', 4, '2024-12-22', NULL, NULL),
(72, 24, 7, '2024-12-22', 'late', 4, '2024-12-22', NULL, 3),
(74, 26, 7, '2024-12-22', 'present', 4, '2024-12-22', NULL, NULL),
(75, 13, 2, '2024-12-22', 'present', 4, '2024-12-22', NULL, NULL),
(76, 23, 2, '2024-12-22', 'absent', 4, '2024-12-22', NULL, NULL),
(77, 24, 2, '2024-12-22', 'late', 4, '2024-12-22', NULL, 18),
(79, 26, 2, '2024-12-22', 'present', 4, '2024-12-22', NULL, NULL),
(80, 16, 2, '2024-12-22', 'absent', 4, '2024-12-22', NULL, 18),
(81, 13, 6, '2024-12-23', 'present', 18, '2024-12-23', NULL, NULL),
(82, 23, 6, '2024-12-23', 'present', 18, '2024-12-23', NULL, NULL),
(83, 24, 6, '2024-12-23', 'present', 18, '2024-12-23', NULL, 3),
(85, 13, 4, '2024-12-23', 'late', 18, '2024-12-23', NULL, NULL),
(86, 23, 4, '2024-12-23', 'present', 18, '2024-12-23', NULL, NULL),
(87, 24, 4, '2024-12-23', 'present', 18, '2024-12-23', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `class_name` varchar(20) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `student_code` varchar(50) NOT NULL,
  `Fname` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `class` varchar(20) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `student_code`, `Fname`, `Lname`, `class`, `phone`, `address`) VALUES
(13, '10003', 'หมอเสง', 'เซ็งเป็ด', 'ป.1', '0808214241', 'RMUTT'),
(16, '11567', 'สมปอง', 'วัดซับแหมน', 'ป.3', '0808214241', 'RMUTT'),
(20, '12745', 'สมัย', 'ยกยองยันยืน', 'ป.5', '0897898523', '124/44'),
(21, '12345', 'สมยอม', 'ยันยืน', 'ป.2', '0874561441', '192/168'),
(23, '10001', 'อาปาเช่', 'เปชาเน่', 'ป.1', '0000000000', 'กฟหก'),
(24, '10002', 'ยอดชาย', 'มันบ่แม่น', 'ป.1', '0890742563', 'คลองหก'),
(26, '10005', 'ประวิท', 'จันจัน', 'ป.5', '0808214771', 'รังสิต-นครนายก'),
(29, '11012', 'ธนากร', 'ยอดยเี่ยม', 'ป.3', '080999241', 'รังสิต-นครนายก');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `credits` int(11) NOT NULL,
  `teacher_name` varchar(255) NOT NULL,
  `subject_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `credits`, `teacher_name`, `subject_description`) VALUES
(2, '2653', 'พละศึกษา', 3, 'ชุมพล', 'พละ'),
(4, '15240', 'ภาษาไทย', 3, 'สมจิตร', 'ไทยเเลนด์'),
(6, '11234', 'ภาษาอังกฤษ', 3, 'สมหมาย', 'ภาษาอังกฤษ'),
(7, '47842', 'ประวัติศาสตร์', 1, 'ประพันธ์', 'oapkdkapskdkspd['),
(13, '16620', 'พุทธศาสนา', 2, 'ชายชาญ', 'หฟกฟหก');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `t_username` varchar(50) NOT NULL,
  `t_password` varchar(255) NOT NULL,
  `t_Fname` varchar(50) NOT NULL,
  `t_Lname` varchar(50) NOT NULL,
  `t_email` varchar(100) NOT NULL,
  `t_address` text NOT NULL,
  `t_phone` varchar(15) DEFAULT NULL,
  `role` enum('teacher','admin') DEFAULT 'teacher',
  `Fname` varchar(255) NOT NULL,
  `Lname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `t_username`, `t_password`, `t_Fname`, `t_Lname`, `t_email`, `t_address`, `t_phone`, `role`, `Fname`, `Lname`) VALUES
(3, 'Goe013705', '$2y$10$i9n0NggT4geVE2Ul6xkKPe52JQHAvsuScn6MXsTicmMpGFnoS4RKe', 'Arbudabe', 'Arbedabu', 'AAAAAA@asdsd', 'ffdsaf', '0808214000', 'teacher', '', ''),
(5, 'Goe_1540', '$2y$10$3SaQtOLQhqWFK9a7HLzokuyBzqTEvoIcuUO6wjBF748R39IBZCez6', 'Adisom', 'sonpeng', 'Goe@01370', 'Goe_1540', '0808214241', 'teacher', '', ''),
(6, 'Gzee01', '$2y$10$SlMFZ1xQ0pPid20H8FrjO.91B6JSyiqA6CjRoTIm.3/PV./ypXzay', 'Gzee01', 'Gzee01', 'Gzee01@ggg', 'ffdsaf', '0808214241', 'teacher', '', ''),
(18, 'Adison', '$2y$10$/lunSVmsBzw28Jyx/ZY13uVWw./KRdncgZFjiHHHzyNrO8q1QEB26', 'Sompeng', 'Adison', 'adisodaj@gmail.xom', '25/5', '0888887888', 'teacher', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `fk_modified_by` (`modified_by`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_name` (`class_name`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_code` (`student_code`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_username` (`t_username`),
  ADD UNIQUE KEY `t_email` (`t_email`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_modified_by` FOREIGN KEY (`modified_by`) REFERENCES `teacher` (`id`);

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `teacher` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
