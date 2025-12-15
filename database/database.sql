-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 11:06 AM
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
-- Database: `lab_project_group_8`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `designation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_permissions`
--

CREATE TABLE `admin_permissions` (
  `admin_id` int(11) NOT NULL,
  `permission` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `therapist_name` varchar(100) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `therapist_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `date`, `time`, `status`, `patient_name`, `therapist_name`, `patient_id`, `therapist_id`) VALUES
(1, '2025-12-16', '03:35:00', 'Pending', 'Bruce', 'Tailor', 2, 1),
(2, '2025-12-18', '14:41:00', 'Pending', 'Bruce', 'Tailor', 2, 1),
(3, '2025-12-16', '02:35:00', 'Pending', 'Upoma', 'Tailor', 4, 3),
(4, '2025-12-19', '05:50:00', 'Pending', 'Upoma', 'Tailor', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `emergency_service_request`
--

CREATE TABLE `emergency_service_request` (
  `patient_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `time` time NOT NULL,
  `description` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `patient_id` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `therapist_name` varchar(100) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`) VALUES
(2),
(4);

-- --------------------------------------------------------

--
-- Table structure for table `progress_report`
--

CREATE TABLE `progress_report` (
  `patient_name` varchar(100) DEFAULT NULL,
  `improvement_score` int(11) DEFAULT NULL,
  `severity` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `summary` text DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `therapist_id` int(11) DEFAULT NULL,
  `appointment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `self_assessment_test`
--

CREATE TABLE `self_assessment_test` (
  `name` varchar(100) DEFAULT NULL,
  `test_time` time DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `severity` varchar(50) DEFAULT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE `specialization` (
  `therapist_id` int(11) NOT NULL,
  `specialization` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `therapist`
--

CREATE TABLE `therapist` (
  `therapist_id` int(11) NOT NULL,
  `license_no` varchar(50) DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `availability_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `therapist`
--

INSERT INTO `therapist` (`therapist_id`, `license_no`, `consultation_fee`, `availability_status`) VALUES
(1, 'LIC-17197', 1500.00, 'Available'),
(3, 'LIC-52730', 1500.00, 'Available'),
(5, 'LIC-10096', 1500.00, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `therapist_degree`
--

CREATE TABLE `therapist_degree` (
  `therapist_id` int(11) NOT NULL,
  `degree` varchar(100) NOT NULL,
  `degree_year` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `date_time` date DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `phone`, `gender`, `date_time`, `street`, `city`, `zipcode`) VALUES
(1, 'Tailor', 'tailor@gmail.com', 'tailor123', '01227456812', 'Male', NULL, '120/A Dong Road', 'Huston', '1391'),
(2, 'Bruce', 'bruce@gmail.com', 'bruce123', '01435613513', 'Male', NULL, '24/A Down Town, Brooklyn', 'New York', '15321'),
(3, 'Tailor', 'tailor@gmail.com', 'tailor123', '01227456812', 'Male', NULL, '120/A Dong Road', 'Huston', '1391'),
(4, 'Upoma', 'anondo@gmail.com', 'upoma123', '01735292031', 'Male', NULL, '39/B Bingbong, Shutter Island', 'NewYork', '12352'),
(5, 'Bakku', 'bakku@gmail.com', 'bakku123', '01853212319', 'Female', NULL, '89/C Ring Road', 'Dhaka', '1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`admin_id`,`permission`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Indexes for table `emergency_service_request`
--
ALTER TABLE `emergency_service_request`
  ADD PRIMARY KEY (`patient_id`,`time`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`patient_id`,`therapist_id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress_report`
--
ALTER TABLE `progress_report`
  ADD PRIMARY KEY (`patient_id`,`date`),
  ADD KEY `therapist_id` (`therapist_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `self_assessment_test`
--
ALTER TABLE `self_assessment_test`
  ADD PRIMARY KEY (`patient_id`,`date`);

--
-- Indexes for table `specialization`
--
ALTER TABLE `specialization`
  ADD PRIMARY KEY (`therapist_id`,`specialization`);

--
-- Indexes for table `therapist`
--
ALTER TABLE `therapist`
  ADD PRIMARY KEY (`therapist_id`);

--
-- Indexes for table `therapist_degree`
--
ALTER TABLE `therapist_degree`
  ADD PRIMARY KEY (`therapist_id`,`degree`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD CONSTRAINT `admin_permissions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`therapist_id`) REFERENCES `therapist` (`therapist_id`);

--
-- Constraints for table `emergency_service_request`
--
ALTER TABLE `emergency_service_request`
  ADD CONSTRAINT `emergency_service_request_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `emergency_service_request_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`therapist_id`) REFERENCES `therapist` (`therapist_id`);

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `progress_report`
--
ALTER TABLE `progress_report`
  ADD CONSTRAINT `progress_report_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `progress_report_ibfk_2` FOREIGN KEY (`therapist_id`) REFERENCES `therapist` (`therapist_id`),
  ADD CONSTRAINT `progress_report_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`appointment_id`);

--
-- Constraints for table `self_assessment_test`
--
ALTER TABLE `self_assessment_test`
  ADD CONSTRAINT `self_assessment_test_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `specialization`
--
ALTER TABLE `specialization`
  ADD CONSTRAINT `specialization_ibfk_1` FOREIGN KEY (`therapist_id`) REFERENCES `therapist` (`therapist_id`) ON DELETE CASCADE;

--
-- Constraints for table `therapist`
--
ALTER TABLE `therapist`
  ADD CONSTRAINT `therapist_ibfk_1` FOREIGN KEY (`therapist_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `therapist_degree`
--
ALTER TABLE `therapist_degree`
  ADD CONSTRAINT `therapist_degree_ibfk_1` FOREIGN KEY (`therapist_id`) REFERENCES `therapist` (`therapist_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
