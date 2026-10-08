-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.0:3306
-- Время создания: Окт 08 2026 г., 20:12
-- Версия сервера: 8.0.45
-- Версия PHP: 8.5.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `chat_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `to_user_id` int UNSIGNED NOT NULL,
  `room_id` int UNSIGNED NOT NULL,
  `text` text,
  `image_paths` json DEFAULT NULL,
  `file_paths` json DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `to_user_id`, `room_id`, `text`, `image_paths`, `file_paths`, `is_read`, `created_at`) VALUES
(1, 1, 11, 1, NULL, NULL, '[{\"mime\": \"image/png\", \"name\": \"Снимок экрана 2026-03-05 144918.png\", \"path\": \"/uploads/images/a16f4140558cc0df.png\", \"size\": 17433, \"type\": \"images\", \"safe_name\": \"a16f4140558cc0df.png\"}]', 0, '2026-10-08 19:20:09'),
(2, 1, 11, 1, NULL, NULL, '[{\"mime\": \"video/mp4\", \"name\": \"sample-5s-720p.mp4\", \"path\": \"/uploads/videos/0fff7c3dafd3c57d.mp4\", \"size\": 2572910, \"type\": \"videos\", \"safe_name\": \"0fff7c3dafd3c57d.mp4\"}]', 0, '2026-10-08 19:24:38'),
(3, 1, 11, 1, NULL, NULL, '[{\"mime\": \"video/mp4\", \"name\": \"sample-5s-720p.mp4\", \"path\": \"/uploads/videos/b3c66cefbaf7fa07.mp4\", \"size\": 2572910, \"type\": \"videos\", \"safe_name\": \"b3c66cefbaf7fa07.mp4\"}, {\"mime\": \"video/mp4\", \"name\": \"sample-10s.mp4\", \"path\": \"/uploads/videos/b9dc918bf9918b9c.mp4\", \"size\": 5485935, \"type\": \"videos\", \"safe_name\": \"b9dc918bf9918b9c.mp4\"}, {\"mime\": \"video/mp4\", \"name\": \"sample-5s.mp4\", \"path\": \"/uploads/videos/c57368fad7718509.mp4\", \"size\": 2848208, \"type\": \"videos\", \"safe_name\": \"c57368fad7718509.mp4\"}, {\"mime\": \"video/mp4\", \"name\": \"sample-5s-360p.mp4\", \"path\": \"/uploads/videos/1b056d07147139ed.mp4\", \"size\": 1137884, \"type\": \"videos\", \"safe_name\": \"1b056d07147139ed.mp4\"}]', 0, '2026-10-08 19:25:13'),
(4, 1, 11, 1, NULL, NULL, '[{\"mime\": \"image/png\", \"name\": \"f3748da234cc9fa8cf9cc27444ae40ceb090ed0e.png\", \"path\": \"/uploads/images/ac2b68ed26885f1f.png\", \"size\": 5352164, \"type\": \"images\", \"safe_name\": \"ac2b68ed26885f1f.png\"}]', 0, '2026-10-08 19:27:55'),
(5, 1, 11, 1, 'iohuig', NULL, NULL, 0, '2026-10-08 19:31:19'),
(6, 1, 11, 1, NULL, NULL, '[{\"mime\": \"video/mp4\", \"name\": \"sample-10s.mp4\", \"path\": \"/uploads/videos/abdeb0f19017679d.mp4\", \"size\": 5485935, \"type\": \"videos\", \"safe_name\": \"abdeb0f19017679d.mp4\"}]', 0, '2026-10-08 19:56:05'),
(7, 1, 11, 1, NULL, NULL, '[{\"mime\": \"video/mp4\", \"name\": \"sample-5s.mp4\", \"path\": \"/uploads/videos/e03997ece58ee6f6.mp4\", \"size\": 2848208, \"type\": \"videos\", \"safe_name\": \"e03997ece58ee6f6.mp4\"}]', 0, '2026-10-08 20:01:25'),
(8, 1, 11, 1, NULL, NULL, '[{\"mime\": \"video/mp4\", \"name\": \"sample-5s.mp4\", \"path\": \"/uploads/videos/ccad6a22dc576609.mp4\", \"size\": 2848208, \"type\": \"videos\", \"safe_name\": \"ccad6a22dc576609.mp4\"}]', 0, '2026-10-08 20:02:23'),
(9, 1, 11, 1, NULL, NULL, '[{\"mime\": \"video/mp4\", \"name\": \"sample-10s.mp4\", \"path\": \"/uploads/videos/ddf80af7030b4f34.mp4\", \"size\": 5485935, \"type\": \"videos\", \"safe_name\": \"ddf80af7030b4f34.mp4\"}]', 0, '2026-10-08 20:09:39'),
(10, 1, 11, 1, NULL, NULL, '[{\"mime\": \"image/png\", \"name\": \"Shape sp-347-0-3.png\", \"path\": \"/uploads/images/a01934319961abdc.png\", \"size\": 149, \"type\": \"images\", \"safe_name\": \"a01934319961abdc.png\"}]', 0, '2026-10-08 20:09:54');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_room` (`room_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
