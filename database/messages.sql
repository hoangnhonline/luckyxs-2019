-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 13, 2019 at 04:32 PM
-- Server version: 10.2.12-MariaDB
-- PHP Version: 7.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lottery`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL,
  `tel_id` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `tel_id`, `content`, `created_at`, `updated_at`) VALUES
(1, '495662200', '06.60.da5 79-49-15-48 dv3', '2019-02-13 09:31:48', '2019-02-13 09:31:48'),
(2, '495662200', 'T6.hn 77;88;99 đa vòng 15n', '2019-02-13 09:31:51', '2019-02-13 09:31:51'),
(3, '495662200', '735Daob40n 735x30', '2019-02-13 09:31:54', '2019-02-13 09:31:54'),
(4, '495662200', 'Hn 312 x300 812 128 x50 2032 b30 911 x170 952 x400 938 x250 739 x200 368 x80', '2019-02-13 09:31:56', '2019-02-13 09:31:56'),
(5, '495662200', '1266b35 36.63da20n', '2019-02-13 09:31:57', '2019-02-13 09:31:57'),
(6, '495662200', 'Đá 77 82 30n', '2019-02-13 09:31:57', '2019-02-13 09:31:57'),
(7, '495662200', 'T6.hn 77;88;99 đa vòng 15n', '2019-02-13 09:31:58', '2019-02-13 09:31:58'),
(8, '495662200', '06.60.da5 79-49-15-48 dv3', '2019-02-13 09:31:59', '2019-02-13 09:31:59'),
(9, '495662200', '583daob5  35 29 77da7', '2019-02-13 09:32:00', '2019-02-13 09:32:00'),
(10, '495662200', '528b30 28b400', '2019-02-13 09:32:00', '2019-02-13 09:32:00'),
(11, '495662200', 'Hn. 688.dx20 54.88.da20', '2019-02-13 09:32:01', '2019-02-13 09:32:01'),
(12, '495662200', 'Hn 312 x300 812 128 x50 2032 b30 911 x170 952 x400 938 x250 739 x200 368 x80', '2019-02-13 09:32:01', '2019-02-13 09:32:01'),
(13, '495662200', 'Đá 77 82 30n', '2019-02-13 09:32:02', '2019-02-13 09:32:02'),
(14, '495662200', '583daob5  35 29 77da7', '2019-02-13 09:32:05', '2019-02-13 09:32:05'),
(15, '495662200', '19.59.79da5  66.92.82.dd50 06.đuôi 300', '2019-02-13 09:32:06', '2019-02-13 09:32:06'),
(16, '495662200', 'Hn 375 x450 06 duoi 150', '2019-02-13 09:32:06', '2019-02-13 09:32:06'),
(17, '495662200', 'Hn 312 x300 812 128 x50 2032 b30 911 x170 952 x400 938 x250 739 x200 368 x80', '2019-02-13 09:32:08', '2019-02-13 09:32:08'),
(18, '495662200', 'Đá 77 82 30n', '2019-02-13 09:32:09', '2019-02-13 09:32:09'),
(19, '495662200', 'T6.hn 77;88;99 đa vòng 15n', '2019-02-13 09:32:09', '2019-02-13 09:32:09'),
(20, '495662200', 'Hn 375 x450 06 duoi 150', '2019-02-13 09:32:12', '2019-02-13 09:32:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
