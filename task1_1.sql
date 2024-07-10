-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2024 at 08:21 PM
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
-- Database: `yiitask1_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE `like` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blog_post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`id`, `user_id`, `blog_post_id`) VALUES
(32, 4, 3),
(37, 2, 4),
(39, 2, 12),
(40, 2, 11),
(41, 2, 3),
(42, 4, 14);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `content`, `is_public`, `created_at`, `updated_at`) VALUES
(3, 5, 'testing', 'password', 1, '2024-07-10 16:11:41', '2024-07-10 16:11:41'),
(4, 5, 'dfasldfk', 'kjdslkjfkdsalkjdf', 0, '2024-07-10 16:12:03', '2024-07-10 16:12:03'),
(11, 2, 'tesd', 'tesradssaf', 1, '2024-07-10 22:15:37', '2024-07-10 22:15:37'),
(12, 2, 'nenwenen', 'dhskfdjasljdfhkj', 1, '2024-07-10 22:15:53', '2024-07-10 22:15:53'),
(14, 2, 'kkfjldkjksdaf', 'test', 1, '2024-07-10 22:17:41', '2024-07-10 22:18:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_migration`
--

CREATE TABLE `tbl_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_migration`
--

INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1720545981),
('m240709_141821_posts_table', 1720545985);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '0',
  `verify_token` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `status`, `verify_token`) VALUES
(1, 'user', 'admin@admin.com', 'password', '1', NULL),
(2, 'testo', 'admin@admin2.com', '5f4dcc3b5aa765d61d8327deb882cf99', '1', NULL),
(3, 'user2', 'user2@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', '0', NULL),
(4, 'mhdferas97', 'mhd.feras97@gmail.com', 'df5ac9904854cf961eb45d3e564cbf2a', 'active', NULL),
(5, 'nio', 'nio@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', '1', NULL),
(6, 'testo1', 'kljlkj@g.co', '5f4dcc3b5aa765d61d8327deb882cf99', '0', NULL),
(7, 'mhdferaso', 'mhd.feras97@gmail.comm', 'df5ac9904854cf961eb45d3e564cbf2a', '0', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `blog_post_id` (`blog_post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_blog_post_user` (`user_id`);

--
-- Indexes for table `tbl_migration`
--
ALTER TABLE `tbl_migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `like`
--
ALTER TABLE `like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `fk_like_blog_post` FOREIGN KEY (`blog_post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_like_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_blog_post_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
