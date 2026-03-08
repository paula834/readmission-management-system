-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2026 at 07:28 PM
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
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `level` enum('Artisan','Certificate','Diploma') NOT NULL,
  `semester` enum('SEM 1','SEM 2') NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admission_forms`
--

CREATE TABLE `admission_forms` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `other_names` varchar(150) DEFAULT NULL,
  `passport_id` varchar(50) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `course_applied` varchar(255) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `kcse_grade` varchar(10) DEFAULT NULL,
  `mode_of_study` enum('Full-time','Part-time','Evening') DEFAULT NULL,
  `intake` enum('January','May','September') DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL,
  `id_passport` varchar(50) DEFAULT NULL,
  `crnm_status` varchar(50) DEFAULT NULL,
  `hod_signature` varchar(100) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `serial_number` varchar(50) DEFAULT NULL,
  `previous_school1` varchar(255) DEFAULT NULL,
  `previous_school2` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `registrar_name` varchar(100) DEFAULT NULL,
  `letter_generated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admission_forms`
--

INSERT INTO `admission_forms` (`id`, `user_id`, `surname`, `other_names`, `passport_id`, `county`, `postal_address`, `telephone`, `email`, `phone`, `date_of_birth`, `gender`, `course_applied`, `module`, `kcse_grade`, `mode_of_study`, `intake`, `status`, `submitted_at`, `approved_at`, `id_passport`, `crnm_status`, `hod_signature`, `reviewed_at`, `serial_number`, `previous_school1`, `previous_school2`, `created_at`, `registrar_name`, `letter_generated_at`) VALUES
(1, 4, ' kim', 'paula', NULL, 'Nairobi', NULL, NULL, 'gitapaula43@gmail.com', '0711681305', '2001-10-06', 'Female', 'Information Communication Technology', NULL, 'D+', 'Full-time', 'January', 'approved', '2026-01-16 12:35:38', NULL, '676767', NULL, NULL, '2026-01-17 13:15:42', NULL, 'Murema', 'Hon John Njoronge', '2026-01-16 16:34:57', NULL, NULL),
(4, 6, ' kim', 'paula wanjiku', NULL, 'Nairobi', NULL, NULL, 'paulakim226@gmail.com', '0711681305', '2007-08-09', 'Female', 'Diploma in ict', NULL, 'D+', 'Full-time', 'January', 'approved', '2026-02-19 13:10:25', NULL, '676767', NULL, NULL, NULL, 'TNNP/ADM/2026/0004', 'Murema', 'Hon John Njoronge', '2026-02-19 16:10:25', 'Registrar Academics', '2026-02-19 16:11:32'),
(5, 7, ' kim', 'victor', NULL, 'Nairobi', NULL, NULL, 'paulakim226@gmail.com', '0711681305', '2005-02-22', 'Male', 'Diploma in ict', NULL, 'D+', 'Full-time', 'January', 'pending', '2026-03-08 18:04:27', NULL, '676767', NULL, NULL, NULL, NULL, 'Murema', 'Hon John Njoronge', '2026-03-08 21:04:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `level` enum('Artisan','Certificate','Diploma') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `readmissions`
--

CREATE TABLE `readmissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_course` varchar(255) DEFAULT NULL,
  `last_level` varchar(50) DEFAULT NULL,
  `last_year` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `reviewed_at` datetime DEFAULT NULL,
  `result_slip` varchar(255) DEFAULT NULL,
  `fee_statement` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `readmissions`
--

INSERT INTO `readmissions` (`id`, `user_id`, `last_course`, `last_level`, `last_year`, `reason`, `status`, `created_at`, `reviewed_at`, `result_slip`, `fee_statement`) VALUES
(3, 4, 'information communication technology', 'Diploma', 2, 'continuing student', 'approved', '2026-01-16 09:00:14', '2026-01-16 09:00:49', NULL, NULL),
(4, 5, 'information communication technology', 'Diploma', 2, 'njk', 'approved', '2026-01-17 13:28:11', '2026-01-17 13:28:53', NULL, NULL),
(5, 6, 'Diploma in information communication technology', 'Diploma', 2, 'continuing student', 'approved', '2026-02-19 15:07:09', '2026-02-19 16:06:05', NULL, NULL),
(6, 7, 'Diploma in information communication technology', 'Diploma', 2, 'I\'m a continuing student', 'approved', '2026-02-21 10:22:09', '2026-02-21 18:36:36', '1771658529_result_69995d21ef28d.png', '1771658529_fee_69995d21ef293.pdf'),
(7, 8, 'Diploma in Automotive Engineering', 'Diploma', 2, 'continuing student', 'pending', '2026-03-08 21:10:51', NULL, '1772993451_result_69adbbabced65.pdf', '1772993451_fee_69adbbabced69.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `readmission_applications`
--

CREATE TABLE `readmission_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_comment` text DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `admission_no` varchar(20) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `last_year_of_study` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verify_token` varchar(64) DEFAULT NULL,
  `readmission_status` enum('none','pending','approved','rejected') NOT NULL DEFAULT 'none',
  `admission_status` enum('locked','pending','approved') NOT NULL DEFAULT 'locked',
  `readmission_reason` text DEFAULT NULL,
  `readmission_date` date DEFAULT NULL,
  `readmission_applied_at` datetime DEFAULT NULL,
  `role` enum('student','admin') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `admission_no`, `full_name`, `email`, `password`, `gender`, `profile_picture`, `is_verified`, `last_year_of_study`, `created_at`, `verify_token`, `readmission_status`, `admission_status`, `readmission_reason`, `readmission_date`, `readmission_applied_at`, `role`) VALUES
