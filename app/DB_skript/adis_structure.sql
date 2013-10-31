-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 03, 2012 at 09:58 PM
-- Server version: 5.1.58
-- PHP Version: 5.3.6-13ubuntu3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `adis`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_days`()
LABEL: BEGIN
declare datum date default null;
select max(day) + interval 1 day from days into datum;
if (datum is null) then
 select min(date(registered)) from users into datum;
end if;
if (datum is null) then
  LEAVE LABEL;
end if;
while (datum<=now()) do
 insert into days values (datum);
 set datum = datum + interval 1 day;
end while;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bannery`
--

CREATE TABLE IF NOT EXISTS `bannery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `velkost` int(11) NOT NULL,
  `path` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk` (`user`),
  KEY `velkost_fk` (`velkost`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=22 ;

--
-- RELATIONS FOR TABLE `bannery`:
--   `user`
--       `users` -> `id`
--   `velkost`
--       `velkosti` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE IF NOT EXISTS `days` (
  `day` date NOT NULL,
  PRIMARY KEY (`day`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci COMMENT='event generated days';

-- --------------------------------------------------------

--
-- Table structure for table `kategoria_banner`
--

CREATE TABLE IF NOT EXISTS `kategoria_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategoria` int(11) NOT NULL,
  `banner` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kategoria_fk` (`kategoria`),
  KEY `banner_fk` (`banner`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=91 ;

--
-- RELATIONS FOR TABLE `kategoria_banner`:
--   `banner`
--       `bannery` -> `id`
--   `kategoria`
--       `kategorie` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategoria_reklama`
--

CREATE TABLE IF NOT EXISTS `kategoria_reklama` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategoria` int(11) NOT NULL,
  `reklama` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kategoria_fk2` (`kategoria`),
  KEY `reklama_fk` (`reklama`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=28 ;

--
-- RELATIONS FOR TABLE `kategoria_reklama`:
--   `kategoria`
--       `kategorie` -> `id`
--   `reklama`
--       `reklamy` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategorie`
--

CREATE TABLE IF NOT EXISTS `kategorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazov` varchar(100) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `kliky`
--

CREATE TABLE IF NOT EXISTS `kliky` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cas` datetime NOT NULL,
  `zobra` int(11) NOT NULL,
  `inzer` int(11) NOT NULL,
  `reklama` int(11) NOT NULL,
  `banner` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=234 ;

-- --------------------------------------------------------

--
-- Table structure for table `reklamy`
--

CREATE TABLE IF NOT EXISTS `reklamy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `velkost` int(11) NOT NULL,
  `meno` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk2` (`user`),
  KEY `velkost_fk2` (`velkost`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=6 ;

--
-- RELATIONS FOR TABLE `reklamy`:
--   `user`
--       `users` -> `id`
--   `velkost`
--       `velkosti` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(10) COLLATE utf8_slovak_ci NOT NULL,
  `heslo` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  `web` varchar(30) COLLATE utf8_slovak_ci NOT NULL,
  `kategoria` enum('inzer','zobra','admin') COLLATE utf8_slovak_ci NOT NULL,
  `registered` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `web` (`web`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `velkosti`
--

CREATE TABLE IF NOT EXISTS `velkosti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sirka` int(3) NOT NULL,
  `vyska` int(3) NOT NULL,
  `nazov` varchar(20) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `zobrazenia`
--

CREATE TABLE IF NOT EXISTS `zobrazenia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cas` datetime NOT NULL,
  `zobra` int(11) NOT NULL,
  `inzer` int(11) NOT NULL,
  `reklama` int(11) NOT NULL,
  `banner` int(11) NOT NULL,
  `clicked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4498 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bannery`
--
ALTER TABLE `bannery`
  ADD CONSTRAINT `user_fk` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `velkost_fk` FOREIGN KEY (`velkost`) REFERENCES `velkosti` (`id`);

--
-- Constraints for table `kategoria_banner`
--
ALTER TABLE `kategoria_banner`
  ADD CONSTRAINT `banner_fk` FOREIGN KEY (`banner`) REFERENCES `bannery` (`id`),
  ADD CONSTRAINT `kategoria_fk` FOREIGN KEY (`kategoria`) REFERENCES `kategorie` (`id`);

--
-- Constraints for table `kategoria_reklama`
--
ALTER TABLE `kategoria_reklama`
  ADD CONSTRAINT `kategoria_fk2` FOREIGN KEY (`kategoria`) REFERENCES `kategorie` (`id`),
  ADD CONSTRAINT `reklama_fk` FOREIGN KEY (`reklama`) REFERENCES `reklamy` (`id`);

--
-- Constraints for table `reklamy`
--
ALTER TABLE `reklamy`
  ADD CONSTRAINT `user_fk2` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `velkost_fk2` FOREIGN KEY (`velkost`) REFERENCES `velkosti` (`id`);

DELIMITER $$
--
-- Events
--
CREATE EVENT `days_generator` ON SCHEDULE EVERY 1 DAY STARTS '2012-01-01 00:00:01' ON COMPLETION PRESERVE ENABLE COMMENT 'Event denne generujuci dni do tabulky days' DO INSERT INTO days VALUES (NOW())$$

DELIMITER ;
