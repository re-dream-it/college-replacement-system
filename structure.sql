-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 13, 2025 at 07:15 PM
-- Server version: 10.6.21-MariaDB-0ubuntu0.22.04.2
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kmpo_replacements`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disciplines`
--

CREATE TABLE `disciplines` (
  `id` smallint(6) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employes`
--

CREATE TABLE `employes` (
  `id` smallint(6) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `surname` varchar(25) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `login` varchar(30) NOT NULL,
  `post` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` smallint(6) NOT NULL,
  `name` varchar(12) NOT NULL,
  `department_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups_disciplines`
--

CREATE TABLE `groups_disciplines` (
  `group_id` smallint(6) NOT NULL,
  `discipline_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `replacements`
--

CREATE TABLE `replacements` (
  `id` int(11) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  `author_id` smallint(6) NOT NULL,
  `group_id` smallint(6) NOT NULL,
  `group_part` varchar(12) DEFAULT NULL,
  `was_id` int(11) DEFAULT NULL,
  `became_id` int(11) DEFAULT NULL,
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `replacement_components`
--

CREATE TABLE `replacement_components` (
  `id` int(11) NOT NULL,
  `cabinet` varchar(20) DEFAULT NULL,
  `teacher_id` smallint(6) DEFAULT NULL,
  `slot_id` smallint(6) DEFAULT NULL,
  `discipline_id` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `replacement_types`
--

CREATE TABLE `replacement_types` (
  `type_id` smallint(6) NOT NULL,
  `replace_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` smallint(6) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` smallint(6) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `surname` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` smallint(6) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `username` varchar(25) DEFAULT NULL,
  `group_id` smallint(6) DEFAULT NULL,
  `teacher_id` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `disciplines`
--
ALTER TABLE `disciplines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `code` (`code`) USING BTREE;

--
-- Indexes for table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `groups_disciplines`
--
ALTER TABLE `groups_disciplines`
  ADD KEY `discipline_id` (`discipline_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `replacements`
--
ALTER TABLE `replacements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `was_id` (`was_id`),
  ADD KEY `became_id` (`became_id`);

--
-- Indexes for table `replacement_components`
--
ALTER TABLE `replacement_components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slot_id` (`slot_id`),
  ADD KEY `discipline_id` (`discipline_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `replacement_types`
--
ALTER TABLE `replacement_types`
  ADD KEY `type_id` (`type_id`),
  ADD KEY `replace_id` (`replace_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `start_time` (`start_time`),
  ADD UNIQUE KEY `end_time` (`end_time`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disciplines`
--
ALTER TABLE `disciplines`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employes`
--
ALTER TABLE `employes`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `replacements`
--
ALTER TABLE `replacements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `replacement_components`
--
ALTER TABLE `replacement_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `group:department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `groups_disciplines`
--
ALTER TABLE `groups_disciplines`
  ADD CONSTRAINT `groups_disciplines_ibfk_1` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`),
  ADD CONSTRAINT `groups_disciplines_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);

--
-- Constraints for table `replacements`
--
ALTER TABLE `replacements`
  ADD CONSTRAINT `replace:became` FOREIGN KEY (`became_id`) REFERENCES `replacement_components` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `replace:employee` FOREIGN KEY (`author_id`) REFERENCES `employes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `replace:group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `replace:was` FOREIGN KEY (`was_id`) REFERENCES `replacement_components` (`id`) ON UPDATE NO ACTION;

--
-- Constraints for table `replacement_components`
--
ALTER TABLE `replacement_components`
  ADD CONSTRAINT `component:discipline` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `component:slot` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `component:teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `replacement_types`
--
ALTER TABLE `replacement_types`
  ADD CONSTRAINT `replace` FOREIGN KEY (`replace_id`) REFERENCES `replacements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `type` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user:group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user:teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
