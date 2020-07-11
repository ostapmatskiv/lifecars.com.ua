-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Лип 11 2020 р., 17:13
-- Версія сервера: 10.3.18-MariaDB
-- Версія PHP: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База даних: `stezhkam_lifecars`
--

-- --------------------------------------------------------

--
-- Структура таблиці `s_cart`
--

CREATE TABLE IF NOT EXISTS `s_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `shipping_id` int(11) DEFAULT NULL,
  `shipping_info` text DEFAULT NULL,
  `payment_alias` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `total` float unsigned NOT NULL,
  `payed` float unsigned DEFAULT NULL,
  `bonus` int(11) DEFAULT NULL,
  `discount` float unsigned DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `manager_comment` text DEFAULT NULL,
  `ttn` text DEFAULT NULL,
  `date_add` int(11) NOT NULL,
  `manager` int(11) DEFAULT NULL,
  `date_edit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `s_cart_bonus`
--

CREATE TABLE IF NOT EXISTS `s_cart_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL,
  `code` varchar(12) NOT NULL,
  `count_do` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `discount_type` tinyint(1) NOT NULL,
  `discount` float NOT NULL,
  `discount_max` float NOT NULL,
  `order_min` float NOT NULL,
  `info` text NOT NULL,
  `manager` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `s_cart_history`
--

CREATE TABLE IF NOT EXISTS `s_cart_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `show` tinyint(1) NOT NULL,
  `user` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `s_cart_payments`
--

CREATE TABLE IF NOT EXISTS `s_cart_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wl_alias` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `name` text NOT NULL,
  `info` text DEFAULT NULL,
  `tomail` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп даних таблиці `s_cart_payments`
--

