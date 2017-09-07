-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.34 - MySQL Community Server (GPL)
-- Операционная система:         Win32
-- HeidiSQL Версия:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица newpoll.division
DROP TABLE IF EXISTS `division`;
CREATE TABLE IF NOT EXISTS `division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы newpoll.division: ~4 rows (приблизительно)
DELETE FROM `division`;
/*!40000 ALTER TABLE `division` DISABLE KEYS */;
INSERT INTO `division` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'ИП Коняхин Р.А.', '2017-09-01 11:46:39', '2017-09-01 11:46:39'),
	(2, 'ИП Борисенко А.В.', '2017-09-01 13:24:06', '2017-09-01 13:24:06'),
	(3, 'ИП Леньшин И.В.', '2017-09-01 13:38:59', '2017-09-01 13:38:59'),
	(4, 'ИП Леньшина Л.Г.', '2017-09-01 13:43:11', '2017-09-01 13:43:11');
/*!40000 ALTER TABLE `division` ENABLE KEYS */;

-- Дамп структуры для таблица newpoll.ecounter
DROP TABLE IF EXISTS `ecounter`;
CREATE TABLE IF NOT EXISTS `ecounter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `koeff` float NOT NULL,
  `tarif` float NOT NULL DEFAULT '3.5',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы newpoll.ecounter: ~3 rows (приблизительно)
DELETE FROM `ecounter`;
/*!40000 ALTER TABLE `ecounter` DISABLE KEYS */;
INSERT INTO `ecounter` (`id`, `name`, `text`, `koeff`, `tarif`, `created_at`, `updated_at`) VALUES
	(1, 'Главный', 'счетчик на 69 ПС', 7200, 3.5, '2017-09-01 14:43:00', '2017-09-01 14:43:00'),
	(2, 'Общий на СК', 'Общий счетчик на СК', 200, 3.5, '2017-09-01 14:44:30', '2017-09-01 14:44:30'),
	(3, 'Общий на МС', 'ГРЩ на МС', 200, 3.5, '2017-09-01 14:45:51', '2017-09-01 14:45:51');
/*!40000 ALTER TABLE `ecounter` ENABLE KEYS */;

-- Дамп структуры для таблица newpoll.place
DROP TABLE IF EXISTS `place`;
CREATE TABLE IF NOT EXISTS `place` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ecounter_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_place_ecounter` (`ecounter_id`),
  CONSTRAINT `FK_place_ecounter` FOREIGN KEY (`ecounter_id`) REFERENCES `ecounter` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы newpoll.place: ~5 rows (приблизительно)
DELETE FROM `place`;
/*!40000 ALTER TABLE `place` DISABLE KEYS */;
INSERT INTO `place` (`id`, `name`, `ecounter_id`, `created_at`, `updated_at`) VALUES
	(1, 'МС Выставка', 3, '2017-09-01 16:13:21', '2017-09-01 17:45:40'),
	(2, 'Складской комплекс', 2, '2017-09-01 17:56:02', '2017-09-01 17:56:02'),
	(3, 'МС ОП', 3, '2017-09-01 18:08:18', '2017-09-01 18:08:18'),
	(4, 'МС ОПГС', 3, '2017-09-01 18:08:49', '2017-09-01 18:08:49'),
	(5, 'Парковка большая', 3, '2017-09-01 18:09:17', '2017-09-01 18:09:17');
/*!40000 ALTER TABLE `place` ENABLE KEYS */;

-- Дамп структуры для таблица newpoll.renter
DROP TABLE IF EXISTS `renter`;
CREATE TABLE IF NOT EXISTS `renter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `area` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `agent` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phone1` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `encounter` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `koeff` float DEFAULT '4.7',
  `place_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `division_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `encounter` (`encounter`),
  KEY `FK_renter_division` (`division_id`),
  KEY `FK_renter_place` (`place_id`),
  CONSTRAINT `FK_renter_division` FOREIGN KEY (`division_id`) REFERENCES `division` (`id`),
  CONSTRAINT `FK_renter_place` FOREIGN KEY (`place_id`) REFERENCES `place` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы newpoll.renter: ~10 rows (приблизительно)
DELETE FROM `renter`;
/*!40000 ALTER TABLE `renter` DISABLE KEYS */;
INSERT INTO `renter` (`id`, `title`, `area`, `agent`, `phone1`, `phone2`, `encounter`, `koeff`, `place_id`, `status`, `division_id`, `created_at`, `updated_at`) VALUES
	(1, 'Домострой-ЛСК', '6', 'Лапай Вадим', '+7 (495) 943-43-52', '+7 (903) 285-88-30', '8-6', 4.7, 1, 1, 1, '2017-09-01 19:15:17', '2017-09-03 18:42:17'),
	(2, 'Энерго Девелопмент', '7', 'Клочков Алексей', '+7(495) 776-63-35', '+7(985) 135-11-35', '8-7', 4.7, 1, 1, 2, '2017-09-03 18:43:33', '2017-09-03 18:43:33'),
	(3, 'Дас Хауз', '8', 'Иванов Андрей', '+7(4932) 22-90-22', '+7(925) 713-31-72', '8-8', 4.7, 1, 1, 2, '2017-09-03 19:02:26', '2017-09-03 19:02:26'),
	(4, 'Шельф', '9', 'Внуков Иван', '+7(495) 517-59-54', '', '8-9', 4.7, 1, 1, 2, '2017-09-03 19:04:35', '2017-09-03 19:04:35'),
	(5, 'Вологда Сруб', '10', 'Николай, Светлана', '+7(916) 178-86-89 ', '+7(903) 280-48-87 ', '8-10', 4.7, 1, 1, 2, '2017-09-03 19:06:19', '2017-09-03 19:06:19'),
	(6, 'Правильный дом', '14', 'Коваленко Валерий', '+7(985) 774-7782', '', '8-14', 4.7, 1, 1, 2, '2017-09-03 19:08:12', '2017-09-03 19:08:12'),
	(7, 'ЛесДревПром', '15', 'Гвоздяный Евгений', '+7(909) 162-37-50', '', '8-15', 4.7, 1, 1, 2, '2017-09-03 19:09:43', '2017-09-03 19:51:03'),
	(8, 'Тамак', '16', 'Андрей Палей', '+7(916) 336-76-05', '', '8-16', 4.7, 1, 1, 2, '2017-09-03 19:57:51', '2017-09-03 19:57:51'),
	(9, 'СтройДом', '18', 'Комов Олег', '', '', '8-18', 4.7, 1, 1, 1, '2017-09-03 20:06:55', '2017-09-03 20:06:55'),
	(10, 'ИП Рожков', '19', 'Федоров Сергей', '+7(915) 127-31-98', '', '8-19', 4.7, 1, 1, 1, '2017-09-03 20:14:36', '2017-09-03 20:14:36');
/*!40000 ALTER TABLE `renter` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
