-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2025 at 04:08 PM
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
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
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
  `skills_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `department_id`, `position_id`, `skills_id`, `name`, `email`, `image_path`, `created_by`, `created_at`) VALUES
(1, 6, 8, 0, 'Balmond Saputra', 'mon_putra15@gmail.com', 'uploads/0e0e084aef8e3730d47cc0d8a3fa4ad7.jpg', 0, '2025-06-15 04:23:50'),
(2, 3, 6, 0, 'Guntur Surapati', 'guntursurapati@gmail.com', 'uploads/cbb208d8d98e4d67676d6811b6102ed8.jpg', 0, '2025-06-15 04:23:50'),
(4, 4, 7, 0, 'Rivaldi Tazz', 'bobitan@gmail.exe', 'uploads/6954edfcc349810836e69df826ca180f.jpg', 0, '2025-06-15 04:23:50'),
(5, 5, 9, 0, 'TONY STARKS', 'tonystark@stark.com', 'uploads/5acbc030b37130fe7fc9480add14119b.png', 0, '2025-06-15 04:23:50'),
(6, 7, 11, 0, 'かのじょ', 'kanokari@gmail.com', 'uploads/7291e1d0ec782295cf870321af2c7aff.png', 0, '2025-06-15 04:23:50'),
(7, 7, 10, 0, 'ベレウェブ', 'brew-brew-patapim@gmail.com', 'uploads/4bf5df48eb02b8aa5fb8ec7f05799eea.png', 0, '2025-06-15 04:23:50'),
(8, 6, 8, 0, '英雄緑色', 'eishi.h173@gmail.com', 'uploads/b515f50b0db2902eed12b7d8a69d3cca.jpg', 0, '2025-06-15 04:23:50'),
(9, 3, 5, 0, 'Artoria Pendragon', 'artoriapen@gmail.com', 'uploads/53b43fce2ff40dc09ff3e1df8021fdd7.jpg', 0, '2025-06-15 04:23:50'),
(10, 8, 12, 0, 'Brandon Victorz', 'boneofmysword@gmail.com', 'uploads/2ce87c546a6b39c25695dcadb17408c8.png', 0, '2025-06-15 04:23:50'),
(11, 4, 7, 0, 'Dokter Aneh', 'arkamm.tusk@gmail.com', 'uploads/ef2cda0224205c1863c3623691909774.png', 0, '2025-06-15 04:23:50'),
(12, 8, 13, 0, 'Janggar Pranew', '2373003@maranatha.ac.id', 'uploads/21f5c746370d742527996da0876ae872.gif', 0, '2025-06-15 04:23:50'),
(17, 6, 8, 0, 'Asep Balon', 'balon_meledak1748@gmail.com', 'uploads/066d7046e4a1eda50c71724aed3a48e5.jpg', 0, '2025-06-15 04:23:50'),
(24, 2, 3, 0, 'Kiboy Monteg', 'yebtolonginyeb@gmail.com', 'uploads/ce0fcfe6a9ea7c4a470adc3642b2facb.jpg', 1, '2025-06-15 11:07:04'),
(25, 1, 1, 0, 'samidahlah', 'tonyck@gmail.com', 'uploads/c48263ed8d227d0e9eddae5563f9b65d.jpg', NULL, '2025-06-15 11:12:32'),
(27, 6, 9, 0, 'Coach Adi', 'yebtolonginyeb@gmail.com', 'uploads/e6d7aaa36c68502afe056bab0441cabe.png', 1, '2025-06-15 11:16:37'),
(28, 4, 7, 0, 'CaptWolfGt', 'rayganteng@gmail.com', 'uploads/0cd4f155be387b3e63d6129226778fce.jpg', 1, '2025-06-15 11:26:00'),
(30, 3, 5, 0, 'Asep kardi', 'balon_asep1748@gmail.com', 'uploads/2ddfa18c68672bbb7f6c394144ae7afc.jpg', 1, '2025-06-15 13:19:34'),
(31, 7, 11, 0, 'Kairi Ygnacio Rayosdelsol', 'kairirandosol@gmail.com', 'uploads/c6fe90bca962bb9fe625ac6262c98a16.png', 1, '2025-06-15 13:22:53'),
(32, 8, 15, 0, 'kiboyzzz', 'zzz@gmail.com', 'uploads/photo_8fed4c63d148cab9db96356a99d31698.jpg', 1, '2025-06-15 14:05:58'),
(34, 1, 1, 0, 'Brody Saputri', 'brodisaputri@gmail.com', 'uploads/2a1e94306a766fbca148aa42f7b36fc4.jpg', NULL, '2025-06-16 06:53:43'),
(35, 1, 2, 0, 'Counting Star', 'hitungbintang@gmail.com', 'uploads/1742f52f17a0ae8f85dd373c8233e9d5.jpg', NULL, '2025-06-16 06:54:31'),
(36, 2, 3, 0, 'Kim Sa My', 'sammyrikiganteng@gmail.com', 'uploads/eeed0acd2d7aee4c4e2aacbb300e4a13.png', NULL, '2025-06-16 06:58:04'),
(37, 2, 4, 0, 'グレゴリー', 'guragero@gmail.com', 'uploads/89995e215b7afe9f48c7ff196ced4aa6.gif', NULL, '2025-06-16 06:58:44'),
(38, 2, 3, 0, '성권톨', 'sungkwondol@gmail.com', 'uploads/88c71af4adc84ef87f8f92f1e584b26a.jpg', NULL, '2025-06-16 06:59:52'),
(39, 6, 8, 0, 'Slank Ganjar', 'tonyck169@gmail.com', 'uploads/8d8e4a2460b4485d193695762b7337a4.gif', NULL, '2025-06-16 07:00:34'),
(40, 7, 11, 0, 'おばあさん', 'obaasankawaii@gmail.com', 'uploads/e44cb2c9805cc35a3017599e17325c5e.jpg', NULL, '2025-06-16 07:05:39'),
(41, 8, 14, 0, 'Kuda Kramat', 'chandrawong@gmail.kakek.billgates', 'uploads/f3428f28b4f62b7071471ed5d72c4547.png', NULL, '2025-06-16 07:06:52'),
(42, 8, 12, 0, 'Anomali へんたい', 'axfer169@yahoo.co.id', 'uploads/042aacbae9b09c9de35ad094949a117d.jpg', NULL, '2025-06-16 07:08:29'),
(43, 8, 15, 0, 'Mark ZuGrebeg', 'ybrap@javtiful.com', 'uploads/73dd5d46b546759940d3b232da3b29dc.jpg', NULL, '2025-06-16 07:09:48'),
(44, 8, 14, 0, 'Putih Victim', 'johnklemen@gmail.com', 'uploads/ab3923b8e2a852d7bc5a52e8c0d2a68e.gif', NULL, '2025-06-16 07:13:02'),
(45, 8, 14, 0, 'Gregorius Subianto', 'mcgrebeg@yahoo.co.id', 'uploads/d9becea3999eb12807f121a79ae6ff95.jpg', NULL, '2025-06-16 07:14:11'),
(46, 8, 12, 0, 'Ken Surono', 'kensurono111@gmail.com', 'uploads/2ee294be5a6d8d2bade4b088cdb5fe24.gif', NULL, '2025-06-16 07:15:34'),
(47, 6, 8, 0, 'スティーブン・フェリックス', 'sutigbelip@gmail.com', 'uploads/e46e9f4f66239a31b192536d5b4328ca.jpg', NULL, '2025-06-16 07:17:42'),
(48, 6, 6, 0, '死柄木弔', 'tomura.shiragaki@177013.comc', 'uploads/d58befa456b25e19961c78fe9c376045.png', NULL, '2025-06-16 07:18:13'),
(49, 3, 5, 0, '宮下玲奈', 'renakorusu@gmail.com', 'uploads/8048c9a9df08395a7cae5d6bd620220c.jpg', NULL, '2025-06-16 07:23:14'),
(50, 7, 11, 0, ' 深田えいみ', 'adakufimie@kakao.com', 'uploads/77c4f9cb96607f1b7088a5dfb052e0fa.jpg', NULL, '2025-06-16 07:26:08');

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary`
--

