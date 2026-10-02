-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 02 2026 г., 16:43
-- Версия сервера: 5.7.39-log
-- Версия PHP: 8.2.12

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
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_paths` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `to_user_id`, `room_id`, `text`, `created_at`, `is_read`, `image_path`, `image_paths`) VALUES
(1, 1, 11, 1, 'jjj', '2026-09-28 13:48:34', 1, NULL, NULL),
(2, 1, 11, 1, 'gg', '2026-09-29 05:48:55', 1, NULL, NULL),
(3, 1, 11, 1, NULL, '2026-09-29 05:51:41', 1, 'uploads/chat_images/img_6abb51ecd25b2_1790661100.jpg', NULL),
(4, 11, 1, 1, 'ghenj', '2026-09-29 06:07:41', 1, NULL, NULL),
(5, 1, 11, 1, 'fff', '2026-09-29 07:18:37', 1, NULL, NULL),
(6, 1, 11, 1, NULL, '2026-09-29 07:18:49', 1, NULL, '[\"uploads/chat_images/img_6abb665971400_1790666329.jpg\"]'),
(7, 1, 11, 1, NULL, '2026-09-29 07:19:21', 1, NULL, '[\"uploads/chat_images/img_6abb667919e28_1790666361.jpg\"]'),
(8, 1, 11, 1, NULL, '2026-09-29 11:16:14', 1, NULL, '[\"uploads/chat_images/img_6abb9dfde7897_1790680573.jpg\", \"uploads/chat_images/img_6abb9dfe00fcf_1790680574.png\"]'),
(9, 1, 11, 1, 'апап', '2026-09-29 11:16:23', 1, NULL, NULL),
(10, 1, 11, 1, NULL, '2026-09-29 11:16:35', 1, NULL, '[\"uploads/chat_images/img_6abb9e12dc9d6_1790680594.jpg\"]'),
(11, 1, 11, 1, NULL, '2026-09-29 13:03:23', 1, NULL, '[\"uploads/chat_images/img_6abbb71b92af8_1790687003.jpg\", \"uploads/chat_images/img_6abbb71ba144b_1790687003.jpg\", \"uploads/chat_images/img_6abbb71baef5f_1790687003.png\", \"uploads/chat_images/img_6abbb71bb2047_1790687003.png\"]'),
(12, 1, 11, 1, 'fdgfg', '2026-09-29 13:13:31', 1, NULL, NULL),
(13, 1, 11, 1, NULL, '2026-09-29 13:14:07', 1, NULL, '[\"uploads/chat_images/img_6abbb99fbb358_1790687647.jpg\"]'),
(14, 1, 11, 1, 'kk', '2026-09-30 08:29:23', 1, NULL, NULL),
(15, 1, 11, 1, 'dd', '2026-09-30 08:31:00', 1, NULL, NULL),
(16, 1, 11, 1, NULL, '2026-09-30 08:52:28', 1, NULL, '[\"uploads/chat_images/img_6abccdccbc716_1790758348.jpg\"]'),
(17, 1, 11, 1, 'hhh', '2026-09-30 08:52:43', 1, NULL, NULL),
(18, 1, 11, 1, NULL, '2026-09-30 08:53:02', 1, NULL, '[\"uploads/chat_images/img_6abccdee7e6e9_1790758382.jpg\"]'),
(19, 1, 11, 1, NULL, '2026-09-30 08:54:14', 1, NULL, '[\"uploads/chat_images/img_6abcce363c0ac_1790758454.jpg\", \"uploads/chat_images/img_6abcce3646fa4_1790758454.png\", \"uploads/chat_images/img_6abcce364a2a2_1790758454.png\"]'),
(20, 1, 11, 1, NULL, '2026-09-30 10:27:34', 1, NULL, '[\"uploads/chat_images/img_6abce4160669e_1790764054.jpg\"]'),
(21, 1, 11, 1, 'fgfg', '2026-09-30 10:27:41', 1, NULL, NULL),
(22, 1, 11, 1, NULL, '2026-09-30 10:27:50', 1, NULL, '[\"uploads/chat_images/img_6abce426bd61b_1790764070.jpg\", \"uploads/chat_images/img_6abce426cc77f_1790764070.png\"]'),
(23, 1, 11, 1, 'ghbdtn', '2026-09-30 11:08:21', 1, NULL, '[\"uploads/chat_images/img_6abceda585be1_1790766501.jpg\"]'),
(24, 1, 11, 1, NULL, '2026-09-30 11:37:16', 1, NULL, '[\"uploads/chat_images/img_6abcf46be70f3_1790768235.jpg\"]'),
(25, 1, 11, 1, 'ffff', '2026-09-30 11:41:11', 1, NULL, NULL),
(26, 1, 11, 1, NULL, '2026-09-30 11:41:30', 1, NULL, '[\"uploads/chat_images/img_6abcf56aa9941_1790768490.jpg\"]'),
(27, 1, 11, 1, 'апра', '2026-09-30 13:24:40', 1, NULL, NULL),
(28, 1, 11, 1, 'авпва', '2026-10-01 10:14:20', 1, NULL, NULL),
(29, 1, 11, 1, NULL, '2026-10-01 13:17:40', 1, NULL, '[\"uploads/chat_images/img_6abe5d747604f_1790860660.jpg\"]'),
(30, 1, 11, 1, NULL, '2026-10-01 13:42:16', 1, NULL, '[\"uploads/chat_images/img_6abe633893dc6_1790862136.jpg\"]'),
(31, 1, 11, 1, NULL, '2026-10-01 13:52:45', 1, NULL, '[\"uploads/chat_images/img_6abe65adcf3d4_1790862765.jpg\"]'),
(32, 1, 11, 1, NULL, '2026-10-02 07:59:57', 0, NULL, '[\"uploads/chat_images/img_6abf647d05426_1790927997.jpg\"]'),
(33, 1, 11, 1, NULL, '2026-10-02 08:16:44', 0, NULL, '[\"uploads/chat_images/img_6abf686c4f055_1790929004.jpg\", \"uploads/chat_images/img_6abf686c5ad8f_1790929004.png\"]'),
(34, 1, 11, 1, NULL, '2026-10-02 08:18:02', 0, NULL, '[\"uploads/chat_images/img_6abf68ba4438f_1790929082.png\"]'),
(35, 1, 11, 1, NULL, '2026-10-02 08:26:51', 0, NULL, '[\"uploads/chat_images/img_6abf6acb30755_1790929611.jpg\", \"uploads/chat_images/img_6abf6acb42b72_1790929611.png\"]'),
(36, 1, 11, 1, NULL, '2026-10-02 10:17:50', 0, NULL, '[\"uploads/chat_images/img_6abf84ce78e3b_1790936270.png\", \"uploads/chat_images/img_6abf84ce85069_1790936270.png\", \"uploads/chat_images/img_6abf84ce8e377_1790936270.png\", \"uploads/chat_images/img_6abf84ce9427b_1790936270.png\"]'),
(37, 1, 11, 1, NULL, '2026-10-02 11:56:38', 0, NULL, '[\"uploads/chat_images/img_6abf9bf6526e4_1790942198.jpg\", \"uploads/chat_images/img_6abf9bf65e1da_1790942198.jpg\", \"uploads/chat_images/img_6abf9bf6687e0_1790942198.png\"]'),
(38, 1, 11, 1, NULL, '2026-10-02 12:16:30', 0, NULL, '[\"uploads/chat_images/img_6abfa09deb60b_1790943389.jpg\", \"uploads/chat_images/img_6abfa09e03f1e_1790943390.jpg\", \"uploads/chat_images/img_6abfa09e0eb79_1790943390.jpg\", \"uploads/chat_images/img_6abfa09e195e2_1790943390.jpg\", \"uploads/chat_images/img_6abfa09e26b85_1790943390.jpg\", \"uploads/chat_images/img_6abfa09e3263a_1790943390.png\", \"uploads/chat_images/img_6abfa09e35116_1790943390.jpg\"]'),
(39, 1, 11, 1, 'hgfh', '2026-10-02 13:23:06', 0, NULL, NULL),
(40, 1, 11, 1, 'ddd', '2026-10-02 13:23:45', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`id`, `user1_id`, `user2_id`, `created_at`) VALUES
(1, 1, 11, '2026-09-21 13:09:22'),
(2, 1, 2, '2026-09-21 13:25:17'),
(3, 2, 11, '2026-09-21 13:25:36');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `password`, `avatar_path`, `created_at`) VALUES
(1, 'Евгений', 'Абаров', '$2y$10$NEYuO1AOHa8O7WocaKpdkumfqDxRcMbHtWob7IAHfgYFouaESrXeO', '/uploads/avatars/user_1789473036_444.jpg', '2026-09-15 11:50:36'),
(2, 'Петр', 'Петров', '$2y$10$hDBGgNSdAv4.tu2w3RjxIuLT0Aq.ECTH5hOp4LtT.ejxSyovma8KC', '/uploads/avatars/user_1789473881_338.png', '2026-09-15 12:04:42'),
(11, 'Маша', 'Машина', '$2y$10$SABnduzsimp0y8JRFZlZXO68g8SbSdJIospq1yDkyI/XtKimcwYT6', '/uploads/avatars/user_1789729897_266.jpg', '2026-09-18 11:11:37');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `to_user_id` (`to_user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Индексы таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pair` (`user1_id`,`user2_id`),
  ADD KEY `user2_id` (`user2_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Ограничения внешнего ключа таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rooms_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
