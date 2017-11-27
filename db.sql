-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2017 at 07:47 AM
-- Server version: 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apidallas`
--
CREATE DATABASE IF NOT EXISTS `apidallas` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `apidallas`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_keys`
--

CREATE TABLE `tbl_keys` (
  `id` int(11) NOT NULL,
  `UID` varchar(25) NOT NULL,
  `id_users` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_keys`
--

INSERT INTO `tbl_keys` (`id`, `UID`, `id_users`) VALUES
(1, '386478374', 1),
(2, '98123728232', 2),
(3, '468378473847', 1),
(4, '5162154544586', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `firstname`, `name`) VALUES
(1, 'Jean', 'Onche'),
(2, 'Michel', 'Dubois'),
(3, 'test', 'alan'),
(6, 'jean', 'francois'),
(7, 'Ben', 'Gringo'),
(8, 'Salut', 'Alan'),
(9, 'Salut', 'Alan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_keys`
--
ALTER TABLE `tbl_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_button_keys_id_users` (`id_users`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_keys`
--
ALTER TABLE `tbl_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_keys`
--
ALTER TABLE `tbl_keys`
  ADD CONSTRAINT `FK_button_keys_id_users` FOREIGN KEY (`id_users`) REFERENCES `tbl_users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