CREATE TABLE `employee_salary` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_account_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tax_status` enum('TK0','TK1','TK2','TK3','K0','K1','K2','K3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'TK0',
  `npwp_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `effective_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary_components`
--

CREATE TABLE `employee_salary_components` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `component_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_skills`
--

CREATE TABLE `employee_skills` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `skill_id` int NOT NULL,
  `tingkat_keahlian` enum('pemula','menengah','mahir','ahli') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_skills`
--

INSERT INTO `employee_skills` (`id`, `employee_id`, `skill_id`, `tingkat_keahlian`, `catatan`, `created_at`) VALUES
(4, 42, 39, 'menengah', 'being an hero', '2025-06-16 15:40:29'),
(5, 12, 16, 'mahir', '', '2025-06-16 15:41:08');

-- --------------------------------------------------------

--
-- Table structure for table `job_positions`
--

CREATE TABLE `job_positions` (
  `position_id` int NOT NULL,
  `department_id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
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
(11, 7, 'Supply Chain Staff'),
(12, 8, 'IT Staff'),
(13, 8, 'IT Manager'),
(14, 8, 'System Engineer'),
(15, 8, 'Programmer');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_attendance`
--

CREATE TABLE `payroll_attendance` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('present','absent','late','half_day','leave','holiday') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'present',
  `overtime_hours` decimal(5,2) DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_components`