(2, NULL, 'System Admin', 'admin@tnnp.ac.ke', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Male', NULL, 0, 0, '2026-01-15 14:03:10', NULL, 'none', 'locked', NULL, NULL, NULL, 'admin'),
(4, NULL, 'paula kim', 'gitapaula43@gmail.com', '$2y$10$twnt7M5qE5unvHq7BLxdSeTVO2mjfLBYKkieB1TqD.sUANvu24Goi', 'Female', 'uploads/female.jpeg', 0, 2, '2026-01-16 04:44:14', '00c95a23b27ec2a6cbce42f5cfaf81640adac85ac769124bc4fc18afa232b5c3', 'approved', 'approved', NULL, NULL, '2026-01-16 09:00:14', 'student'),
(5, NULL, 'morris', '2108612@students.kcau.ac.ke', '$2y$10$Z2OQMQqIdvNAinEwde4w..QjlqHYCrjbYIIQQBy87/OF5bOk1IZqu', 'Male', 'uploads/male.jpeg', 0, 2, '2026-01-17 10:27:16', 'df9ed23e991393f7aa202a850dfa00049670ff6f8b7b3fa6a04a56f41d9c3efd', 'approved', 'locked', NULL, NULL, '2026-01-17 13:28:11', 'student'),
(6, NULL, 'paula kim', 'paulakim226@gmail.com', '$2y$10$AVHr2JGwYfZMEw8N/MAXYu5GRQLj7fTS96peuKLKAeVK2Ur7Omb2O', 'Female', 'uploads/female.jpeg', 0, 2, '2026-02-09 03:30:07', '5231d83252e59a8fd03f0d01e6eadc8f46d1518298090413f3ad235000197fb3', 'approved', 'approved', NULL, NULL, '2026-02-19 15:07:09', 'student'),
(7, NULL, 'pauline gitau', 'wanjikupaula437@gmail.com', '$2y$10$85g0mHtgFr0NcRR0QvLnOuMM320flc2O23dn2a0uwFeCItmGL/jbO', 'Female', 'uploads/female.jpeg', 0, 1, '2026-02-21 06:01:48', '84712e9d93c8fbd42f603c6dd756146e5cce24222be46adcb3131a7c936fd1a1', 'approved', 'pending', NULL, NULL, '2026-02-21 10:22:09', 'student'),
(8, NULL, 'morris gitau', 'morrisgita022@gmail.com', '$2y$10$cSMeJq/z3uhPebl9sPEz5OoSfB7xJ.ZwbJQmz3pDTaCagOolBSlDS', 'Male', 'uploads/male.jpeg', 0, 2, '2026-03-08 18:08:03', '04fe1fdd920235b530d964d5ba9d378dc94262a9c2618ca65d3a542a333f327f', 'pending', 'locked', NULL, NULL, '2026-03-08 21:10:51', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `admission_forms`
--
ALTER TABLE `admission_forms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `readmissions`
--
ALTER TABLE `readmissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `readmission_applications`
--
ALTER TABLE `readmission_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admission_forms`
--
ALTER TABLE `admission_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `readmissions`
--
ALTER TABLE `readmissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `readmission_applications`
--
ALTER TABLE `readmission_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admissions`
--
ALTER TABLE `admissions`
  ADD CONSTRAINT `admissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `admissions_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `admission_forms`
--
ALTER TABLE `admission_forms`
  ADD CONSTRAINT `admission_forms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `readmissions`
--
ALTER TABLE `readmissions`
  ADD CONSTRAINT `readmissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `readmission_applications`
--
ALTER TABLE `readmission_applications`
  ADD CONSTRAINT `readmission_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
