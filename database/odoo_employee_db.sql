-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2025 at 02:15 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `odoo_employee_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'Accounting and Finance'),
(2, 'Human Resource Development'),
(3, 'Marketing'),
(4, 'Office Boiz'),
(5, 'Operational'),
(6, 'Production'),
(7, 'Supply Chain and Logistics'),
(8, 'Information Technology Research and Development');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int NOT NULL,
  `department_id` int DEFAULT NULL,
  `position_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `applied_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `processed_at` datetime DEFAULT NULL,
  `processed_by` int DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `department_id`, `position_id`, `name`, `email`, `image_path`, `created_by`, `applied_at`, `processed_at`, `processed_by`, `admin_notes`) VALUES
(1, 6, 0, 'Balmond Saputra', 'mon_putra15@gmail.com', NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(2, 3, 0, 'Guntur Surapati', NULL, NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(4, 4, 0, 'Rivaldi Tazz', 'bobitan@gmail.exe', NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(5, 5, 0, 'TONY STARKS', 'tonystark@stark.com', '', NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(6, 7, 0, 'かのじょ', NULL, NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(7, 7, 0, 'ベレウェブ', 'brew-brew-patapim@gmail.com', NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(8, 6, 0, '英雄緑色', NULL, NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(9, 3, 0, 'Artonia Pendragon', NULL, NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(10, 8, 0, 'Brandon Victorz', 'boneofmysword@gmail.com', NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(11, 4, 0, 'Dokter Aneh', 'arkamm.tusk@gmail.com', NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(12, 8, 0, 'Janggar Pranew', '2373003@maranatha.ac.id', NULL, NULL, '2025-06-14 18:17:46', NULL, NULL, NULL),
(17, 6, 0, 'Asep Balon', 'balon_meledak1748@gmail.com', '', NULL, '2025-06-14 18:17:46', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_positions`
--

CREATE TABLE `job_positions` (
  `position_id` int NOT NULL,
  `department_id` int NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_positions`
--

INSERT INTO `job_positions` (`position_id`, `department_id`, `title`) VALUES
(1, 1, 'Accounting Staff'),
(2, 1, 'Accounting Manager'),
(3, 2, 'HR Staff'),
(4, 2, 'HR Manager'),
(5, 3, 'Marketing Staff'),
(6, 3, 'Marketing Manager'),
(7, 4, 'Cleaning Service'),
(8, 6, 'Production Staff'),
(9, 6, 'Production Manager'),
(10, 7, 'Supply Chain Supervisor'),
(11, 7, 'Supply Chain Manager'),
(12, 8, 'IT Staff'),
(13, 8, 'IT Manager'),
(14, 8, 'System Engineer'),
(15, 8, 'Programmer');

-- --------------------------------------------------------

--
-- Table structure for table `recruitment`
--

CREATE TABLE `recruitment` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `position_id` int NOT NULL,
  `cv_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `application_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','reviewed','accepted','rejected') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruitment`
--

INSERT INTO `recruitment` (`id`, `name`, `email`, `phone`, `position_id`, `cv_path`, `photo_path`, `application_date`, `status`, `notes`) VALUES
(2, 'CaptWolfGt', 'sammygans@gmail.com', '222222444', 1, 'uploads/cv_f7bf7f9f6a8e19c4cbfe907d4820caf6.pdf', 'uploads/photo_576f01758a31009294b0eb906cc30c05.jpg', '2025-06-14 18:45:09', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','employee') COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `employee_id`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_department_id` (`department_id`);

--
-- Indexes for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`position_id`),
  ADD KEY `job_positions_ibfk_1` (`department_id`);

--
-- Indexes for table `recruitment`
--
ALTER TABLE `recruitment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `position_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `recruitment`
--
ALTER TABLE `recruitment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `FK_department_id` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD CONSTRAINT `job_positions_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recruitment`
--
ALTER TABLE `recruitment`
  ADD CONSTRAINT `recruitment_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `job_positions` (`position_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
