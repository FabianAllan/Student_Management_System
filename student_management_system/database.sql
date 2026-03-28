-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2026 at 09:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `full_name`) VALUES
(1, 'admin', 'admin123', 'System Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `credits` int(11) NOT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_code`, `course_name`, `credits`, `dept_id`) VALUES
(1, 'CYB101', 'Ethical Hacking', 3, 1),
(2, 'CYB102', 'Network Security', 4, 1),
(3, 'CYB103', 'Cryptography', 3, 1),
(4, 'CYB104', 'Digital Forensics', 3, 1),
(5, 'AI201', 'Machine Learning', 4, 2),
(6, 'AI202', 'Neural Networks', 3, 2),
(7, 'AI203', 'Natural Language Processing', 3, 2),
(8, 'AI204', 'Robotics & Vision', 4, 2),
(9, 'SE301', 'Web Architecture', 3, 3),
(10, 'SE302', 'Mobile App Development', 4, 3),
(11, 'SE303', 'Software Testing', 2, 3),
(12, 'SE304', 'Cloud Computing', 3, 3),
(13, 'DS401', 'Statistical Analysis', 4, 4),
(14, 'DS402', 'Data Visualization', 3, 4),
(15, 'DS403', 'Big Data Systems', 4, 4),
(16, 'DS404', 'Predictive Modeling', 3, 4),
(20, 'CYB105', 'Network Pentesting', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`dept_id`, `dept_name`) VALUES
(2, 'Artificial Intelligence'),
(1, 'CyberSecurity'),
(4, 'Data Science'),
(3, 'Software Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `grade_letter` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`grade_id`, `student_id`, `course_id`, `score`, `grade_letter`) VALUES
(1, 1, 1, 88.50, 'A'),
(2, 1, 2, 75.00, 'B+'),
(3, 1, 3, 65.00, 'C+'),
(4, 5, 1, 55.00, 'D+'),
(5, 5, 4, 82.00, 'A'),
(6, 6, 2, 91.00, 'A'),
(7, 6, 3, 45.00, 'F'),
(8, 2, 5, 78.00, 'B+'),
(9, 2, 6, 84.00, 'A'),
(10, 2, 7, 62.00, 'C'),
(11, 7, 5, 42.00, 'F'),
(12, 7, 8, 89.00, 'A'),
(13, 3, 9, 95.00, 'A'),
(14, 3, 10, 88.00, 'A'),
(15, 8, 11, 58.00, 'D+'),
(16, 8, 12, 71.00, 'B'),
(17, 4, 13, 67.00, 'C+'),
(18, 4, 14, 74.00, 'B'),
(19, 9, 15, 92.00, 'A'),
(20, 9, 16, 81.00, 'A'),
(23, 14, 15, 80.00, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT '123456',
  `dept_id` int(11) DEFAULT NULL,
  `registration_no` varchar(20) GENERATED ALWAYS AS (concat('STU-',`student_id`)) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `email`, `password`, `dept_id`) VALUES
(1, 'Alice', 'Mumbi', 'alicemumbi@school.com', '123456', 1),
(2, 'Kevin', 'Oduor', 'kevinoduor@school.com', '123456', 2),
(3, 'Sarah', 'Kwamboka', 'sarahkwamboka@school.com', '123456', 3),
(4, 'Michael', 'Kamau', 'michaelkamau@school.com', '123456', 4),
(5, 'Emma', 'Wasilwa', 'emmawasilwa@school.com', '123456', 1),
(6, 'Robert', 'Kanambo', 'robertkanambo@school.com', '123456', 1),
(7, 'Aisha', 'Kanyiri', 'aishakanyiri@school.com', '123456', 2),
(8, 'Liam', 'Omondi', 'liamomondi@school.com', '123456', 3),
(9, 'Elena', 'Mutua', 'elenamutua@school.com', '123456', 4),
(10, 'Lerry', 'Masai', 'lerrymasai@school.com', '123456', 1),
(12, 'John', 'Wasilwa', 'johnwasilwa@school.com', '123456', 4),
(13, 'Ahmed', 'Boina', 'ahmedboina@school.com', '123456', 2),
(14, 'Michelle', 'Njoki', 'michellenjoki@school.com', '123456', 4),
(17, 'Yolanda', 'Odinga', 'yolandaodinga@school.com', '123456', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course_code` (`course_code`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`dept_id`),
  ADD UNIQUE KEY `dept_name` (`dept_name`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `dept_id` (`dept_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`dept_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`dept_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
