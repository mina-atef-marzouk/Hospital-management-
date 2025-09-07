-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 09:55 PM
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
-- Database: `patient_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `Appointment_ID` int(11) NOT NULL,
  `patient_Name` varchar(100) NOT NULL,
  `patient_ID` int(11) NOT NULL,
  `Doctor_ID` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `duration` int(11) NOT NULL,
  `service_Reason` varchar(255) NOT NULL,
  `provider` varchar(100) NOT NULL,
  `status` enum('confirmed','pending','canceled','completed') NOT NULL,
  `contact_Info` int(11) NOT NULL,
  `notes` text NOT NULL,
  `created_Date` datetime NOT NULL,
  `Actions` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`Appointment_ID`, `patient_Name`, `patient_ID`, `Doctor_ID`, `date`, `time`, `duration`, `service_Reason`, `provider`, `status`, `contact_Info`, `notes`, `created_Date`, `Actions`) VALUES
(1, 'mariam', 1, 1, '2024-10-25', '14:30:00', 30, 'Routine Check-up', 'Dr. xyz', 'confirmed', 1038948471, 'Patient mentioned mild symptoms.', '2024-10-24 21:26:54', 'Follow-up needed in 1 week.'),
(3, 'mariam', 1, 1, '2024-10-25', '14:30:00', 30, 'Routine Check-up', 'Dr. xyz', 'canceled', 0, 'Patient mentioned mild symptoms.', '0000-00-00 00:00:00', 'Follow-up needed in 1 week.'),
(9, 'Ali', 2, 2, '2024-10-17', '14:00:00', 1, 'Consultation', 'Dr. Smith', 'confirmed', 555, 'Patient has a headache', '2024-10-18 20:25:17', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `Doctor_ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `clinic_name` varchar(255) DEFAULT NULL,
  `years_of_experience` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `biography` text DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`Doctor_ID`, `name`, `specialization`, `email`, `password`, `phone_number`, `address`, `clinic_name`, `years_of_experience`, `gender`, `license_number`, `availability`, `profile_picture`, `biography`, `created_date`, `last_updated`) VALUES
(1, 'Dr. John Doe', 'Cardiologist', 'john.doe@example.com', '$2y$10$MErPv/J1QejW0CXkC2RogexiiXPohDhEKpFRwbqlUrU0FmZ37CqpS', '555-1234', '123 Main St', 'Healthy Hearts Clinic', 10, 'Male', 'ABC123456', 'Mon-Fri 9 AM - 5 PM', 'path/to/picture.jpg', 'Experienced cardiologist with 10 years in the field.', '2024-10-17 16:46:42', '2024-10-17 16:46:42'),
(2, 'Dr. Ahmed', 'Neurologist', 'ahmed@gmail.com', '$2y$10$JZAesV1It4AzTfCtzuLduO1iMXSSsWQjgq3TzTPj0usjGUfwxCxXa', '0938747', 'Cairo St', 'Top Clinic', 20, 'Male', 'ABC123444', 'Mon-Fri 9 AM - 5 PM', 'path/to/picture.jpg', 'Experienced Neurologist with 20 years in the field.', '2024-10-17 17:23:55', '2024-10-18 19:16:54');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_ID` int(11) NOT NULL,
  `patient_Name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `health_goals` text NOT NULL,
  `gender` enum('Male','Female') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_ID`, `patient_Name`, `email`, `password`, `date_of_birth`, `height`, `weight`, `health_goals`, `gender`) VALUES
(1, 'maraim', 'mariam@gamil.com', '$2y$10$Da6Ld8gEQEdNoeBGHrWd0.ByyE09zfeBijDtHRXkvV0Hbospjl80G', '2000-01-01', 175.50, 70.00, 'Weight loss', 'Female'),
(2, 'Mohammad Ahmad', 'Mohammad.Ahmad@gmail.com', '$2y$10$hashedPasswordHere', '1990-01-01', 165.50, 60.00, 'be well', 'Male');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`Appointment_ID`),
  ADD KEY `patient_ID` (`patient_ID`),
  ADD KEY `fk_doctor_appointments` (`Doctor_ID`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`Doctor_ID`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `Appointment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `Doctor_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_ID`) REFERENCES `patients` (`patient_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_doctor_appointments` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctors` (`Doctor_ID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
