-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 08-12-2021 a las 16:00:01
-- Versión del servidor: 5.7.31
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mycoinfolioproject_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `analysis`
--

DROP TABLE IF EXISTS `analysis`;
CREATE TABLE IF NOT EXISTS `analysis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(3000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_33C73012469DE2` (`category_id`),
  KEY `IDX_33C730A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `analysis`
--

INSERT INTO `analysis` (`id`, `title`, `image`, `content`, `date`, `category_id`, `user_id`) VALUES
(1, 'XPR expect great upside', '4162b1b506dd.png', 'sadasfdsfdsfdsfdsfdsfd', '2021-11-23 16:27:42', 1, 2),
(2, 'BTC Moving to the moon', '81505fdb8cbe.png', 'n one of the scenarios published on the previous analysis on December 2, I was watching also for the price to test the low of the rectangular pattern again and as we can see it already happened.\r\n\r\nNow we can see also an interesting fact added in our chart. On December 4, and December 6 we can see a big sell of and after that all the price decrease was recovered again.\r\n\r\nSo as we can see the price has its own comfort area located between 21 and 27.5\r\nWhen the price will move above the comfort area from the technical perspective it should explode very strong to the upside.', '2021-12-08 15:52:38', 1, 2),
(3, 'BTC Moving to the moon', 'f91e992344f8.png', 'n one of the scenarios published on the previous analysis on December 2, I was watching also for the price to test the low of the rectangular pattern again and as we can see it already happened.\r\n\r\nNow we can see also an interesting fact added in our chart. On December 4, and December 6 we can see a big sell of and after that all the price decrease was recovered again.\r\n\r\nSo as we can see the price has its own comfort area located between 21 and 27.5\r\nWhen the price will move above the comfort area from the technical perspective it should explode very strong to the upside.', '2021-12-08 15:54:57', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Technical'),
(2, 'Fundamental'),
(3, 'Chamanic'),
(4, 'Test');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(1500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `analysis_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  KEY `IDX_9474526C7941003F` (`analysis_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `comment`
--

INSERT INTO `comment` (`id`, `message`, `date`, `picture`, `user_id`, `analysis_id`) VALUES
(4, 'Mierda', '2021-11-24 23:46:03', '5f134e96092a.jpg', 2, 1),
(5, 'joderrrrrrrrrr', '2021-11-24 23:46:28', NULL, 2, 1),
(6, 'We built Ideas so that anyone, anywhere in the world, can easily share their thoughts and opinions on global markets. Now, thousands and thousands of users create Ideas every day from our charting platform', '2021-12-08 15:55:36', '636b7948458a.png', 2, 2),
(7, 'After the recent top at 115.50, UsdJpy dropped aggressively and reached 112.50 support zone .\r\nFrom here a rebound has followed at this moment the pair is reversing from 113.50 zone resistance.', '2021-12-08 15:56:46', '9117f396fe89.png', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contain`
--

DROP TABLE IF EXISTS `contain`;
CREATE TABLE IF NOT EXISTS `contain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crypto_id` int(11) NOT NULL,
  `portfolio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4BEFF7C8E9571A63` (`crypto_id`),
  KEY `IDX_4BEFF7C8B96B5643` (`portfolio_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contain`
--

INSERT INTO `contain` (`id`, `crypto_id`, `portfolio_id`) VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `crypto`
--

DROP TABLE IF EXISTS `crypto`;
CREATE TABLE IF NOT EXISTS `crypto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_price` double NOT NULL,
  `buy_date` datetime NOT NULL,
  `quantity` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `crypto`
--

INSERT INTO `crypto` (`id`, `name`, `entry_price`, `buy_date`, `quantity`) VALUES
(1, 'BTC', 6530, '2016-09-12 00:00:00', 0.65),
(2, 'LTC', 210, '2020-07-09 00:00:00', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20211122094305', '2021-11-22 09:43:18', 67),
('DoctrineMigrations\\Version20211122115508', '2021-11-22 11:55:13', 229),
('DoctrineMigrations\\Version20211122124330', '2021-11-22 12:43:58', 82),
('DoctrineMigrations\\Version20211123154617', '2021-11-23 15:46:24', 165),
('DoctrineMigrations\\Version20211123155326', '2021-11-23 15:53:31', 162),
('DoctrineMigrations\\Version20211123155514', '2021-11-23 15:55:17', 165),
('DoctrineMigrations\\Version20211123161712', '2021-11-23 16:17:17', 251),
('DoctrineMigrations\\Version20211124163800', '2021-11-24 16:38:11', 142),
('DoctrineMigrations\\Version20211124233156', '2021-11-24 23:32:01', 168);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portfolio`
--

DROP TABLE IF EXISTS `portfolio`;
CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `modified_at` datetime DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A9ED1062A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `portfolio`
--

INSERT INTO `portfolio` (`id`, `created_at`, `modified_at`, `total_price`, `user_id`) VALUES
(1, '2021-11-23 16:11:53', NULL, NULL, 2),
(2, '2021-12-08 15:58:23', NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `modified_at` datetime DEFAULT NULL,
  `newsletter` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`, `avatar`, `created_at`, `modified_at`, `newsletter`) VALUES
(2, 'admin@admin.com', '[\"ROLE_ADMIN\"]', '$2y$13$fcsoCeTncgZGjMa5b60gqOYXeZqVPbktic3BbQooXRqYWpRvc3Pei', 'admin', '609d9a5d61f4.png', '2021-11-22 15:22:29', '2021-11-23 15:23:12', 0),
(5, 'manager@manager.com', '[\"ROLE_MANAGER\"]', '$2y$13$D08FpQ4OFWwwSy2WgNOtuew.t2lulMrvYrsB.uX15LhXkJ1bfJbJq', 'manager', '1eaa2e013d85.jpg', '2021-12-08 15:49:10', NULL, 1),
(6, 'user@user.com', '[]', '$2y$13$6W2KtRM9DwStYR6QO.OEZeb9bDpi8c.a99iCbXOjym5qvc3AXKjaq', 'user', 'c1804ba3f0ba.png', '2021-12-08 15:50:26', NULL, 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `analysis`
--
ALTER TABLE `analysis`
  ADD CONSTRAINT `FK_33C73012469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_33C730A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C7941003F` FOREIGN KEY (`analysis_id`) REFERENCES `analysis` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `contain`
--
ALTER TABLE `contain`
  ADD CONSTRAINT `FK_4BEFF7C8B96B5643` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolio` (`id`),
  ADD CONSTRAINT `FK_4BEFF7C8E9571A63` FOREIGN KEY (`crypto_id`) REFERENCES `crypto` (`id`);

--
-- Filtros para la tabla `portfolio`
--
ALTER TABLE `portfolio`
  ADD CONSTRAINT `FK_A9ED1062A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
