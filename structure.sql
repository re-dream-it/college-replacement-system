-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Апр 01 2025 г., 22:24
-- Версия сервера: 11.4.5-MariaDB-ubu2404
-- Версия PHP: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `redream_web_kmporep`
--

-- --------------------------------------------------------

--
-- Структура таблицы `departments`
--

CREATE TABLE `departments` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `disciplines`
--

CREATE TABLE `disciplines` (
  `id` smallint(6) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `employes`
--

CREATE TABLE `employes` (
  `id` smallint(6) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `surname` varchar(25) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `login` varchar(30) NOT NULL,
  `post` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` smallint(6) NOT NULL,
  `name` varchar(12) NOT NULL,
  `department_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `groups_disciplines`
--

CREATE TABLE `groups_disciplines` (
  `group_id` smallint(6) NOT NULL,
  `discipline_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `replacements`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `replacement_components`
--

CREATE TABLE `replacement_components` (
  `id` int(11) NOT NULL,
  `cabinet` varchar(20) DEFAULT NULL,
  `teacher_id` smallint(6) DEFAULT NULL,
  `slot_id` smallint(6) DEFAULT NULL,
  `discipline_id` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `replacement_types`
--

CREATE TABLE `replacement_types` (
  `type_id` smallint(6) NOT NULL,
  `replace_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE `rooms` (
  `number` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `slots`
--

CREATE TABLE `slots` (
  `id` smallint(6) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `id` smallint(6) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `surname` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--

CREATE TABLE `types` (
  `id` smallint(6) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `username` varchar(25) DEFAULT NULL,
  `group_id` smallint(6) DEFAULT NULL,
  `teacher_id` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `disciplines`
--
ALTER TABLE `disciplines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `code` (`code`) USING BTREE;

--
-- Индексы таблицы `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `department_id` (`department_id`);

--
-- Индексы таблицы `groups_disciplines`
--
ALTER TABLE `groups_disciplines`
  ADD KEY `discipline_id` (`discipline_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `replacements`
--
ALTER TABLE `replacements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `was_id` (`was_id`),
  ADD KEY `became_id` (`became_id`);

--
-- Индексы таблицы `replacement_components`
--
ALTER TABLE `replacement_components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slot_id` (`slot_id`),
  ADD KEY `discipline_id` (`discipline_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Индексы таблицы `replacement_types`
--
ALTER TABLE `replacement_types`
  ADD KEY `type_id` (`type_id`),
  ADD KEY `replace_id` (`replace_id`);

--
-- Индексы таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`number`);

--
-- Индексы таблицы `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `start_time` (`start_time`),
  ADD UNIQUE KEY `end_time` (`end_time`);

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `departments`
--
ALTER TABLE `departments`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `disciplines`
--
ALTER TABLE `disciplines`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `employes`
--
ALTER TABLE `employes`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `replacements`
--
ALTER TABLE `replacements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `replacement_components`
--
ALTER TABLE `replacement_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `types`
--
ALTER TABLE `types`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `group:department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `groups_disciplines`
--
ALTER TABLE `groups_disciplines`
  ADD CONSTRAINT `groups_disciplines_ibfk_1` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`),
  ADD CONSTRAINT `groups_disciplines_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);

--
-- Ограничения внешнего ключа таблицы `replacements`
--
ALTER TABLE `replacements`
  ADD CONSTRAINT `replace:became` FOREIGN KEY (`became_id`) REFERENCES `replacement_components` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `replace:employee` FOREIGN KEY (`author_id`) REFERENCES `employes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `replace:group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `replace:was` FOREIGN KEY (`was_id`) REFERENCES `replacement_components` (`id`) ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `replacement_components`
--
ALTER TABLE `replacement_components`
  ADD CONSTRAINT `component:discipline` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `component:slot` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `component:teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `replacement_types`
--
ALTER TABLE `replacement_types`
  ADD CONSTRAINT `replace` FOREIGN KEY (`replace_id`) REFERENCES `replacements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `type` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user:group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user:teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