INSERT INTO `s_cart_payments` (`id`, `wl_alias`, `active`, `position`, `name`, `info`, `tomail`) VALUES
(1, 0, 1, 1, 'Готівкою при отриманні', 'Оплата готівкою при доставці/отриманні товару.', NULL),
(2, 0, 0, 2, 'Оплатити на рахунок за реквізитами', 'Реквізити оплати отримаєте листом на електронну скриньку', NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `s_cart_products`
--

CREATE TABLE IF NOT EXISTS `s_cart_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart` int(11) DEFAULT NULL,
  `user` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `product_alias` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_options` text DEFAULT NULL,
  `storage_alias` int(11) DEFAULT NULL,
  `storage_invoice` int(11) DEFAULT NULL,
  `price` float unsigned NOT NULL,
  `price_in` float unsigned DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `quantity_wont` int(11) NOT NULL,
  `quantity_returned` int(11) DEFAULT NULL,
  `discount` float unsigned DEFAULT NULL,
  `bonus` int(11) DEFAULT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп даних таблиці `s_cart_products`
--

INSERT INTO `s_cart_products` (`id`, `cart`, `user`, `active`, `product_alias`, `product_id`, `product_options`, `storage_alias`, `storage_invoice`, `price`, `price_in`, `quantity`, `quantity_wont`, `quantity_returned`, `discount`, `bonus`, `date`) VALUES
(1, 0, -1, 1, 8, 8, '', 0, 0, 1664.03, 61.86, 1, 1, 0, 0, 0, 1593586420),
(2, 0, -2, 1, 8, 8, '', 0, 0, 1664.03, 61.86, 6, 6, 0, 0, 0, 1593609169),
(3, 0, 1, 1, 8, 3, '', 0, 0, 79.893, 2.97, 3, 3, 0, 0, 0, 1594030517),
(4, 0, -3, 1, 8, 28, '', 0, 0, 21.91, 21.91, 1, 1, 0, 0, 0, 1593634490),
(5, 0, -1, 1, 8, 2, '', 0, 0, 65.098, 2.42, 1, 1, 0, 0, 0, 1593720803),
(6, 0, 1, 1, 8, 8, '', 0, 0, 1664.03, 61.86, 1, 1, 0, 0, 0, 1594162023),
(7, 0, -10, 1, 8, 27, '', 0, 0, 563.017, 20.93, 1, 1, 0, 0, 0, 1594214508),
(8, 0, -6, 1, 8, 8, '', 0, 0, 1664.03, 61.86, 1, 1, 0, 0, 0, 1594220683),
(9, 0, -6, 1, 8, 5, '', 0, 0, 1055.82, 39.25, 1, 1, 0, 0, 0, 1594315321);

-- --------------------------------------------------------

--
-- Структура таблиці `s_cart_shipping`
--

CREATE TABLE IF NOT EXISTS `s_cart_shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wl_alias` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `name` text NOT NULL,
  `info` text DEFAULT NULL,
  `pay` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `s_cart_status`
--

CREATE TABLE IF NOT EXISTS `s_cart_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `color` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `weight` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп даних таблиці `s_cart_status`
--

INSERT INTO `s_cart_status` (`id`, `name`, `color`, `active`, `weight`) VALUES
(1, 'Нове', 'warning', 1, 0),
(2, 'Підтверджене', 'success', 0, 10),
(3, 'Формування замовлення', 'warning', 1, 11),
(4, 'Частково відправлено', 'warning', 0, 20),
(5, 'Відправлено', 'primary', 1, 21),
(6, 'Закрите', 'default', 1, 98),
(7, 'Скасоване', 'default', 1, 99);

-- --------------------------------------------------------

--
-- Структура таблиці `s_cart_users`
--

CREATE TABLE IF NOT EXISTS `s_cart_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `cookie` char(32) NOT NULL,
  `date_add` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cookie` (`cookie`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп даних таблиці `s_cart_users`
--

INSERT INTO `s_cart_users` (`id`, `user`, `cookie`, `date_add`) VALUES
(1, 2, '045b1be315d316cf8f27c367ded9c4a8', 1593586382),
(2, NULL, '599327c7234270ce92b383032f98ec77', 1593609126),
(3, NULL, '133a0e7a78f927c77bd200138594c7ca', 1593634425),
(4, 1, 'ece278e03d8ae15f98c6cdf1b8353907', 1594114728),
(5, NULL, 'ebf1e94457de743f3bb12944a35c263f', 1594117682),
(6, NULL, '927e443c5ef8bea52bbf9385bf37ab36', 1594118213),
(7, NULL, 'bcd96b779f5c7febbde5ba410c038167', 1594118838),
(8, NULL, '54d73ea3a3dd9199a55cc2f113e91564', 1594120449),
(9, 1, 'c6f3e3c83206b2204e1854eec382affe', 1594144073),
(10, NULL, '41267d85cff26ac9577d3d85bbcbd467', 1594214495),
(11, NULL, 'e54f6829d5574e419a90a24e32e5c24c', 1594289663),
(12, NULL, 'f7e2ef90cef7c3eeeed2c6d2d77d8038', 1594411037);

-- --------------------------------------------------------

--
-- Структура таблиці `s_currency`
--

CREATE TABLE IF NOT EXISTS `s_currency` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `code` varchar(3) NOT NULL,
  `currency` float NOT NULL,
  `day` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп даних таблиці `s_currency`
--

INSERT INTO `s_currency` (`id`, `code`, `currency`, `day`) VALUES
(1, 'USD', 26.9, 1593388800),
(2, 'UAH', 1, 1593388800);

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_availability`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_availability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп даних таблиці `s_shopshowcase_availability`
--

INSERT INTO `s_shopshowcase_availability` (`id`, `color`, `active`, `position`) VALUES
(1, 'rgb(2, 204, 2)', 1, 1),
(2, 'rgb(255, 163, 0)', 1, 2),
(3, 'red', 1, 3);

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_availability_name`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_availability_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `availability` int(11) NOT NULL,
  `language` varchar(2) DEFAULT '',
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп даних таблиці `s_shopshowcase_availability_name`
--

INSERT INTO `s_shopshowcase_availability_name` (`id`, `availability`, `language`, `name`) VALUES
(1, 1, '', 'В наявності'),
(2, 2, '', 'Очікується'),
(3, 3, '', 'Немає');

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_groups`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_1c` tinytext NOT NULL,
  `wl_alias` int(11) NOT NULL,
  `alias` text DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT NULL,
  `author_add` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `author_edit` int(11) NOT NULL,
  `export_prom` tinyint(1) DEFAULT 1,
  `export_google` tinyint(1) DEFAULT 1,
  `export_facebook` tinyint(1) DEFAULT 1,
  `date_edit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `wl_alias` (`wl_alias`),
  KEY `parent` (`parent`),
  KEY `position` (`position`),
  KEY `active` (`active`),
  FULLTEXT KEY `id_1c` (`id_1c`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Дамп даних таблиці `s_shopshowcase_groups`
--

INSERT INTO `s_shopshowcase_groups` (`id`, `id_1c`, `wl_alias`, `alias`, `parent`, `position`, `active`, `hide`, `author_add`, `date_add`, `author_edit`, `export_prom`, `export_google`, `export_facebook`, `date_edit`) VALUES
(1, '00000000002', 8, 'chery', 0, 1, 1, 0, 0, 1592949300, 1, 1, 1, 1, 1594128808),
(3, '00000000001', 8, 'geely', 0, 2, 1, 0, 0, 1592949300, 1, 1, 1, 1, 1594129568),
(5, '00000000008', 8, 'byd', 0, 3, 1, 0, 0, 1592949300, 1, 1, 1, 1, 1594128543),
(6, '00000000006', 8, 'great-wall', 0, 4, 1, 0, 0, 1592949300, 1, 1, 1, 1, 1594128440),
(7, '00000000007', 8, 'lifan', 0, 5, 1, 0, 0, 1592949300, 1, 1, 1, 1, 1594128715),
(8, '000000025', 8, 'qq', 1, 1, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(9, '000000010', 8, 'amulet', 1, 2, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(10, '000000019', 8, 'kimo', 1, 2, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(11, '000000016', 8, 'jaggi', 1, 3, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(13, '000000024', 8, 'elara', 1, 3, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(14, '000000009', 8, 'ck', 3, 1, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(16, '000000017', 8, 'tiggo', 1, 4, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(17, '000000021', 8, 'ck2', 3, 2, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(18, '000000023', 8, 'mk-2mk-cross', 3, 2, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(19, '000000018', 8, 'mkmk-new', 3, 3, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(20, '000000032', 8, 'gs6', 3, 3, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(21, '000000028', 8, 'lc-cross-(gx2)', 3, 4, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(22, '000000029', 8, 'lc-panda-(gc2)', 3, 5, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(23, '000000026', 8, 'eastar', 1, 5, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(24, '000000022', 8, 'mk2', 3, 6, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(26, '000000013', 8, 'fc', 3, 7, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(27, '000000011', 8, 'emgrand-ec7', 3, 8, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(28, '000000012', 8, 'emgrand-ec7rv', 3, 9, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(29, '000000048', 8, 'f3', 5, 20, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(30, '000000049', 8, 'f6', 5, 21, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(31, '000000037', 8, 'haval-h3', 6, 22, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(32, '000000039', 8, 'hover', 6, 23, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(33, '000000027', 8, 'm11', 1, 6, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(34, '000000038', 8, 'haval-h5', 6, 25, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(35, '000000043', 8, '320-smily', 7, 1, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(36, '000000044', 8, '520-breez', 7, 2, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(37, '000000045', 8, '620-solano', 7, 2, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(38, '000000030', 8, 'e5', 1, 7, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(39, '000000031', 8, 'beat', 1, 8, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(40, '000000042', 8, 'x60', 7, 3, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(41, '000000034', 8, 'arizzo-7', 1, 9, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(42, '000000035', 8, 'tiggo-3', 1, 10, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(43, '000000036', 8, 'tiggo-5', 1, 11, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(44, '000000040', 8, 'sl', 3, 10, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(45, '000000041', 8, 'x7', 3, 11, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(46, '000000046', 8, 'arrizo-3', 1, 12, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(47, '000000047', 8, 'tiggo-2', 1, 13, 1, 0, 0, 1592949300, 0, 1, 1, 1, 1592949300),
(48, '000000050', 8, 'a13-(zaz-forza)', 1, 14, 1, 0, 0, 1594324527, 0, 1, 1, 1, 1594324527);

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_options`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wl_alias` int(11) NOT NULL,
  `group` int(11) DEFAULT NULL,
  `alias` text DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `main` tinyint(1) DEFAULT 0,
  `changePrice` text DEFAULT NULL,
  `filter` tinyint(1) DEFAULT NULL,
  `toCart` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `sort` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `wl_alias` (`wl_alias`),
  KEY `group` (`group`),
  KEY `active` (`active`),
  KEY `position` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Дамп даних таблиці `s_shopshowcase_options`
--

INSERT INTO `s_shopshowcase_options` (`id`, `wl_alias`, `group`, `alias`, `photo`, `position`, `type`, `main`, `changePrice`, `filter`, `toCart`, `active`, `sort`) VALUES
(1, 8, 0, '1-manufacturer', NULL, 1, 10, 1, '0', 1, 0, 1, 0),
(2, 8, 0, '2-part', NULL, 2, 12, 0, '0', 1, 0, 1, 0),
(3, 8, -1, '0000000000007', NULL, 1, NULL, 0, NULL, NULL, NULL, 1, NULL),
(4, 8, -1, '0000000000003', NULL, 2, NULL, 0, NULL, NULL, NULL, 1, NULL),
(5, 8, -1, '0000000000005', NULL, 3, NULL, 0, NULL, NULL, NULL, 1, NULL),
(6, 8, -1, '0000000000002', NULL, 4, NULL, 0, NULL, NULL, NULL, 1, NULL),
(7, 8, -1, '0000000000001', NULL, 5, NULL, 0, NULL, NULL, NULL, 1, NULL),
(8, 8, -1, '0000000000004', NULL, 6, NULL, 0, NULL, NULL, NULL, 1, NULL),
(9, 8, -1, '0000000000006', NULL, 7, NULL, 0, NULL, NULL, NULL, 1, NULL),
(10, 8, -1, '0000000000008', NULL, 8, NULL, 0, NULL, NULL, NULL, 1, NULL),
(11, 8, -1, '0000000000009', NULL, 9, NULL, 0, NULL, NULL, NULL, 1, NULL),
(12, 8, -1, '0000000000012', NULL, 10, NULL, 0, NULL, NULL, NULL, 1, NULL),
(13, 8, -1, '0000000000014', NULL, 11, NULL, 0, NULL, NULL, NULL, 1, NULL),
(14, 8, -1, '0000000000016', NULL, 12, NULL, 0, NULL, NULL, NULL, 1, NULL),
(15, 8, -1, '0000000000010', NULL, 13, NULL, 0, NULL, NULL, NULL, 1, NULL),
(16, 8, -1, '0000000000013', NULL, 14, NULL, 0, NULL, NULL, NULL, 1, NULL),
(17, 8, -1, '0000000000015', NULL, 15, NULL, 0, NULL, NULL, NULL, 1, NULL),
(18, 8, -2, '000000002', '18_000000002.svg', 1, NULL, 0, NULL, NULL, NULL, 1, NULL),
(19, 8, -2, '000000001', '19_000000001.svg', 2, NULL, 0, NULL, NULL, NULL, 1, NULL),
(20, 8, -2, '000000005', '20_000000005.svg', 3, NULL, 0, NULL, NULL, NULL, 1, NULL),
(21, 8, -2, '000000003', '21_000000003.svg', 4, NULL, 0, NULL, NULL, NULL, 1, NULL),
(22, 8, -2, '000000004', '22_000000004.svg', 5, NULL, 0, NULL, NULL, NULL, 1, NULL),
(23, 8, -2, '000000008', '23_000000008.svg', 6, NULL, 0, NULL, NULL, NULL, 1, NULL),
(24, 8, -2, '000000007', '24_000000007.svg', 7, NULL, 0, NULL, NULL, NULL, 1, NULL),
(25, 8, -2, '000000006', '25_000000006.svg', 8, NULL, 0, NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_options_name`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_options_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option` int(11) NOT NULL,
  `language` enum('uk','ru') NOT NULL,
  `name` text DEFAULT NULL,
  `sufix` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `option` (`option`,`language`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Дамп даних таблиці `s_shopshowcase_options_name`
--

INSERT INTO `s_shopshowcase_options_name` (`id`, `option`, `language`, `name`, `sufix`) VALUES
(1, 1, 'uk', 'Виробник', ''),
(2, 1, 'ru', 'Производитель', ''),
(3, 2, 'uk', 'Застосовується', ''),
(4, 2, 'ru', 'Часть авто', ''),
(5, 3, 'uk', 'Rider', NULL),
(6, 3, 'ru', '', NULL),
(7, 4, 'uk', 'Chery', NULL),
(8, 4, 'ru', '', NULL),
(9, 5, 'uk', 'Konner', NULL),
(10, 5, 'ru', '', NULL),
(11, 6, 'uk', 'Aftermarket', NULL),
(12, 6, 'ru', '', NULL),
(13, 7, 'uk', 'Geely', NULL),
(14, 7, 'ru', '', NULL),
(15, 8, 'uk', 'Fitshi', NULL),
(16, 8, 'ru', '', NULL),
(17, 9, 'uk', 'Kayaba', NULL),
(18, 9, 'ru', '', NULL),
(19, 10, 'uk', 'EEP', NULL),
(20, 10, 'ru', '', NULL),
(21, 11, 'uk', 'Daco', NULL),
(22, 11, 'ru', '', NULL),
(23, 12, 'uk', 'Ajusa', NULL),
(24, 12, 'ru', '', NULL),
(25, 13, 'uk', 'Febi', NULL),
(26, 13, 'ru', 'Febi', NULL),
(27, 14, 'uk', 'Lifan', NULL),
(28, 14, 'ru', '', NULL),
(29, 15, 'uk', 'Cargo', NULL),
(30, 15, 'ru', '', NULL),
(31, 16, 'uk', 'Victor Reinz', NULL),
(32, 16, 'ru', 'Victor Reinz', NULL),
(33, 17, 'uk', 'Erling', NULL),
(34, 17, 'ru', 'Erling', NULL),
(35, 18, 'uk', 'Гальмівна система', NULL),
(36, 18, 'ru', 'Тормозная система', NULL),
(37, 19, 'uk', 'Двигун', NULL),
(38, 19, 'ru', 'Двигатель', NULL),
(39, 20, 'uk', 'Електрообладнання', NULL),
(40, 20, 'ru', 'Електрика', NULL),
(41, 21, 'uk', 'Зчеплення і трансмісія', NULL),
(42, 21, 'ru', 'Сцепление и трансмиссия', NULL),
(43, 22, 'uk', 'Кузов і салон', NULL),
(44, 22, 'ru', 'Кузов и салон', NULL),
(45, 23, 'uk', 'Підвіска та рульове керування', NULL),
(46, 23, 'ru', 'Подвеска та рулевоє управления', NULL),
(47, 24, 'uk', 'Системи двигуна та кондиціонера', NULL),
(48, 24, 'ru', 'Системы двигателя и кондиционер', NULL),
(49, 25, 'uk', 'Фільтра', NULL),
(50, 25, 'ru', 'Фильтра', NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_products`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_1c` tinytext NOT NULL,
  `wl_alias` int(11) NOT NULL,
  `article` text DEFAULT NULL,
  `article_show` text DEFAULT NULL,
  `alias` text DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `price` float unsigned DEFAULT NULL,
  `old_price` float unsigned DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `promo` int(11) DEFAULT NULL,
  `availability` smallint(6) NOT NULL DEFAULT 0,
  `active` tinyint(1) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `author_add` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `author_edit` int(11) NOT NULL,
  `date_edit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `wl_alias` (`wl_alias`),
  KEY `group` (`group`),
  KEY `active` (`active`),
  KEY `position` (`position`),
  FULLTEXT KEY `id_1c` (`id_1c`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_products_similar`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_products_similar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product` (`product`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_product_group`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_product_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product` (`product`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_product_options`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_product_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `option` int(11) NOT NULL,
  `language` varchar(2) DEFAULT '',
  `value` text DEFAULT NULL,
  `changePrice` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `option` (`product`,`option`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `s_shopshowcase_promo`
--

CREATE TABLE IF NOT EXISTS `s_shopshowcase_promo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `percent` float NOT NULL,
  `info` text NOT NULL,
  `date_add` int(11) NOT NULL,
  `date_edit` int(11) NOT NULL,
  `manager_edit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`from`,`to`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `s_static_page`
--

CREATE TABLE IF NOT EXISTS `s_static_page` (
  `id` int(11) NOT NULL,
  `author_add` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `author_edit` int(11) NOT NULL,
  `date_edit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `s_static_page`
--

INSERT INTO `s_static_page` (`id`, `author_add`, `date_add`, `author_edit`, `date_edit`) VALUES
(11, 1, 1593424983, 1, 1593424983),
(12, 1, 1593425297, 1, 1593425297),
(13, 1, 1593425339, 1, 1593425339),
(14, 1, 1593425367, 1, 1593425367);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_aliases`
--

CREATE TABLE IF NOT EXISTS `wl_aliases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` text NOT NULL COMMENT 'основне посилання',
  `service` int(11) DEFAULT 0,
  `table` text DEFAULT NULL,
  `seo_robot` tinyint(1) DEFAULT 0,
  `admin_sidebar` tinyint(1) DEFAULT 0,
  `admin_ico` text DEFAULT NULL,
  `admin_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп даних таблиці `wl_aliases`
--

INSERT INTO `wl_aliases` (`id`, `alias`, `service`, `table`, `seo_robot`, `admin_sidebar`, `admin_ico`, `admin_order`) VALUES
(1, 'main', 0, NULL, 0, 0, 'fa-home', 1),
(2, 'search', 0, NULL, 0, 0, NULL, NULL),
(3, 'profile', 0, NULL, 0, 0, NULL, NULL),
(4, 'login', 0, NULL, 0, 0, NULL, NULL),
(5, 'signup', 0, NULL, 0, 0, NULL, NULL),
(6, 'reset', 0, NULL, 0, 0, NULL, NULL),
(7, 'subscribe', 0, NULL, 0, 0, NULL, NULL),
(8, 'parts', 1, '_8_parts', 0, 0, 'fa-qrcode', 100),
(9, 'currency', 2, '_9_currency', 0, 0, 'fa-line-chart', 10),
(10, 'cart', 3, '_10_cart', 0, 1, 'fa-shopping-cart', 200),
(11, 'manufacturers', 4, '_11_manufacturers', 0, 0, 'fa-newspaper-o', 10),
(12, 'exchange-and-return', 4, '_12_exchange-and-return', 0, 0, 'fa-newspaper-o', 10),
(13, 'delivery-and-payments', 4, '_13_delivery-and-payments', 0, 0, 'fa-newspaper-o', 10),
(14, 'contacts', 4, '_14_contacts', 0, 0, 'fa-newspaper-o', 10);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_aliases_cooperation`
--

CREATE TABLE IF NOT EXISTS `wl_aliases_cooperation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias1` int(11) NOT NULL,
  `alias2` int(11) NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `alias1` (`alias1`),
  KEY `alias2` (`alias2`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп даних таблиці `wl_aliases_cooperation`
--

INSERT INTO `wl_aliases_cooperation` (`id`, `alias1`, `alias2`, `type`) VALUES
(1, 0, 9, '__page_before_init'),
(2, 8, 10, 'cart'),
(3, -1, 10, '__tab_profile'),
(4, 0, 10, 'login'),
(5, -1, 10, '__dashboard_subview');

-- --------------------------------------------------------

--
-- Структура таблиці `wl_audio`
--

CREATE TABLE IF NOT EXISTS `wl_audio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `name` text NOT NULL,
  `text` text NOT NULL,
  `extension` text NOT NULL,
  `author` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`,`content`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_comments`
--

CREATE TABLE IF NOT EXISTS `wl_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `date_add` int(11) NOT NULL,
  `manager` int(11) DEFAULT NULL,
  `date_manage` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`,`content`),
  KEY `user` (`user`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_fields`
--

CREATE TABLE IF NOT EXISTS `wl_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form` int(11) NOT NULL,
  `name` text NOT NULL,
  `position` int(11) DEFAULT 0,
  `input_type` int(11) NOT NULL,
  `required` tinyint(1) DEFAULT 0,
  `title` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_fields_options`
--

CREATE TABLE IF NOT EXISTS `wl_fields_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` int(11) NOT NULL,
  `value` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_files`
--

CREATE TABLE IF NOT EXISTS `wl_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `name` text NOT NULL,
  `text` text NOT NULL,
  `extension` text NOT NULL,
  `author` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`,`content`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_forms`
--

CREATE TABLE IF NOT EXISTS `wl_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sidebar` tinyint(1) NOT NULL,
  `name` text NOT NULL,
  `captcha` tinyint(1) DEFAULT 0,
  `title` text DEFAULT NULL,
  `table` text DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-GET, 2-POST',
  `type_data` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-fields, 2-values',
  `send_mail` tinyint(1) DEFAULT NULL,
  `success` tinyint(1) DEFAULT NULL,
  `success_data` text DEFAULT NULL,
  `send_sms` tinyint(1) NOT NULL DEFAULT 0,
  `sms_text` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_images`
--

CREATE TABLE IF NOT EXISTS `wl_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `file_name` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `author` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `id_1c` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`),
  KEY `content` (`content`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп даних таблиці `wl_images`
--

INSERT INTO `wl_images` (`id`, `alias`, `content`, `file_name`, `title`, `author`, `date_add`, `position`, `id_1c`) VALUES
(1, 8, -1, 'chery-34.png', '', 1, 1594128808, 1, NULL),
(2, 8, -3, 'geely-35.png', '', 1, 1594129568, 1, NULL),
(3, 8, -5, 'byd-33.png', '', 1, 1594128534, 1, NULL),
(4, 8, -7, 'lifan-32.png', '', 1, 1594128498, 1, NULL),
(5, 8, -7, 'lifan-23.png', '', 1, 1593471568, 2, NULL),
(6, 8, -6, 'great-wall-31.png', '', 1, 1594128440, 1, NULL),
(7, 8, -15, 'ec7-21.png', '', 1, 1593471194, 1, NULL),
(8, 8, -25, 'ec7rv-29.png', '', 1, 1593472236, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_images_sizes`
--

CREATE TABLE IF NOT EXISTS `wl_images_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `prefix` tinytext DEFAULT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1 resize, 2 preview',
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `quality` tinyint(2) NOT NULL DEFAULT 100,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп даних таблиці `wl_images_sizes`
--

INSERT INTO `wl_images_sizes` (`id`, `alias`, `active`, `name`, `prefix`, `type`, `width`, `height`, `quality`) VALUES
(1, 0, 1, 'Значення по замовчуванню. Оригінал', NULL, 1, 1500, 1500, 100),
(2, 0, 1, 'Значення по замовчуванню. Панель керування', 'admin', 2, 150, 150, 100),
(3, 0, 1, 'Значення по замовчуванню. Header для соц. мереж', 'header', 2, 600, 315, 100),
(4, 8, 1, 'Відображення у корзині', 'cart', 2, 180, 180, 100),
(5, 8, 1, 'Відображення у каталозі товарів', 'catalog', 1, 265, 265, 100),
(6, 8, 1, 'Товар детально', 'detal', 1, 500, 500, 100),
(7, 8, 1, 'Товар детально міні', 'thumb', 2, 75, 75, 100);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_input_types`
--

CREATE TABLE IF NOT EXISTS `wl_input_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `options` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп даних таблиці `wl_input_types`
--

INSERT INTO `wl_input_types` (`id`, `name`, `options`) VALUES
(1, 'text', 0),
(2, 'number', 0),
(3, 'email', 0),
(4, 'url', 0),
(5, 'date', 0),
(6, 'time', 0),
(7, 'datetime', 0),
(8, 'textarea', 0),
(9, 'radio', 1),
(10, 'select', 1),
(11, 'checkbox', 1),
(12, 'checkbox-select2', 1),
(13, 'file', 0);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_language_values`
--

CREATE TABLE IF NOT EXISTS `wl_language_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` int(11) NOT NULL,
  `language` varchar(2) DEFAULT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Дамп даних таблиці `wl_language_values`
--

INSERT INTO `wl_language_values` (`id`, `word`, `language`, `value`) VALUES
(1, 1, 'uk', NULL),
(2, 1, 'ru', NULL),
(3, 2, 'uk', NULL),
(4, 2, 'ru', NULL),
(5, 3, 'uk', NULL),
(6, 3, 'ru', NULL),
(7, 4, 'uk', NULL),
(8, 4, 'ru', NULL),
(9, 5, 'uk', NULL),
(10, 5, 'ru', NULL),
(11, 6, 'uk', NULL),
(12, 6, 'ru', NULL),
(13, 7, 'uk', NULL),
(14, 7, 'ru', NULL),
(15, 8, 'uk', NULL),
(16, 8, 'ru', NULL),
(17, 9, 'uk', NULL),
(18, 9, 'ru', NULL),
(19, 10, 'uk', NULL),
(20, 10, 'ru', NULL),
(21, 11, 'uk', NULL),
(22, 11, 'ru', NULL),
(23, 12, 'uk', NULL),
(24, 12, 'ru', NULL),
(25, 13, 'uk', NULL),
(26, 13, 'ru', NULL),
(27, 14, 'uk', NULL),
(28, 14, 'ru', NULL),
(29, 15, 'uk', NULL),
(30, 15, 'ru', NULL),
(31, 16, 'uk', NULL),
(32, 16, 'ru', NULL),
(33, 17, 'uk', NULL),
(34, 17, 'ru', NULL),
(35, 18, 'uk', NULL),
(36, 18, 'ru', NULL),
(37, 19, 'uk', NULL),
(38, 19, 'ru', NULL),
(39, 20, 'uk', NULL),
(40, 20, 'ru', NULL),
(41, 21, 'uk', NULL),
(42, 21, 'ru', NULL),
(43, 22, 'uk', NULL),
(44, 22, 'ru', NULL),
(45, 23, 'uk', NULL),
(46, 23, 'ru', NULL),
(47, 24, 'uk', NULL),
(48, 24, 'ru', NULL),
(49, 25, 'uk', NULL),
(50, 25, 'ru', NULL),
(51, 26, 'uk', NULL),
(52, 26, 'ru', NULL),
(53, 27, 'uk', NULL),
(54, 27, 'ru', NULL),
(55, 28, 'uk', NULL),
(56, 28, 'ru', NULL),
(57, 29, 'uk', NULL),
(58, 29, 'ru', NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_language_words`
--

CREATE TABLE IF NOT EXISTS `wl_language_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` text DEFAULT NULL,
  `alias` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Дамп даних таблиці `wl_language_words`
--

INSERT INTO `wl_language_words` (`id`, `word`, `alias`, `type`, `position`) VALUES
(1, 'Увійти', 0, 1, 1),
(2, 'Реєстрація', 0, 1, 2),
(3, 'Швидка реєстрація за допомогою facebook', 4, 1, 1),
(4, 'Ім''я', 5, 1, 1),
(5, 'Прізвище', 5, 1, 2),
(6, 'Контактний телефон', 5, 1, 3),
(7, 'Пароль', 4, 1, 2),
(8, 'Повторіть пароль', 5, 1, 4),
(9, '*пороль має містити від 5 до 20 символів', 5, 1, 5),
(10, 'Зареєструватися', 5, 1, 6),
(11, 'Вхід', 4, 1, 3),
(12, 'або за допомогою email та паролю', 4, 1, 4),
(13, 'Забули пароль?', 4, 1, 5),
(14, 'Вже зареєстровані?', 4, 1, 6),
(15, 'увійти за допомогою email та паролю', 4, 1, 7),
(16, 'Вкажіть свої персональні дані (емейл, телефон, назву компанії) і розпочнімо співпрацю з Dinmark!', 5, 1, 7),
(17, 'Товар у корзині', 0, 1, 3),
(18, 'Продовжити покупки', 0, 1, 4),
(19, 'До корзини', 0, 1, 5),
(20, 'Мої замовлення', 10, 1, 1),
(21, 'Вийти', 0, 1, 6),
(22, 'Кабінет клієнта', 10, 1, 2),
(23, 'Профіль', 10, 1, 3),
(24, 'Редагувати профіль', 10, 1, 4),
(25, 'Замовлення', 10, 1, 5),
(26, 'Статус', 10, 1, 6),
(27, 'Сума', 10, 1, 7),
(28, 'Оплата', 10, 1, 8),
(29, 'Доставка', 10, 1, 9);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_mail_active`
--

CREATE TABLE IF NOT EXISTS `wl_mail_active` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` int(11) NOT NULL,
  `form` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_mail_history`
--

CREATE TABLE IF NOT EXISTS `wl_mail_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `from` text DEFAULT NULL,
  `fromName` text DEFAULT NULL,
  `to` text DEFAULT NULL,
  `replyTo` text DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `attach` text DEFAULT NULL,
  `send_email` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_mail_templates`
--

CREATE TABLE IF NOT EXISTS `wl_mail_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `from` text DEFAULT NULL,
  `to` text DEFAULT NULL,
  `multilanguage` tinyint(1) DEFAULT NULL,
  `savetohistory` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_mail_templats_data`
--

CREATE TABLE IF NOT EXISTS `wl_mail_templats_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` int(11) NOT NULL,
  `language` varchar(2) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `text` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_media_text`
--

CREATE TABLE IF NOT EXISTS `wl_media_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('photo','video') NOT NULL,
  `content` int(11) NOT NULL,
  `language` varchar(2) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content` (`type`,`content`,`language`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_ntkd`
--

CREATE TABLE IF NOT EXISTS `wl_ntkd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `language` enum('uk','ru') NOT NULL DEFAULT 'uk',
  `use_sections` tinyint(1) DEFAULT NULL,
  `get_ivafc` varchar(5) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `text` text DEFAULT NULL,
  `list` text DEFAULT NULL,
  `meta` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `language` (`language`),
  KEY `alias` (`alias`,`content`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=129 ;

--
-- Дамп даних таблиці `wl_ntkd`
--

INSERT INTO `wl_ntkd` (`id`, `alias`, `content`, `language`, `use_sections`, `get_ivafc`, `name`, `title`, `description`, `keywords`, `text`, `list`, `meta`) VALUES
(1, 1, 0, 'uk', NULL, '', 'Запчастини до китайських автомобілів', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 0, 'uk', NULL, '', 'Пошук lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 0, 'uk', NULL, NULL, 'Мій кабінет lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, 0, 'uk', NULL, '', 'Увійти у lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, 0, 'uk', NULL, '', 'Реєстрація lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 6, 0, 'uk', NULL, NULL, 'Відновлення паролю lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, 0, 'uk', NULL, NULL, 'Підписка', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 1, 0, 'ru', NULL, '', 'lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 2, 0, 'ru', NULL, NULL, 'Пошук lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 3, 0, 'ru', NULL, NULL, 'Мій кабінет lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 4, 0, 'ru', NULL, '', 'Увійти у lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 5, 0, 'ru', NULL, NULL, 'Реєстрація lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 6, 0, 'ru', NULL, '', 'Відновлення паролю lifecars.localhost', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 7, 0, 'ru', NULL, NULL, 'Підписка', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 8, 0, 'uk', NULL, '', 'Каталог товарів', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 8, 0, 'ru', NULL, '', 'Каталог товарів', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 9, 0, 'uk', NULL, '', 'Курс валют', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 9, 0, 'ru', NULL, NULL, 'Курс валют', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 8, -1, 'uk', NULL, 'i', 'Chery', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 8, -1, 'ru', NULL, 'i', 'Chery', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 8, -3, 'uk', NULL, 'i', 'Geely', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 8, -3, 'ru', NULL, 'i', 'Geely', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 8, -5, 'uk', NULL, 'i', 'BYD', NULL, NULL, NULL, NULL, NULL, NULL),
(28, 8, -5, 'ru', NULL, 'i', 'BYD', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 8, -6, 'uk', NULL, 'i', 'Great Wall', NULL, NULL, NULL, NULL, NULL, NULL),
(30, 8, -6, 'ru', NULL, 'i', 'Great Wall', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 8, -7, 'uk', NULL, 'i', 'Lifan', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 8, -7, 'ru', NULL, 'i', 'Lifan', NULL, NULL, NULL, NULL, NULL, NULL),
(33, 8, -8, 'uk', NULL, '', 'QQ', NULL, NULL, NULL, NULL, NULL, NULL),
(34, 8, -8, 'ru', NULL, NULL, 'QQ', NULL, NULL, NULL, NULL, NULL, NULL),
(35, 8, -9, 'uk', NULL, '', 'Amulet', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 8, -9, 'ru', NULL, NULL, 'Amulet', NULL, NULL, NULL, NULL, NULL, NULL),
(37, 8, -10, 'uk', NULL, '', 'Kimo', NULL, NULL, NULL, NULL, NULL, NULL),
(38, 8, -10, 'ru', NULL, NULL, 'Kimo', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 8, -11, 'uk', NULL, NULL, 'Jaggi', NULL, NULL, NULL, NULL, NULL, NULL),
(40, 8, -11, 'ru', NULL, NULL, 'Jaggi', NULL, NULL, NULL, NULL, NULL, NULL),
(43, 8, -13, 'uk', NULL, NULL, 'Elara', NULL, NULL, NULL, NULL, NULL, NULL),
(44, 8, -13, 'ru', NULL, NULL, 'Elara', NULL, NULL, NULL, NULL, NULL, NULL),
(45, 8, -14, 'uk', NULL, '', 'CK', NULL, NULL, NULL, NULL, NULL, NULL),
(46, 8, -14, 'ru', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL),
(49, 8, -16, 'uk', NULL, NULL, 'Tiggo', NULL, NULL, NULL, NULL, NULL, NULL),
(50, 8, -16, 'ru', NULL, NULL, 'Tiggo', NULL, NULL, NULL, NULL, NULL, NULL),
(51, 8, -17, 'uk', NULL, '', 'CK2', NULL, NULL, NULL, NULL, NULL, NULL),
(52, 8, -17, 'ru', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL),
(53, 8, -18, 'uk', NULL, '', 'MK-2/MK Cross', NULL, NULL, NULL, NULL, NULL, NULL),
(54, 8, -18, 'ru', NULL, NULL, 'MK-2/MK Cross', NULL, NULL, NULL, NULL, NULL, NULL),
(55, 8, -19, 'uk', NULL, NULL, 'MK/MK New', NULL, NULL, NULL, NULL, NULL, NULL),
(56, 8, -19, 'ru', NULL, NULL, 'MK/MK New', NULL, NULL, NULL, NULL, NULL, NULL),
(57, 8, -20, 'uk', NULL, NULL, 'GS6', NULL, NULL, NULL, NULL, NULL, NULL),
(58, 8, -20, 'ru', NULL, NULL, 'GS6', NULL, NULL, NULL, NULL, NULL, NULL),
(59, 8, -21, 'uk', NULL, NULL, 'LC Cross (GX2)', NULL, NULL, NULL, NULL, NULL, NULL),
(60, 8, -21, 'ru', NULL, NULL, 'LC Cross (GX2)', NULL, NULL, NULL, NULL, NULL, NULL),
(61, 8, -22, 'uk', NULL, NULL, 'LC Panda (GC2)', NULL, NULL, NULL, NULL, NULL, NULL),
(62, 8, -22, 'ru', NULL, NULL, 'LC Panda (GC2)', NULL, NULL, NULL, NULL, NULL, NULL),
(63, 8, -23, 'uk', NULL, NULL, 'Eastar', NULL, NULL, NULL, NULL, NULL, NULL),
(64, 8, -23, 'ru', NULL, NULL, 'Eastar', NULL, NULL, NULL, NULL, NULL, NULL),
(65, 8, -24, 'uk', NULL, NULL, 'MK2', NULL, NULL, NULL, NULL, NULL, NULL),
(66, 8, -24, 'ru', NULL, NULL, 'MK2', NULL, NULL, NULL, NULL, NULL, NULL),
(69, 8, -26, 'uk', NULL, NULL, 'FC', NULL, NULL, NULL, NULL, NULL, NULL),
(70, 8, -26, 'ru', NULL, NULL, 'FC', NULL, NULL, NULL, NULL, NULL, NULL),
(71, 8, -27, 'uk', NULL, NULL, 'Emgrand EC7', NULL, NULL, NULL, NULL, NULL, NULL),
(72, 8, -27, 'ru', NULL, NULL, 'Emgrand EC7', NULL, NULL, NULL, NULL, NULL, NULL),
(73, 8, -28, 'uk', NULL, NULL, 'Emgrand EC7RV', NULL, NULL, NULL, NULL, NULL, NULL),
(74, 8, -28, 'ru', NULL, NULL, 'Emgrand EC7RV', NULL, NULL, NULL, NULL, NULL, NULL),
(75, 8, -29, 'uk', NULL, NULL, 'F3', NULL, NULL, NULL, NULL, NULL, NULL),
(76, 8, -29, 'ru', NULL, NULL, 'F3', NULL, NULL, NULL, NULL, NULL, NULL),
(77, 8, -30, 'uk', NULL, NULL, 'F6', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 8, -30, 'ru', NULL, NULL, 'F6', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 8, -31, 'uk', NULL, '', 'Haval H3', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 8, -31, 'ru', NULL, NULL, 'Haval H3', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 8, -32, 'uk', NULL, NULL, 'Hover', NULL, NULL, NULL, NULL, NULL, NULL),
(82, 8, -32, 'ru', NULL, NULL, 'Hover', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 8, -33, 'uk', NULL, NULL, 'M11', NULL, NULL, NULL, NULL, NULL, NULL),
(84, 8, -33, 'ru', NULL, NULL, 'M11', NULL, NULL, NULL, NULL, NULL, NULL),
(85, 8, -34, 'uk', NULL, NULL, 'Haval H5', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 8, -34, 'ru', NULL, NULL, 'Haval H5', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 8, -35, 'uk', NULL, NULL, '320 Smily', NULL, NULL, NULL, NULL, NULL, NULL),
(88, 8, -35, 'ru', NULL, NULL, '320 Smily', NULL, NULL, NULL, NULL, NULL, NULL),
(89, 8, -36, 'uk', NULL, '', '520 Breez', NULL, NULL, NULL, NULL, NULL, NULL),
(90, 8, -36, 'ru', NULL, NULL, '520 Breez', NULL, NULL, NULL, NULL, NULL, NULL),
(91, 8, -37, 'uk', NULL, NULL, '620 Solano', NULL, NULL, NULL, NULL, NULL, NULL),
(92, 8, -37, 'ru', NULL, NULL, '620 Solano', NULL, NULL, NULL, NULL, NULL, NULL),
(93, 8, -38, 'uk', NULL, NULL, 'E5', NULL, NULL, NULL, NULL, NULL, NULL),
(94, 8, -38, 'ru', NULL, NULL, 'E5', NULL, NULL, NULL, NULL, NULL, NULL),
(95, 8, -39, 'uk', NULL, NULL, 'Beat', NULL, NULL, NULL, NULL, NULL, NULL),
(96, 8, -39, 'ru', NULL, NULL, 'Beat', NULL, NULL, NULL, NULL, NULL, NULL),
(97, 8, -40, 'uk', NULL, NULL, 'X60', NULL, NULL, NULL, NULL, NULL, NULL),
(98, 8, -40, 'ru', NULL, NULL, 'X60', NULL, NULL, NULL, NULL, NULL, NULL),
(99, 8, -41, 'uk', NULL, NULL, 'Arizzo 7', NULL, NULL, NULL, NULL, NULL, NULL),
(100, 8, -41, 'ru', NULL, NULL, 'Arizzo 7', NULL, NULL, NULL, NULL, NULL, NULL),
(101, 8, -42, 'uk', NULL, NULL, 'Tiggo 3', NULL, NULL, NULL, NULL, NULL, NULL),
(102, 8, -42, 'ru', NULL, NULL, 'Tiggo 3', NULL, NULL, NULL, NULL, NULL, NULL),
(103, 8, -43, 'uk', NULL, NULL, 'Tiggo 5', NULL, NULL, NULL, NULL, NULL, NULL),
(104, 8, -43, 'ru', NULL, NULL, 'Tiggo 5', NULL, NULL, NULL, NULL, NULL, NULL),
(105, 8, -44, 'uk', NULL, NULL, 'SL', NULL, NULL, NULL, NULL, NULL, NULL),
(106, 8, -44, 'ru', NULL, NULL, 'SL', NULL, NULL, NULL, NULL, NULL, NULL),
(107, 8, -45, 'uk', NULL, NULL, 'X7', NULL, NULL, NULL, NULL, NULL, NULL),
(108, 8, -45, 'ru', NULL, NULL, 'X7', NULL, NULL, NULL, NULL, NULL, NULL),
(109, 8, -46, 'uk', NULL, NULL, 'Arrizo 3', NULL, NULL, NULL, NULL, NULL, NULL),
(110, 8, -46, 'ru', NULL, NULL, 'Arrizo 3', NULL, NULL, NULL, NULL, NULL, NULL),
(111, 8, -47, 'uk', NULL, NULL, 'Tiggo 2', NULL, NULL, NULL, NULL, NULL, NULL),
(112, 8, -47, 'ru', NULL, NULL, 'Tiggo 2', NULL, NULL, NULL, NULL, NULL, NULL),
(113, 10, 0, 'uk', NULL, '', 'Корзина', NULL, NULL, NULL, NULL, NULL, NULL),
(114, 10, 0, 'ru', NULL, NULL, 'Корзина', NULL, NULL, NULL, NULL, NULL, NULL),
(115, 10, 1, 'uk', NULL, 'af', 'Корзина Оформити замовлення', '', '', '', '', '', ''),
(116, 10, 1, 'ru', NULL, NULL, 'Корзина Оформити замовлення', '', '', '', '', '', ''),
(117, 10, 2, 'uk', NULL, NULL, 'Корзина Оформлення замовлення', '', '', '', '<p>Дякуємо за замовлення. Очікуйте дзвінка менеджера</p>', '', ''),
(118, 10, 2, 'ru', NULL, NULL, 'Корзина Оформлення замовлення', '', '', '', '<p>Дякуємо за замовлення. Очікуйте дзвінка менеджера</p>', '', ''),
(119, 11, 0, 'uk', NULL, '', 'Виробники', NULL, NULL, NULL, NULL, NULL, NULL),
(120, 11, 0, 'ru', NULL, NULL, 'Виробники', NULL, NULL, NULL, NULL, NULL, NULL),
(121, 12, 0, 'uk', NULL, '', 'Повернення та гарантія', NULL, NULL, NULL, NULL, NULL, NULL),
(122, 12, 0, 'ru', NULL, NULL, 'Повернення та гарантія', NULL, NULL, NULL, NULL, NULL, NULL),
(123, 13, 0, 'uk', NULL, '', 'Оплата та доставка', NULL, NULL, NULL, NULL, NULL, NULL),
(124, 13, 0, 'ru', NULL, NULL, 'Оплата та доставка', NULL, NULL, NULL, NULL, NULL, NULL),
(125, 14, 0, 'uk', NULL, '', 'Контакти', NULL, NULL, NULL, NULL, NULL, NULL),
(126, 14, 0, 'ru', NULL, NULL, 'Контакти', NULL, NULL, NULL, NULL, NULL, NULL),
(127, 8, -48, 'uk', NULL, NULL, 'A13 (Zaz Forza)', NULL, NULL, NULL, NULL, NULL, NULL),
(128, 8, -48, 'ru', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_ntkd_robot`
--

CREATE TABLE IF NOT EXISTS `wl_ntkd_robot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `language` varchar(2) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `text` text DEFAULT NULL,
  `list` text DEFAULT NULL,
  `meta` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`),
  KEY `content` (`content`),
  KEY `language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_options`
--

CREATE TABLE IF NOT EXISTS `wl_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service` int(11) NOT NULL,
  `alias` int(11) NOT NULL,
  `name` text NOT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- Дамп даних таблиці `wl_options`
--

INSERT INTO `wl_options` (`id`, `service`, `alias`, `name`, `value`) VALUES
(1, 0, 0, 'sitemap_active', '0'),
(2, 0, 0, 'sitemap_autosent', '0'),
(3, 0, 0, 'sitemap_lastgenerate', '0'),
(4, 0, 0, 'sitemap_lastsent', '0'),
(5, 0, 0, 'sitemap_lastedit', '1594129568'),
(6, 0, 0, 'statictic_set_page', '0'),
(7, 0, 0, 'sitemap_lastedit', '1594129568'),
(8, 0, 0, 'global_MetaTags', ''),
(9, 0, 0, 'showTimeSiteGenerate', '1'),
(10, 0, 0, 'sendEmailForce', '0'),
(11, 0, 0, 'sendEmailSaveHistory', '0'),
(12, 0, 0, 'new_user_type', '4'),
(13, 0, 0, 'showInAdminWl_comments', '0'),
(14, 0, 0, 'paginator_per_page', '20'),
(15, 0, 1, 'folder', 'main'),
(16, 0, 0, 'userSignUp', '1'),
(17, 1, 0, 'ProductUseArticle', '0'),
(18, 1, 0, 'useGroups', '1'),
(19, 1, 0, 'showProductsParentsPages', '1'),
(20, 1, 0, 'ProductMultiGroup', '0'),
(21, 1, 0, 'useAvailability', '0'),
(22, 1, 0, 'searchHistory', '1'),
(23, 1, 0, 'useMarkUp', '0'),
(24, 1, 0, 'folder', 'shopshowcase'),
(25, 1, 0, 'productOrder', 'position DESC'),
(26, 1, 0, 'groupOrder', 'position ASC'),
(27, 1, 0, 'prom', '0'),
(28, 1, 0, 'price_format', ''),
(29, 1, 8, 'ProductUseArticle', '1'),
(30, 1, 8, 'ProductMultiGroup', ''),
(31, 1, 8, 'useAvailability', '1'),
(32, 1, 8, 'searchHistory', '0'),
(33, 1, 8, 'folder', 'parts'),
(34, 1, -8, 'word:products_to_all', 'товарів'),
(35, 1, -8, 'word:product_to', 'До товару'),
(36, 1, -8, 'word:product_to_delete', 'товару'),
(37, 1, -8, 'word:product', 'товар'),
(38, 1, -8, 'word:products', 'товари'),
(39, 1, -8, 'word:product_add', 'Додати товар'),
(40, 1, -8, 'word:options_to_all', 'властивостей'),
(41, 1, -8, 'word:option', 'властивість товару'),
(42, 1, -8, 'word:option_add', 'Додати властивість товару'),
(43, 1, -8, 'sub-menu', 'a:2:{s:5:"alias";s:3:"add";s:4:"name";s:23:"Додати товар";}'),
(44, 1, -8, 'sub-menu', 'a:2:{s:5:"alias";s:3:"all";s:4:"name";s:28:"До всіх товарів";}'),
(45, 1, -8, 'sub-menu', 'a:2:{s:5:"alias";s:6:"groups";s:4:"name";s:10:"Групи";}'),
(46, 1, -8, 'sub-menu', 'a:2:{s:5:"alias";s:7:"options";s:4:"name";s:22:"Властивості";}'),
(47, 1, -8, 'sub-menu', 'a:2:{s:5:"alias";s:5:"promo";s:4:"name";s:10:"Акції";}'),
(48, 2, 0, 'autoUpdate', '1'),
(49, 2, 0, 'saveToHistory', '1'),
(50, 2, 9, 'autoUpdate', '0'),
(51, 2, 9, 'saveToHistory', '0'),
(52, 3, 0, 'useCheckBox', '0'),
(53, 3, 0, 'usePassword', '1'),
(54, 3, 0, 'dogovirOfertiLink', ''),
(55, 3, 10, 'dogovirOfertiLink', 'oferta'),
(56, 3, -10, 'word:products_to_all', 'товарів'),
(57, 3, -10, 'word:product_to', 'До товару'),
(58, 3, -10, 'word:product_to_delete', 'товару'),
(59, 3, -10, 'word:product', 'товар'),
(60, 3, -10, 'word:products', 'товари'),
(61, 4, 0, 'folder', 'static_page'),
(62, 4, 11, 'folder', 'manufacturers'),
(63, 4, 11, 'uniqueDesign', '1'),
(64, 4, 12, 'folder', 'exchange-and-return'),
(65, 4, 13, 'folder', 'delivery-and-payments'),
(66, 4, 14, 'folder', 'contacts'),
(67, 1, 8, 'price_format', 'a:4:{s:6:"before";s:0:"";s:5:"after";s:7:" грн";s:5:"round";s:1:"2";s:5:"penny";s:1:"1";}');

-- --------------------------------------------------------

--
-- Структура таблиці `wl_sections`
--

CREATE TABLE IF NOT EXISTS `wl_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `name` text NOT NULL,
  `global` tinyint(1) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  `value` text NOT NULL,
  `title` text NOT NULL,
  `attr` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`,`content`),
  KEY `global` (`global`),
  KEY `position` (`position`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_services`
--

CREATE TABLE IF NOT EXISTS `wl_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL COMMENT 'службова назва (папки)',
  `title` text NOT NULL COMMENT 'публічна назва',
  `description` text NOT NULL,
  `table` text NOT NULL COMMENT 'службова таблиця',
  `group` tinytext NOT NULL,
  `multi_alias` tinyint(1) NOT NULL,
  `version` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп даних таблиці `wl_services`
--

INSERT INTO `wl_services` (`id`, `name`, `title`, `description`, `table`, `group`, `multi_alias`, `version`) VALUES
(1, 'shopshowcase', 'Магазин', 'Магазин товарів з підтримкою властифостей та фотогалереї. Мультимовна.', 's_shopshowcase', 'shop', 1, '3.2'),
(2, 'currency', 'Курс валют', '', 's_currency', 'currency', 0, '2.3'),
(3, 'cart', 'Корзина', 'Корзина для shopshowcase', 's_cart', 'cart', 0, '2.3'),
(4, 'static_pages', 'Статичні сторінки', '', 's_static_page', 'page', 1, '2.2');

-- --------------------------------------------------------

--
-- Структура таблиці `wl_sitemap`
--

CREATE TABLE IF NOT EXISTS `wl_sitemap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_sha1` char(40) NOT NULL,
  `link` text NOT NULL,
  `alias` smallint(6) DEFAULT NULL,
  `content` int(11) DEFAULT NULL,
  `code` smallint(5) unsigned DEFAULT NULL,
  `data` text DEFAULT NULL,
  `time` int(11) NOT NULL,
  `changefreq` enum('always','hourly','daily','weekly','monthly','yearly','never') NOT NULL DEFAULT 'daily',
  `priority` tinyint(2) NOT NULL DEFAULT 5,
  PRIMARY KEY (`id`),
  UNIQUE KEY `link_sha1` (`link_sha1`),
  KEY `content` (`alias`,`content`) USING BTREE,
  KEY `code` (`code`),
  FULLTEXT KEY `link` (`link`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп даних таблиці `wl_sitemap`
--

INSERT INTO `wl_sitemap` (`id`, `link_sha1`, `link`, `alias`, `content`, `code`, `data`, `time`, `changefreq`, `priority`) VALUES
(1, 'b28b7af69320201d1cf206ebf28373980add1451', 'main', 1, 0, 200, '', 1594476572, 'daily', 5),
(2, 'f7806c7e4b4e5afe304fd77cb3b41ea5a2c99a98', 'favicon.ico', 0, 0, 404, '', 1594476576, 'daily', 5),
(3, '2736fab291f04e69b62d490c3c09361f5b82461a', 'login', 4, 0, 201, '', 1594476578, 'daily', 5);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_sitemap_from`
--

CREATE TABLE IF NOT EXISTS `wl_sitemap_from` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitemap` int(11) NOT NULL,
  `from` text NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sitemap` (`sitemap`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп даних таблиці `wl_sitemap_from`
--

INSERT INTO `wl_sitemap_from` (`id`, `sitemap`, `from`, `date`) VALUES
(1, 2, 'http://life.webspirit.com.ua/', 1594476576);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_statistic_pages`
--

CREATE TABLE IF NOT EXISTS `wl_statistic_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) DEFAULT NULL,
  `content` int(11) DEFAULT NULL,
  `language` varchar(2) DEFAULT NULL,
  `day` int(10) unsigned NOT NULL,
  `unique` int(10) unsigned NOT NULL,
  `views` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`),
  KEY `content` (`content`),
  KEY `language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_statistic_views`
--

CREATE TABLE IF NOT EXISTS `wl_statistic_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` int(10) unsigned NOT NULL,
  `cookie` int(10) unsigned NOT NULL,
  `unique` int(10) unsigned NOT NULL,
  `views` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `day` (`day`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп даних таблиці `wl_statistic_views`
--

INSERT INTO `wl_statistic_views` (`id`, `day`, `cookie`, `unique`, `views`) VALUES
(1, 1593550800, 2, 154, 154),
(2, 1593637200, 2, 57, 57),
(3, 1593723600, 1, 63, 63),
(4, 1593896400, 1, 1, 1),
(5, 1594069200, 4, 410, 410),
(6, 1594155600, 1, 146, 146),
(7, 1594242000, 1, 94, 94),
(8, 1594328400, 1, 55, 55),
(9, 1594414800, 0, 38, 38);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_users`
--

CREATE TABLE IF NOT EXISTS `wl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` text DEFAULT NULL,
  `email` text NOT NULL,
  `name` text DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `type` smallint(2) NOT NULL DEFAULT 4,
  `status` tinyint(1) NOT NULL DEFAULT 2,
  `registered` int(11) DEFAULT 0,
  `last_login` int(11) NOT NULL,
  `auth_id` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `reset_key` text DEFAULT NULL,
  `reset_expires` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп даних таблиці `wl_users`
--

INSERT INTO `wl_users` (`id`, `alias`, `email`, `name`, `photo`, `type`, `status`, `registered`, `last_login`, `auth_id`, `password`, `reset_key`, `reset_expires`) VALUES
(1, 'developer', 'developer@webspirit.com.ua', 'developer', NULL, 1, 1, 1592946247, 1594476581, '4f8e66cefd1de5f5793aaf52dff92d24', '6db31d729169257c4b63485a3e770ccca4c6eea7', NULL, 2),
(2, 'bohdan.kinash', 'bogd.kinash@gmail.com', 'Богдан Кінаш', '', 1, 1, 1594122946, 1594123310, 'ddf9fd6e33207d78bd07a3b7cad0aa1c', '4dec8464b2c91424a7c8c9888b5366f3ea9a7a9c', NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_user_info`
--

CREATE TABLE IF NOT EXISTS `wl_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `field` text NOT NULL,
  `value` text DEFAULT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп даних таблиці `wl_user_info`
--

INSERT INTO `wl_user_info` (`id`, `user`, `field`, `value`, `date`) VALUES
(1, 1, 'phone', '380673141471', 1594122681),
(2, 2, 'phone', '380985756316', 1594122946);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_user_permissions`
--

CREATE TABLE IF NOT EXISTS `wl_user_permissions` (
  `user` int(11) NOT NULL,
  `permission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблиці `wl_user_register`
--

CREATE TABLE IF NOT EXISTS `wl_user_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `do` tinyint(4) NOT NULL,
  `user` int(11) NOT NULL,
  `additionally` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Дамп даних таблиці `wl_user_register`
--

INSERT INTO `wl_user_register` (`id`, `date`, `do`, `user`, `additionally`) VALUES
(1, 1592946247, 1, 1, NULL),
(2, 1592946438, 13, 1, '1. shopshowcase (3.2)'),
(3, 1592946490, 11, 1, 'parts (8)'),
(4, 1592946504, 13, 1, '2. currency (2.3)'),
(5, 1592946509, 11, 1, 'currency (9)'),
(6, 1593424893, 13, 1, '3. cart (2.3)'),
(7, 1593424905, 11, 1, 'cart (10)'),
(8, 1593424921, 13, 1, '4. static_pages (2.2)'),
(9, 1593424983, 11, 1, 'manufacturers (11)'),
(10, 1593425297, 11, 1, 'exchange-and-return (12)'),
(11, 1593425339, 11, 1, 'delivery-and-payments (13)'),
(12, 1593425367, 11, 1, 'contacts (14)'),
(13, 1593557439, 6, 1, 'User IP: 46.119.183.1'),
(14, 1594122946, 1, 2, ''),
(15, 1594122976, 2, 2, ''),
(16, 1594122979, 2, 2, ''),
(17, 1594123269, 2, 2, ''),
(18, 1594123279, 2, 2, ''),
(19, 1594123310, 2, 2, ''),
(20, 1594124065, 15, 1, 'Входив до #2. Богдан Кінаш'),
(21, 1594124065, 15, 2, '1. developer'),
(22, 1594124112, 7, 2, 'user: 1. developer, old type: 4, new type: 1'),
(23, 1594148928, 6, 2, 'User IP: 134.249.116.214'),
(24, 1594148935, 6, 2, 'User IP: 134.249.116.214');

-- --------------------------------------------------------

--
-- Структура таблиці `wl_user_register_do`
--

CREATE TABLE IF NOT EXISTS `wl_user_register_do` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `public` tinyint(1) NOT NULL,
  `title` text NOT NULL,
  `title_public` text NOT NULL,
  `help_additionall` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Дамп даних таблиці `wl_user_register_do`
--

INSERT INTO `wl_user_register_do` (`id`, `name`, `public`, `title`, `title_public`, `help_additionall`) VALUES
(1, 'signup', 1, 'Реєстрація нового користувача', 'Реєстрація користувача', ''),
(2, 'confirmed', 1, 'Підтвердження реєстрації користувача', 'Підтвердження реєстрації', ''),
(3, 'reset_sent', 0, 'Відновлення паролю. Вислано повідомлення із кодом відновлення.', '', ''),
(4, 'reset', 1, 'Відновлення паролю. Пароль змінено. Старий пароль у полі Додатково.', 'Зміна паролю користувачем', 'Попередній пароль у sha1'),
(5, 'profile_data', 0, 'Змінено особисті дані', '', 'field(id) - ід поля, value(text) - попередні дані'),
(6, 'login_bad', 0, 'Невірна спроба авторизації з ІР', '', 'ІР адреса'),
(7, 'profile_type', 1, 'Зміна типу користувача', 'Зміна типу користувача', 'user(id) - хто змінив, old_type(id) - попередній тип'),
(8, 'subscribe', 0, 'Підписався на оновлення', '', ''),
(9, 'reset_admin', 1, 'Відновлення паролю. Пароль змінено. Старий пароль у полі Додатково.', 'Зміна паролю адміністрацією', 'Зміна паролю адміністрацією. Пароль змінено. Старий пароль у полі Додатково.'),
(10, 'user_delete', 0, 'Видалив профіль користувача', 'Видалив профіль користувача', 'Id. Email. User name. Type. Date register'),
(11, 'alias_add', 0, 'Додано головну адресу', 'Додано головну адресу', 'Адреса посилання'),
(12, 'alias_delete', 0, 'Видалена головна адреса', 'Видалена головна адреса', 'Ід. Адреса. Сервіс.'),
(13, 'service_install', 0, 'Install service', 'Install service', 'Id. Service name (version)'),
(14, 'service_uninstall', 0, 'Uninstall service', 'Uninstall service', 'Id. Service name (version)'),
(15, 'login_as_user', 0, 'Вхід до профілю через панель керування', '', 'Хто зайшов'),
(16, 'logout_as_user', 0, 'Вихід з профілю через панель керування', '', 'Хто вийшов');

-- --------------------------------------------------------

--
-- Структура таблиці `wl_user_status`
--

CREATE TABLE IF NOT EXISTS `wl_user_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `next` int(11) NOT NULL,
  `load` text NOT NULL,
  `color` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп даних таблиці `wl_user_status`
--

INSERT INTO `wl_user_status` (`id`, `name`, `title`, `next`, `load`, `color`) VALUES
(1, 'confirmed', 'Підтверджений', 0, 'profile', 'success'),
(2, 'registered', 'Новозареєстрований', 1, 'login/confirmed', 'warning'),
(3, 'banned', 'Заблокований', 0, '', 'danger'),
(4, 'create-1', 'Створити профіль 1: контактна інформація', 5, 'profile/create/step-1', 'default'),
(5, 'create-2', 'Створити профіль 2: персональна інформація', 6, 'profile/create/step-2', 'default'),
(6, 'create-3', 'Створити профіль 3: професійна інформація', 1, 'profile/create/step-3', 'default');

-- --------------------------------------------------------

--
-- Структура таблиці `wl_user_types`
--

CREATE TABLE IF NOT EXISTS `wl_user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп даних таблиці `wl_user_types`
--

INSERT INTO `wl_user_types` (`id`, `name`, `title`, `active`) VALUES
(1, 'admin', 'Адміністратор', 1),
(2, 'manager', 'Менеджер', 1),
(3, 'reserved', 'Резерв', 1),
(4, 'single', 'Користувач', 1),
(5, 'subscribe', 'Підписник', 1);

-- --------------------------------------------------------

--
-- Структура таблиці `wl_video`
--

CREATE TABLE IF NOT EXISTS `wl_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `site` text DEFAULT NULL COMMENT 'youtube, vimeo',
  `link` text DEFAULT NULL,
  `active` int(1) DEFAULT 1 COMMENT '0 - видалене',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
