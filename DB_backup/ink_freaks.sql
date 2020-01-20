-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2019 at 12:07 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ink_freaks`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `article` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `article`, `date`) VALUES
(1, 2, 'dscsdc', 'sdcsdcsdc', '2019-10-28 14:16:20'),
(2, 2, 'ddddddd', 'ddddddddddddddddddddddd', '2019-10-28 14:16:31'),
(3, 2, 'ccc', 'This tutorial is going to show you how to SELECT data from a MySQL database, split it on multiple pages and display it using page numbers. Check our live demo.\r\n\r\nWe have MySQL table called \"students\" holding 100 records with the following fields:\r\nID: autoincrement ID\r\nName: varchar(250)\r\nPhoneNumber: varchar(250)\r\n\r\nInstead of doing a single SELECT query and display all the 100 records on a single page we can have 5 pages each containing maximum 20 records. To do this we will need to use the LIMIT clause for SELECT command so we can limit the query to show only 20 records. The LIMIT clause also allows you to specify which record to start from. For example this query', '2019-10-28 14:16:46'),
(4, 3, 'ddd', 'This tutorial is going to show you how to SELECT data from a MySQL database, split it on multiple pages and display it using page numbers. Check our live demo.\r\n\r\nWe have MySQL table called \"students\" holding 100 records with the following fields:\r\nID: autoincrement ID\r\nName: varchar(250)\r\nPhoneNumber: varchar(250)\r\n\r\nInstead of doing a single SELECT query and display all the 100 records on a single page we can have 5 pages each containing maximum 20 records. To do this we will need to use the LIMIT clause for SELECT command so we can limit the query to show only 20 records. The LIMIT clause also allows you to specify which record to start from. For example this query', '2019-10-28 14:17:09'),
(5, 3, 'dfdf', 'dfdfdd', '2019-10-28 14:17:18'),
(6, 3, 'bbbbb', 'bbbbbbb', '2019-10-28 14:17:24'),
(57, 2, '23c423c423', 'c423c423c4', '2019-10-28 16:24:34'),
(58, 2, '23c423c4', '23c423c4', '2019-10-28 16:24:38'),
(59, 2, 'dddd', '\r\nThis tutorial is going to show you how to SELECT data from a MySQL database, split it on multiple pages and display it using page numbers. Check our live demo.\r\n\r\nWe have MySQL table called &amp;#34;students&amp;#34; holding 100 records with the following fields:\r\nID: autoincrement ID\r\nName: varchar(250)\r\nPhoneNumber: varchar(250)\r\n\r\nInstead of doing a single SELECT query and display all the 100 records on a single page we can have 5 pages each containing maximum 20 records. To do this we will need to use the LIMIT clause for SELECT command so we can limit the query to show only 20 records. The LIMIT clause also allows you to specify which record to start from. For example this query', '2019-10-28 17:56:24'),
(61, 38, '......&amp;#39;&amp;#39;', '.....&amp;#39;&amp;#39;', '2019-10-29 10:14:35'),
(62, 39, 'HI', 'HI YOU', '2019-10-29 12:50:56'),
(63, 40, 'ssdfs', 'ssssssssss', '2019-10-29 13:31:04'),
(64, 38, 'fsdfs', '\'\'\'kl\'k\'', '2019-10-30 00:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`) VALUES
(1, 'Harry\'', 'Potter', 'harry@gmail.com', '$2y$10$EAzjwOZPMYx9aThOykTGjuxXRMdH8RKxClcJ/u5Zk6iRw1zmkvQ3C'),
(2, 'Ronald', 'Weasley', 'ron@gmail.com', '$2y$10$EAzjwOZPMYx9aThOykTGjuxXRMdH8RKxClcJ/u5Zk6iRw1zmkvQ3C'),
(3, 'Hermi', 'Granger', 'hermi@gmail.com', '$2y$10$EAzjwOZPMYx9aThOykTGjuxXRMdH8RKxClcJ/u5Zk6iRw1zmkvQ3C'),
(4, 'Drak\'\'\'o', 'Malfoy\'\'5', 'drako@gmail.com', '$2y$10$8/WJP5./77KYjrSa7YYGHuO3gxr9lRpFkO2C.Tuom.SrnRbiKKZq2'),
(35, 'Liran', 'Bla', 'liran@gmail.com', '$2y$10$8SuqOOedEtZojDaTLp0pk.L/F9CU7LkJ93YJ.kpRNTeYalWIalOJa'),
(36, 'Abdul', 'Ahmedov', 'asds@gmail.com', '$2y$10$Rj/u/Sl9M3uE62XTYJovTuIBKs.Og2RZuEiys1BJWWWsil.dVJuXW'),
(37, 'Dfgdfg', 'Dfgdfg', 'dfgdfg@sdfsf.fs', '$2y$10$xseP3EpfsZPS2ii3Yv3O/eRycgyn1VaCo84bGGu9fVajw8GI7Esiq'),
(38, 'Vika', 'London', 'vika@gmail.com', '$2y$10$.dkNVmTeR2Q2P9TIbuDpb.UOKVQYstSIc6s0JJcTUJQzkqYxvL0X6'),
(39, 'Yuval', 'Tayar', 'yuvsltsysr97@gmail.com', '$2y$10$mLMexiqjjUJpeHDDMjSDOezO6PzHH/hFhAZtSFQTmNohIQiM/09sK'),
(40, 'Der', 'Werwer', 'werwre2@efdf.co', '$2y$10$fY0HI7/PEY2hIKQuakwaAOXs9rjpWcE.yVTAgr33wtUkCfR/1WA9a'),
(41, 'Dfdf', 'Dfdfdf', 'dffdfd@sds.f', '$2y$10$8yj4e1TDe.VsiTUeLvypEudpqbGti9.qa5jl3fVNKwkjo37uODJEK');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_img` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `user_id`, `profile_img`, `city`, `country`) VALUES
(1, 1, '19.10.19.17.43.20-2019.10.10.13.33.56-dana.png', 'Hogwartz', 'Armenia'),
(5, 2, '17.10.19.14.09.37-car_1.jpg', 'bla', 'Argentina'),
(6, 3, '22.10.19.14.52.45-20.10.19.14.08.58-20170101_165740.jpg', 'Ashdod\'', 'Austria'),
(7, 4, '22.10.19.08.50.34-loginn.jpg', 'cote', ''),
(38, 35, 'profile_img.jpg', 'asds', 'Australia'),
(39, 36, '23.10.19.16.09.35-148960_158639880933420_1414386343_n.jpg', 'Ashdod', 'Afghanistan'),
(40, 37, 'profile_img.jpg', 'jesus', 'Qatar'),
(41, 38, '27.10.19.08.10.28-20.jpg', 'ashdod', 'Belarus'),
(42, 39, 'profile_img.jpg', '', ''),
(43, 40, 'profile_img.jpg', 'ashdod', 'Armenia'),
(44, 41, 'profile_img.jpg', 'dfdfdf', 'Albania');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
