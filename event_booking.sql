-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 17, 2026 at 07:44 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `adminID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`adminID`, `userID`, `position`, `department`) VALUES
(6, 21, 'Dean', 'College of Computer Studies'),
(7, 23, 'IT Director', 'CCS'),
(10, 34, 'Dean', 'College of Computer Studies'),
(11, 35, 'Chairperson', 'Information Technology'),
(12, 36, 'Coordinator', 'Computer Science'),
(14, 38, 'Professor', 'College of Computer Studies'),
(15, 39, 'Chairperson', 'Computer Engineering'),
(16, 40, 'Registrar', 'Academic Affairs'),
(17, 41, 'Coordinator', 'Information Systems'),
(18, 42, 'Instructor', 'College of Computer Studies'),
(19, 43, 'Secretary', 'College of Computer Studies'),
(20, 44, 'Instructor', 'Information'),
(21, 45, 'Professor', 'Computer Science'),
(22, 46, 'Coordinator', 'Multimedia Arts'),
(23, 47, 'Director', 'Human Resources'),
(24, 48, 'Professor', 'College of Engineering'),
(25, 49, 'Legal Counsel', 'Administration Affairs'),
(26, 50, 'Assistant Dean', 'College of Computer Studies'),
(27, 51, 'Coordinator', 'Cultural Affairs'),
(28, 52, 'Director', 'Student Affairs'),
(29, 53, 'Secretary', 'Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE `Events` (
  `eventID` int(11) NOT NULL,
  `adminID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `eventDate` datetime DEFAULT NULL,
  `maxCapacity` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Events`
--

INSERT INTO `Events` (`eventID`, `adminID`, `name`, `eventDate`, `maxCapacity`, `status`) VALUES
(9, 6, 'Intramurals', '2026-05-14 15:40:00', 100, 'active'),
(11, 6, 'Pasigarbo', '2026-05-15 11:08:00', 50, 'active'),
(12, 6, 'Cebu Tech Summit 2026', '2026-06-10 09:00:00', 150, 'active'),
(13, 6, 'Hackathon Kickoff Session', '2026-06-15 13:00:00', 100, 'active'),
(14, 6, 'Database Management Seminar', '2026-06-22 10:30:00', 80, 'active'),
(15, 6, 'Web Development Boot Camp', '2026-07-01 08:00:00', 120, 'active'),
(16, 6, 'UI/UX Design Thinking Workshop', '2026-07-05 14:00:00', 60, 'active'),
(17, 6, 'PHP & MySQL Deep Dive', '2026-07-12 09:30:00', 90, 'active'),
(18, 6, 'College of Computer Studies Assembly', '2026-07-18 11:00:00', 300, 'active'),
(19, 6, 'Mobile App Innovation Contest', '2026-07-25 13:00:00', 50, 'active'),
(20, 6, 'AI & Grassroots Disaster Dashboard Seminar', '2026-08-02 10:00:00', 110, 'active'),
(21, 6, 'Cybersecurity Awareness Forum', '2026-08-08 15:00:00', 200, 'active'),
(22, 6, 'Cloud Computing Essentials', '2026-08-14 09:00:00', 75, 'active'),
(23, 6, 'CIT-U Tech Career Fair', '2026-08-20 08:30:00', 500, 'active'),
(24, 6, 'Object-Oriented Programming Review', '2026-08-28 14:30:00', 45, 'active'),
(25, 6, 'Data Structures & Algorithms Prep', '2026-09-03 10:00:00', 65, 'active'),
(26, 6, 'Git & GitHub Workspace Tutorial', '2026-09-10 11:15:00', 130, 'active'),
(27, 6, 'Old Event: Web Dev Basics 2025', '2025-11-12 09:00:00', 100, 'active'),
(28, 6, 'Canceled Seminar: Legacy Systems', '2026-01-15 14:00:00', 40, 'inactive'),
(29, 6, 'Archived: Smart Waste Management Mockup', '2026-02-20 10:30:00', 50, 'inactive'),
(30, 6, 'Completed Workshop: Basic HTML Layouts', '2026-03-05 08:00:00', 85, 'inactive'),
(31, 6, 'Postponed Event: Software Engineering 101', '2026-04-18 13:00:00', 120, 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `Reservation`
--

CREATE TABLE `Reservation` (
  `reservationID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `seatID` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending' CHECK (`status` in ('pending','confirmed','cancelled'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Seat`
--