--

CREATE TABLE `payroll_components` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('earning','deduction','tax','benefit','reimbursement') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_taxable` tinyint(1) NOT NULL DEFAULT '0',
  `is_recurring` tinyint(1) NOT NULL DEFAULT '1',
  `calculation_type` enum('fixed','percentage','formula') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'fixed',
  `calculation_value` decimal(10,2) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_components`
--

INSERT INTO `payroll_components` (`id`, `name`, `type`, `is_taxable`, `is_recurring`, `calculation_type`, `calculation_value`, `description`) VALUES
(1, 'Basic Salary', 'earning', 1, 1, 'fixed', NULL, 'Basic monthly salary'),
(2, 'Transport Allowance', 'earning', 1, 1, 'fixed', 1000000.00, 'Monthly transport allowance'),
(3, 'Meal Allowance', 'earning', 1, 1, 'fixed', 800000.00, 'Monthly meal allowance'),
(4, 'Position Allowance', 'earning', 1, 1, 'fixed', 1500000.00, 'Monthly position allowance'),
(5, 'Overtime Pay', 'earning', 1, 0, 'fixed', NULL, 'Overtime payment'),
(6, 'BPJS Kesehatan', 'deduction', 0, 1, 'percentage', 1.00, 'Employee BPJS Kesehatan contribution'),
(7, 'BPJS Ketenagakerjaan', 'deduction', 0, 1, 'percentage', 2.00, 'Employee BPJS Ketenagakerjaan contribution'),
(8, 'Income Tax (PPh 21)', 'tax', 0, 1, 'formula', NULL, 'Monthly income tax deduction'),
(9, 'Loan Repayment', 'deduction', 0, 0, 'fixed', NULL, 'Employee loan repayment'),
(10, 'Performance Bonus', 'earning', 1, 0, 'fixed', NULL, 'Performance-based bonus');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_jamsostek_config`
--

CREATE TABLE `payroll_jamsostek_config` (
  `id` int NOT NULL,
  `effective_date` date NOT NULL,
  `bpjs_kes_employee_rate` decimal(5,2) NOT NULL COMMENT 'Percentage',
  `bpjs_kes_company_rate` decimal(5,2) NOT NULL COMMENT 'Percentage',
  `bpjs_tk_employee_rate` decimal(5,2) NOT NULL COMMENT 'Percentage',
  `bpjs_tk_company_rate` decimal(5,2) NOT NULL COMMENT 'Percentage',
  `bpjs_kes_max_amount` decimal(12,2) DEFAULT NULL,
  `bpjs_tk_max_amount` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_jamsostek_config`
