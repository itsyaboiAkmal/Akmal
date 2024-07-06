-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2024 at 06:04 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

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
-- Table structure for table `student_details`
--

CREATE TABLE `student_details` (
  `student_id` varchar(30) NOT NULL,
  `student_name` varchar(30) DEFAULT NULL,
  `student_password` varchar(30) DEFAULT NULL,
  `student_phoneNum` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_details`
--

INSERT INTO `student_details` (`student_id`, `student_name`, `student_password`, `student_phoneNum`) VALUES
('2022112233', 'John Doe', 'password123', '012-3456789'),
('2022123467', 'Jane Smith', 'password456', '012-3456790');

-- --------------------------------------------------------

--
-- Table structure for table `worker_details`
--

CREATE TABLE `worker_details` (
  `worker_id` varchar(30) NOT NULL,
  `worker_name` varchar(30) DEFAULT NULL,
  `worker_password` varchar(30) DEFAULT NULL,
  `worker_phoneNum` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`worker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `worker_details`
--

INSERT INTO `worker_details` (`worker_id`, `worker_name`, `worker_password`, `worker_phoneNum`) VALUES
('v01', 'Pok Du', 'vankuning01', '019-9279053'),
('v02', 'Driver Two', 'driverpass2', '019-1234567');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
  `pickup_point` varchar(30) DEFAULT NULL,
  `drop_point` varchar(30) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_time` time DEFAULT NULL,
  `seat_num` int(11) DEFAULT NULL,
  `num_of_people` int(11) DEFAULT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `worker_id` varchar(30) DEFAULT NULL,
  `confirmed` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`reservation_id`),
  KEY `student_id` (`student_id`),
  KEY `worker_id` (`worker_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_details` (`student_id`),
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `worker_details` (`worker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`reservation_id`, `pickup_point`, `drop_point`, `order_date`, `order_time`, `seat_num`, `num_of_people`, `student_id`, `worker_id`, `confirmed`) VALUES
(1, 'UITM', 'Pokok Kelapa', '2024-05-01', '21:00:00', 4, 1, '2022112233', 'v01', 0),
(2, 'UITM', 'Pantai Timor', '2024-05-02', '06:00:00', 2, 2, '2022123467', 'v01', 1),
(3, 'Bandar Raub', 'UITM', '2024-05-03', '14:00:00', 3, 3, '2022112233', 'v02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `confirmed_reservations`
--

CREATE TABLE `confirmed_reservations` (
  `confirmation_id` varchar(30) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `confirmation_date` date DEFAULT NULL,
  PRIMARY KEY (`confirmation_id`),
  KEY `reservation_id` (`reservation_id`),
  CONSTRAINT `confirmed_reservations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `order_details` (`reservation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `confirmed_reservations`
--

INSERT INTO `confirmed_reservations` (`confirmation_id`, `reservation_id`, `confirmation_date`) VALUES
('c1', 1, '2024-04-28'),
('c2', 2, '2024-04-29');

-- --------------------------------------------------------

--
-- Table structure for table `edited_reservations`
--

CREATE TABLE `edited_reservations` (
  `edit_id` varchar(30) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `edit_description` text DEFAULT NULL,
  `edit_date` date DEFAULT NULL,
  `worker_id` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`edit_id`),
  KEY `reservation_id` (`reservation_id`),
  KEY `worker_id` (`worker_id`),
  CONSTRAINT `edited_reservations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `order_details` (`reservation_id`),
  CONSTRAINT `edited_reservations_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `worker_details` (`worker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `edited_reservations`
--

INSERT INTO `edited_reservations` (`edit_id`, `reservation_id`, `edit_description`, `edit_date`, `worker_id`) VALUES
('e1', 1, 'Changed pickup time to 22:00', '2024-04-27', 'v01'),
('e2', 2, 'Updated drop point to Pantai Timur', '2024-04-28', 'v01');

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
  `payment_date` date DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `reservation_id` (`reservation_id`),
  CONSTRAINT `payment_details_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `order_details` (`reservation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`payment_id`, `reservation_id`, `payment_method`, `payment_status`, `amount`, `payment_date`) VALUES
('p1', 1, 'Credit Card', 'Pending', '50.00', '2024-04-30'),
('p2', 2, 'PayPal', 'Completed', '100.00', '2024-04-25');

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

COMMIT;
