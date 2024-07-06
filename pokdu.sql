-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2024 at 06:59 AM
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
-- Database: `pokdu`
--

-- --------------------------------------------------------

--
-- Table structure for table `confirmed_reservations`
--

CREATE TABLE `confirmed_reservations` (
  `confirmation_id` varchar(30) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `confirmation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edited_reservations`
--

CREATE TABLE `edited_reservations` (
  `edit_id` varchar(30) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `edit_description` text DEFAULT NULL,
  `edit_date` date DEFAULT NULL,
  `worker_id` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `reservation_id` int(11) NOT NULL,
  `pickup_point` varchar(30) DEFAULT NULL,
  `drop_point` varchar(30) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_time` time DEFAULT NULL,
  `seat_num` int(11) DEFAULT NULL,
  `num_of_people` int(11) DEFAULT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `worker_id` varchar(30) DEFAULT NULL,
  `confirmed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`reservation_id`, `pickup_point`, `drop_point`, `order_date`, `order_time`, `seat_num`, `num_of_people`, `student_id`, `worker_id`, `confirmed`) VALUES
(33, 'UiTM', 'pk', '2024-07-05', '10:57:00', NULL, 2, '2022489312', 'v01', 1),
(35, 'UiTM', 'Oldtown', '2024-07-05', '12:02:00', NULL, 1, '202245678', 'v01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `payment_id` varchar(30) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `payment_method` varchar(30) DEFAULT NULL,
  `payment_status` varchar(30) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`payment_id`, `reservation_id`, `payment_method`, `payment_status`, `amount`, `payment_date`) VALUES
('p668760d338d5f', 33, 'Cash', NULL, NULL, '2024-07-05'),
('p66876b11258cb', 35, 'Cash', NULL, NULL, '2024-07-05');

-- --------------------------------------------------------

--
-- Table structure for table `student_details`
--

CREATE TABLE `student_details` (
  `student_id` varchar(30) NOT NULL,
  `student_name` varchar(30) DEFAULT NULL,
  `student_password` varchar(30) DEFAULT NULL,
  `student_phoneNum` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_details`
--

INSERT INTO `student_details` (`student_id`, `student_name`, `student_password`, `student_phoneNum`) VALUES
('2022112233', 'John Doe', 'password123', '012-3456789'),
('2022123456', 'Laila ', '123456', '0135936411'),
('20222', 'merang', '1234', '011'),
('202245678', 'Amal', '123', '0111111'),
('2022489312', 'Ahmad Akmal', '123', '0135498722');

-- --------------------------------------------------------

--
-- Table structure for table `worker_details`
--

CREATE TABLE `worker_details` (
  `worker_id` varchar(30) NOT NULL,
  `worker_name` varchar(30) DEFAULT NULL,
  `worker_password` varchar(30) DEFAULT NULL,
  `worker_phoneNum` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worker_details`
--

INSERT INTO `worker_details` (`worker_id`, `worker_name`, `worker_password`, `worker_phoneNum`) VALUES
('v01', 'Pok Du', 'vankuning01', '019-9279053');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `confirmed_reservations`
--
ALTER TABLE `confirmed_reservations`
  ADD PRIMARY KEY (`confirmation_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `edited_reservations`
--
ALTER TABLE `edited_reservations`
  ADD PRIMARY KEY (`edit_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `student_details`
--
ALTER TABLE `student_details`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `worker_details`
--
ALTER TABLE `worker_details`
  ADD PRIMARY KEY (`worker_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `confirmed_reservations`
--
ALTER TABLE `confirmed_reservations`
  ADD CONSTRAINT `confirmed_reservations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `order_details` (`reservation_id`);

--
-- Constraints for table `edited_reservations`
--
ALTER TABLE `edited_reservations`
  ADD CONSTRAINT `edited_reservations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `order_details` (`reservation_id`),
  ADD CONSTRAINT `edited_reservations_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `worker_details` (`worker_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_details` (`student_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `worker_details` (`worker_id`);

--
-- Constraints for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD CONSTRAINT `payment_details_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `order_details` (`reservation_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