--

INSERT INTO `payroll_jamsostek_config` (`id`, `effective_date`, `bpjs_kes_employee_rate`, `bpjs_kes_company_rate`, `bpjs_tk_employee_rate`, `bpjs_tk_company_rate`, `bpjs_kes_max_amount`, `bpjs_tk_max_amount`) VALUES
(1, '2025-01-01', 1.00, 4.00, 2.00, 3.70, 12000000.00, 8000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_periods`
--

CREATE TABLE `payroll_periods` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('draft','processing','completed','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_records`
--

CREATE TABLE `payroll_records` (
  `id` int NOT NULL,
  `period_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `total_earnings` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_deductions` decimal(12,2) NOT NULL DEFAULT '0.00',
  `gross_pay` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `net_pay` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `payment_date` date DEFAULT NULL,
  `payment_method` enum('bank_transfer','cash','cheque') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_record_details`
--

CREATE TABLE `payroll_record_details` (
  `id` int NOT NULL,
  `payroll_record_id` int NOT NULL,
  `component_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_tax_config`
--

CREATE TABLE `payroll_tax_config` (
  `id` int NOT NULL,
  `tax_year` year NOT NULL,
  `tax_status` enum('TK0','TK1','TK2','TK3','K0','K1','K2','K3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ptkp_amount` decimal(12,2) NOT NULL COMMENT 'Penghasilan Tidak Kena Pajak',
  `tax_rate_1` decimal(5,2) NOT NULL COMMENT 'Rate for first bracket',
  `tax_rate_2` decimal(5,2) NOT NULL COMMENT 'Rate for second bracket',
  `tax_rate_3` decimal(5,2) NOT NULL COMMENT 'Rate for third bracket',
  `tax_rate_4` decimal(5,2) NOT NULL COMMENT 'Rate for fourth bracket',
  `bracket_1_max` decimal(12,2) DEFAULT NULL COMMENT 'Max amount for first bracket',
  `bracket_2_max` decimal(12,2) DEFAULT NULL COMMENT 'Max amount for second bracket',
  `bracket_3_max` decimal(12,2) DEFAULT NULL COMMENT 'Max amount for third bracket'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_tax_config`
--

INSERT INTO `payroll_tax_config` (`id`, `tax_year`, `tax_status`, `ptkp_amount`, `tax_rate_1`, `tax_rate_2`, `tax_rate_3`, `tax_rate_4`, `bracket_1_max`, `bracket_2_max`, `bracket_3_max`) VALUES
(1, '2025', 'TK0', 54000000.00, 5.00, 15.00, 25.00, 30.00, 60000000.00, 250000000.00, 500000000.00),
(2, '2025', 'TK1', 58500000.00, 5.00, 15.00, 25.00, 30.00, 60000000.00, 250000000.00, 500000000.00),
(3, '2025', 'TK2', 63000000.00, 5.00, 15.00, 25.00, 30.00, 60000000.00, 250000000.00, 500000000.00),
(4, '2025', 'TK3', 67500000.00, 5.00, 15.00, 25.00, 30.00, 60000000.00, 250000000.00, 500000000.00),
(5, '2025', 'K0', 58500000.00, 5.00, 15.00, 25.00, 30.00, 60000000.00, 250000000.00, 500000000.00),
(6, '2025', 'K1', 63000000.00, 5.00, 15.00, 25.00, 30.00, 60000000.00, 250000000.00, 500000000.00),
(7, '2025', 'K2', 67500000.00, 5.00, 15.00, 25.00, 30.00, 60000000.00, 250000000.00, 500000000.00),
(8, '2025', 'K3', 72000000.00, 5.00, 15.00, 25.00, 30.00, 60000000.00, 250000000.00, 500000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `recruitment`
--

CREATE TABLE `recruitment` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `position_id` int NOT NULL,
  `cv_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `photo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `application_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','reviewed','accepted','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `admin_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruitment`
--

INSERT INTO `recruitment` (`id`, `name`, `email`, `phone`, `position_id`, `cv_path`, `photo_path`, `application_date`, `status`, `admin_notes`) VALUES
(6, 'CaptWolfGt', 'tonyck169@gmail.com', '0696969', 8, 'uploads/cv_28d03bf3b4b492c2e05572ed03f57d19.pdf', 'uploads/photo_94d9368906f7d200e88c0d0543642b1c.png', '2025-06-15 14:10:19', 'rejected', ''),
(7, 'kink26', 'kink.konk169@gmail.com', '081-223830598', 1, 'uploads/cv_1087131b5be2f9e0efd89a0462ee0860.pdf', 'uploads/photo_a5a628def03a1f517b35a4b0efe9de9e.jpg', '2025-06-15 17:29:29', 'accepted', ''),
(8, 'kiboymonteg', 'yebtolonginyeb@gmail.com', '07823727', 3, 'uploads/cv_6fc1e8fdc2ae8c18f0fc75b21882bf28.pdf', 'uploads/photo_4a4ab35a65c3a8f94e44ef36b06083b4.png', '2025-06-15 18:06:41', 'accepted', 'ganteng bro'),
(9, 'coach adi', 'yebtolonginyeb1@gmail.com', '0812222', 9, 'uploads/cv_d1f9cb08beb84234c55ea7050085d473.pdf', 'uploads/photo_3e4890c87fd86412c8bdfa78397990aa.png', '2025-06-15 18:13:27', 'accepted', ''),
(10, 'coach adi', 'yebtolonginyeb@gmail.com', '0812222', 9, 'uploads/cv_82270f2edb3220788845a5ddc3e62d41.pdf', 'uploads/photo_9b412e50efda6ce612fda996f5ad020d.png', '2025-06-15 18:16:23', 'accepted', ''),
(11, 'CaptWolfGt', 'rayganteng@gmail.com', '0696969', 7, 'uploads/cv_80adc631cfb5b13189f5709599f22f0f.pdf', 'uploads/photo_7841bbff5f33c2db36bb47cdd5176544.png', '2025-06-15 18:25:39', 'accepted', ''),
(12, 'pencahar', 'kink.konk169@gmail.com', '081-223830598', 2, 'uploads/cv_f9d8466ca67de615e0f5d97f2e11acc5.pdf', 'uploads/photo_37059779c572cc784c43c8e1f93caf01.jpg', '2025-06-15 19:19:01', 'accepted', ''),
(13, 'Asep kardi', 'balon_asep1748@gmail.com', '0696969', 5, 'uploads/cv_1e5e9a76d65f47f48721ccc3aec3c77c.pdf', 'uploads/photo_919a96cae46ec1b1c2842aba122a8a07.jpg', '2025-06-15 20:19:28', 'accepted', ''),
(14, 'kairi', 'kairirandosol@gmail.com', '0812222', 11, 'uploads/cv_954ebf57fe3bbaff463e5ba57d697e3e.pdf', '', '2025-06-15 20:22:44', 'accepted', ''),
(15, 'kiboyzzz', 'zzz@gmail.com', '07823727', 15, 'uploads/cv_218dfbe891ec97acad1c2581d76eb9eb.pdf', 'uploads/photo_8fed4c63d148cab9db96356a99d31698.jpg', '2025-06-15 21:05:50', 'accepted', ''),
(16, 'King of Kings', 'tonyck169@gmail.com', '0696969', 1, 'uploads/cv_e7eb56f6b0397d6260af1246b97268bf.pdf', 'uploads/photo_a2c163f05e6343c23d16711067a1b4d0.gif', '2025-06-16 22:48:33', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int NOT NULL,
  `nama_skill` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_skill` enum('soft_skill','hard_skill') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `nama_skill`, `jenis_skill`, `deskripsi`) VALUES
(1, 'test', 'soft_skill', 'test01'),
(2, 'test2', 'hard_skill', 'test02'),
(3, 'Problem Solving', 'soft_skill', NULL),
(4, 'Adaptability', 'soft_skill', NULL),
(5, 'Communication', 'soft_skill', NULL),
(6, 'Empathy', 'soft_skill', NULL),
(7, 'Leadership', 'soft_skill', NULL),
(8, 'Time Management', 'soft_skill', NULL),
(9, 'Creativity', 'soft_skill', NULL),
(10, 'Attention to Detail', 'soft_skill', NULL),
(11, 'Interpersonal Skills', 'soft_skill', NULL),
(12, 'Negotiation', 'soft_skill', NULL),
(13, 'Persuasion', 'soft_skill', NULL),
(14, 'Strategic Thinking', 'soft_skill', NULL),
(15, 'Decision Making', 'soft_skill', NULL),
(16, 'Teamwork', 'soft_skill', NULL),
(17, 'Initiative', 'soft_skill', NULL),
(18, 'Visionary Thinking', 'soft_skill', NULL),
(19, 'Conflict Management', 'soft_skill', NULL),
(20, 'Collaboration', 'soft_skill', NULL),
(21, 'Patience', 'soft_skill', NULL),
(22, 'Presentation', 'soft_skill', NULL),
(23, 'Organization', 'soft_skill', NULL),
(24, 'Critical Thinking', 'soft_skill', NULL),
(25, 'Learning Agility', 'soft_skill', NULL),
(26, 'Network Security', 'hard_skill', NULL),
(27, 'Penetration Testing', 'hard_skill', NULL),
(28, 'Recruitment Tools', 'hard_skill', NULL),
(29, 'Labor Law', 'hard_skill', NULL),
(30, 'Curriculum Planning', 'hard_skill', NULL),
(31, 'Faculty Systems', 'hard_skill', NULL),
(32, 'HTML', 'hard_skill', NULL),
(33, 'CSS', 'hard_skill', NULL),
(34, 'Content Management Systems', 'hard_skill', NULL),
(35, 'Social Media Analytics', 'hard_skill', NULL),
(36, 'Event Planning', 'hard_skill', NULL),
(37, 'CRM Software', 'hard_skill', NULL),
(38, 'Sales Forecasting', 'hard_skill', NULL),
(39, 'Business Operations', 'hard_skill', NULL),
(40, 'Finance Tools', 'hard_skill', NULL),
(41, 'SEO', 'hard_skill', NULL),
(42, 'Google Analytics', 'hard_skill', NULL),
(43, 'Technical Writing', 'hard_skill', NULL),
(44, 'Editing Software', 'hard_skill', NULL),
(45, 'Business Strategy', 'hard_skill', NULL),
(46, 'Financial Planning', 'hard_skill', NULL),
(47, 'UI/UX Design', 'hard_skill', NULL),
(48, 'Figma', 'hard_skill', NULL),
(49, 'Adobe XD', 'hard_skill', NULL),
(50, 'Project Coordination', 'hard_skill', NULL),
(51, 'Performance Metrics', 'hard_skill', NULL),
(52, 'JavaScript', 'hard_skill', NULL),
(53, 'React', 'hard_skill', NULL),
(54, 'Git', 'hard_skill', NULL),
(55, 'Systems Architecture', 'hard_skill', NULL),
(56, 'DevOps', 'hard_skill', NULL),
(57, 'Helpdesk Software', 'hard_skill', NULL),
(58, 'Ticketing Systems', 'hard_skill', NULL),
(59, 'Training Modules', 'hard_skill', NULL),
(60, 'E-Learning Tools', 'hard_skill', NULL),
(61, 'Product Roadmapping', 'hard_skill', NULL),
(62, 'Market Analysis', 'hard_skill', NULL),
(63, 'Marketing Automation', 'hard_skill', NULL),
(64, 'Branding', 'hard_skill', NULL),
(65, 'Data Entry', 'hard_skill', NULL),
(66, 'LMS Platforms', 'hard_skill', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','employee') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
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
  ADD KEY `FK_department_id` (`department_id`),
  ADD KEY `FK_position_id` (`position_id`);

--
-- Indexes for table `employee_salary`
--
ALTER TABLE `employee_salary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employee_salary_components`
--
ALTER TABLE `employee_salary_components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `component_id` (`component_id`);

--
-- Indexes for table `employee_skills`
--
ALTER TABLE `employee_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_employee_skill` (`employee_id`,`skill_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`position_id`),
  ADD KEY `job_positions_ibfk_1` (`department_id`);

--
-- Indexes for table `payroll_attendance`
--
ALTER TABLE `payroll_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `payroll_components`
--
ALTER TABLE `payroll_components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_jamsostek_config`
--
ALTER TABLE `payroll_jamsostek_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_records`
--
ALTER TABLE `payroll_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `period_id` (`period_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `payroll_record_details`
--
ALTER TABLE `payroll_record_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_record_id` (`payroll_record_id`),
  ADD KEY `component_id` (`component_id`);

--
-- Indexes for table `payroll_tax_config`
--
ALTER TABLE `payroll_tax_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tax_year_status` (`tax_year`,`tax_status`);

--
-- Indexes for table `recruitment`
--
ALTER TABLE `recruitment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `employee_salary`
--
ALTER TABLE `employee_salary`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_salary_components`
--
ALTER TABLE `employee_salary_components`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_skills`
--
ALTER TABLE `employee_skills`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `position_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payroll_attendance`
--
ALTER TABLE `payroll_attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_components`
--
ALTER TABLE `payroll_components`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payroll_jamsostek_config`
--
ALTER TABLE `payroll_jamsostek_config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_records`
--
ALTER TABLE `payroll_records`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_record_details`
--
ALTER TABLE `payroll_record_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_tax_config`
--
ALTER TABLE `payroll_tax_config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `recruitment`
--
ALTER TABLE `recruitment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

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
  ADD CONSTRAINT `FK_department_id` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_position_id` FOREIGN KEY (`position_id`) REFERENCES `job_positions` (`position_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `employee_salary`
--
ALTER TABLE `employee_salary`
  ADD CONSTRAINT `employee_salary_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_salary_components`
--
ALTER TABLE `employee_salary_components`
  ADD CONSTRAINT `employee_salary_components_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_salary_components_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `payroll_components` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_skills`
--
ALTER TABLE `employee_skills`
  ADD CONSTRAINT `employee_skills_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD CONSTRAINT `job_positions_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `payroll_attendance`
--
ALTER TABLE `payroll_attendance`
  ADD CONSTRAINT `payroll_attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_records`
--
ALTER TABLE `payroll_records`
  ADD CONSTRAINT `payroll_records_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `payroll_periods` (`id`),
  ADD CONSTRAINT `payroll_records_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_record_details`
--
ALTER TABLE `payroll_record_details`
  ADD CONSTRAINT `payroll_record_details_ibfk_1` FOREIGN KEY (`payroll_record_id`) REFERENCES `payroll_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payroll_record_details_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `payroll_components` (`id`) ON DELETE CASCADE;

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
