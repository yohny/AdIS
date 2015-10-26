-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 30, 2012 at 10:40 PM
-- Server version: 5.1.58
-- PHP Version: 5.3.6-13ubuntu3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `adis`
--

--
-- Dumping data for table `kategorie`
--

INSERT INTO `kategorie` (`id`, `nazov`) VALUES
(1, 'Stavba, dom, záhrada'),
(2, 'Oblečenie a módne doplnky'),
(3, 'Šport'),
(4, 'Zábava a voľný čas'),
(5, 'Kozmetika a kaderníctvo'),
(6, 'Domáce potreby'),
(7, 'Zvieratá'),
(8, 'Výpočtová technika'),
(9, 'Služby'),
(10, 'Literatúra a tlač'),
(11, 'Doprava a cestovanie'),
(12, 'Priemysel'),
(13, 'Jedlo a stravovanie'),
(14, 'Servis'),
(15, 'Vzdelávanie'),
(16, 'Kultúra a umenie'),
(17, 'Kancelárske potreby'),
(18, 'Veda a technika'),
(19, 'Financie a trh'),
(20, 'Ekológia'),
(21, 'Spoločnosť');

--
-- Dumping data for table `velkosti`
--

INSERT INTO `velkosti` (`id`, `sirka`, `vyska`, `nazov`) VALUES
(1, 300, 250, 'Medium Rectangle'),
(2, 250, 250, 'Square Pop-Up'),
(3, 240, 400, 'Vertical Rectangle'),
(4, 336, 280, 'Large Rectangle'),
(5, 180, 150, 'Rectangle'),
(6, 300, 100, '3:1 Rectangle'),
(7, 720, 300, 'Pop-Under'),
(8, 468, 60, 'Full banner'),
(9, 234, 60, 'Half banner'),
(10, 88, 31, 'Micro bar'),
(11, 120, 90, 'Button 1'),
(12, 120, 60, 'Button 2'),
(13, 120, 240, 'Vertical banner'),
(14, 125, 125, 'Square button'),
(15, 728, 90, 'Leaderboard'),
(16, 160, 600, 'Wide skyscraper'),
(17, 120, 600, 'Skyscraper'),
(18, 300, 600, 'Half page ad'),
(19, 200, 200, 'adis');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