CREATE TABLE `Seat` (
  `seatID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `seatNumber` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT 'available' CHECK (`status` in ('available','reserved')),
  `isPriority` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Seat`
--

INSERT INTO `Seat` (`seatID`, `eventID`, `seatNumber`, `status`, `isPriority`) VALUES
(23, 9, 'A1', 'available', 1),
(24, 9, 'A2', 'reserved', 0),
(25, 9, 'A3', 'available', 0),
(26, 9, 'A4', 'available', 0),
(27, 9, 'A5', 'available', 0),
(28, 12, 'A1', 'available', 1),
(29, 12, 'A2', 'available', 0),
(30, 12, 'A3', 'available', 0),
(31, 12, 'A4', 'available', 0),
(32, 12, 'A5', 'available', 0),
(33, 13, 'A1', 'available', 1),
(34, 13, 'A2', 'available', 0),
(35, 13, 'A3', 'available', 0),
(36, 13, 'A4', 'available', 0),
(37, 13, 'A5', 'available', 0),
(38, 14, 'A1', 'available', 1),
(39, 14, 'A2', 'available', 0),
(40, 14, 'A3', 'available', 0),
(41, 14, 'A4', 'available', 0),
(42, 14, 'A5', 'available', 0),
(43, 15, 'A1', 'available', 1),
(44, 15, 'A2', 'available', 0),
(45, 15, 'A3', 'available', 0),
(46, 15, 'A4', 'available', 0),
(47, 15, 'A5', 'available', 0),
(48, 16, 'A1', 'available', 1),
(49, 16, 'A2', 'available', 0),
(50, 16, 'A3', 'available', 0),
(51, 16, 'A4', 'available', 0),
(52, 16, 'A5', 'available', 0),
(53, 17, 'A1', 'available', 1),
(54, 17, 'A2', 'available', 0),
(55, 17, 'A3', 'available', 0),
(56, 17, 'A4', 'available', 0),
(57, 17, 'A5', 'available', 0),
(58, 18, 'A1', 'available', 1),
(59, 18, 'A2', 'available', 0),
(60, 18, 'A3', 'available', 0),
(61, 18, 'A4', 'available', 0),
(62, 18, 'A5', 'available', 0),
(63, 19, 'A1', 'available', 1),
(64, 19, 'A2', 'available', 0),
(65, 19, 'A3', 'available', 0),
(66, 19, 'A4', 'available', 0),
(67, 19, 'A5', 'available', 0),
(68, 20, 'A1', 'available', 1),
(69, 20, 'A2', 'available', 0),
(70, 20, 'A3', 'available', 0),
(71, 20, 'A4', 'available', 0),
(72, 20, 'A5', 'available', 0),
(73, 11, 'A1', 'available', 1),
(74, 11, 'A2', 'available', 0),
(75, 11, 'A3', 'available', 0),
(76, 11, 'A4', 'available', 0),
(77, 11, 'A5', 'available', 0),
(78, 21, 'A1', 'available', 1),
(79, 21, 'A2', 'available', 0),
(80, 21, 'A3', 'available', 0),
(81, 21, 'A4', 'available', 0),
(82, 21, 'A5', 'available', 0),
(83, 22, 'A1', 'available', 1),
(84, 22, 'A2', 'available', 0),
(85, 22, 'A3', 'available', 0),
(86, 22, 'A4', 'available', 0),
(87, 22, 'A5', 'available', 0),
(88, 23, 'A1', 'available', 1),
(89, 23, 'A2', 'available', 0),
(90, 23, 'A3', 'available', 0),
(91, 23, 'A4', 'available', 0),
(92, 23, 'A5', 'available', 0),
(93, 24, 'A1', 'available', 1),
(94, 24, 'A2', 'available', 0),
(95, 24, 'A3', 'available', 0),
(96, 24, 'A4', 'available', 0),
(97, 24, 'A5', 'available', 0),
(98, 25, 'A1', 'available', 1),
(99, 25, 'A2', 'available', 0),
(100, 25, 'A3', 'available', 0),
(101, 25, 'A4', 'available', 0),
(102, 25, 'A5', 'available', 0),
(103, 26, 'A1', 'available', 1),
(104, 26, 'A2', 'available', 0),
(105, 26, 'A3', 'available', 0),
(106, 26, 'A4', 'available', 0),
(107, 26, 'A5', 'available', 0),
(108, 27, 'A1', 'available', 1),
(109, 27, 'A2', 'available', 0),
(110, 27, 'A3', 'available', 0),
(111, 27, 'A4', 'available', 0),
(112, 27, 'A5', 'available', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Student`
--

CREATE TABLE `Student` (
  `studentID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `program` varchar(100) DEFAULT NULL,
  `yearLevel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Student`
--

INSERT INTO `Student` (`studentID`, `userID`, `program`, `yearLevel`) VALUES
(4, 6, 'BSIT', 2),
(6, 8, 'BSIT', 2),
(8, 12, 'BSIT', 2),
(9, 14, 'BSIT', 2),
(10, 16, 'BSIT', 2),
(12, 22, 'BSIT', 2),
(13, 26, 'BSIT', 2),
(14, 28, 'BSIT', 2),
(15, 30, 'BSIT', 4),
(16, 31, 'BSIT', 1),
(17, 33, 'BSIT', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `userID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT NULL CHECK (`role` in ('student','admin'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `name`, `email`, `password`, `role`) VALUES
(6, 'alexa', 'alexa@gmail.com', '$2y$10$f.85NX6z2tovFyk5719/AufdvO4qbjry1jDdd4HfW1cSxvOA8m.Xa', 'student'),
(8, 'Alexandra Mauring', 'alexamau123@gmail.com', '$2y$10$1B/E3vW5t13Z6exM3a.iTexAV0bKmoCL0Ayx74vVcJUd1dK/KYVwm', 'student'),
(12, 'Alexa', 'alexaaa@gmail.com', '$2y$10$VlgACvvaP4GhGV1aBVY.8.WwjcCY2qbkRR9cCRuaQhuWYPu9DEp.i', 'student'),
(14, 'MG', 'mg@gmail.com', '$2y$10$vrr/Ab1nfjh/FEfOsUQAp.pm9xNk7DX9HU36NAsO46DG7Jmpbknra', 'student'),
(16, 'MG', 'mg123@gmail.com', '$2y$10$B8UFFZfxzp/V4EbsbNWCCesLhRe0JyaRuIO45Wd38u1MQExOhdjdK', 'student'),
(21, 'Super Admin', 'admin@cituniversity.edu', '$2y$10$vEg5ck6WK1hVzi3j75ZmBeQGS/Lwq2GXOTKaUKOEiRdmZ5fvbZygW', 'admin'),
(22, 'keith', 'keith@gmail.com', '$2y$10$RqUEMJiT7hb1ATQipdGTau8PbcwSGE02UV5q3e/.8twElzHi88LTa', 'student'),
(23, 'MG', 'adminMG@gmail.com', '$2y$10$K0QJeZqORmgeWGLlpe3VY.5c6STnTzgZm2INhiF1xhCCGIFiV8Cpq', 'admin'),
(26, 'mau', 'mauuuuu@gmail.com', '$2y$10$t8xSCIhRhODSZKMil3.D/./WsKJXxdZ6uxElqWyCTxnVCOnOXsnzK', 'student'),
(28, 'mau', 'maui@gmail.com', '$2y$10$rPTCNbooSnwhw1YmCmgoU.dRKIxZEyJqb.6rMRkRhI7te9tjLHEQ6', 'student'),
(30, 'mau', 'hello@gmail.com', '$2y$10$vHrCoWmSPN80HRsdeOd1OOzmbMtfQtLNe1PlPbc45LDEFYCn8PMYq', 'student'),
(31, 'mau', 'hello@cit.edu', '$2y$10$yZgcfaL5LIWdMTdCKYwr3uiCpUpR4ejPfACPdLG0nDyFqauwrCSrW', 'student'),
(33, 'mau', 'helloooo@gmail.com', '$2y$10$qfvnkPdFOgfU3lJwXXqLzudt0KYFMgAFqi4.nQ/mATLaITjgQpssW', 'student'),
(34, 'Juan Dela Cruz', 'juan@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(35, 'Maria Santos', 'maria@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(36, 'Pedro Penduko', 'pedro@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(38, 'Jose Rizal', 'jose@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(39, 'Elena Garcia', 'elena@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(40, 'Manuel Quezon', 'manuel@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(41, 'Grace Poe', 'grace@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(42, 'Antonio Luna', 'antonio@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(43, 'Carmen Rosales', 'carmen@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(44, 'Andres Bonifacio', 'andres@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(45, 'Corazon Aquino', 'corazon@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(46, 'Emilio Aguinaldo', 'emilio@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(47, 'Imelda Marcos', 'imelda@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(48, 'Apolinario Mabini', 'apolinario@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(49, 'Miriam Santiago', 'miriam@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(50, 'Ferdinand Castro', 'ferdinand@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(51, 'Lea Salonga', 'lea@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(52, 'Ramon Magsaysay', 'ramon@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(53, 'Sarah Geronimo', 'sarah@cituniversity.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`adminID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `Events`
--
ALTER TABLE `Events`
  ADD PRIMARY KEY (`eventID`),
  ADD KEY `adminID` (`adminID`);

--
-- Indexes for table `Reservation`
--
ALTER TABLE `Reservation`
  ADD PRIMARY KEY (`reservationID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `seatID` (`seatID`);

--
-- Indexes for table `Seat`
--
ALTER TABLE `Seat`
  ADD PRIMARY KEY (`seatID`),
  ADD KEY `eventID` (`eventID`);

--
-- Indexes for table `Student`
--
ALTER TABLE `Student`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `Events`
--
ALTER TABLE `Events`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `Reservation`
--
ALTER TABLE `Reservation`
  MODIFY `reservationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Seat`
--
ALTER TABLE `Seat`
  MODIFY `seatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `Student`
--
ALTER TABLE `Student`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Admin`
--
ALTER TABLE `Admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `Events`
--
ALTER TABLE `Events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`adminID`) REFERENCES `Admin` (`adminID`) ON DELETE CASCADE;

--
-- Constraints for table `Reservation`
--
ALTER TABLE `Reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`seatID`) REFERENCES `Seat` (`seatID`) ON DELETE CASCADE;

--
-- Constraints for table `Seat`
--
ALTER TABLE `Seat`
  ADD CONSTRAINT `seat_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `Events` (`eventID`) ON DELETE CASCADE;

--
-- Constraints for table `Student`
--
ALTER TABLE `Student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
