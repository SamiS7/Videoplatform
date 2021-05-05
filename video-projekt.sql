-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 08. Apr 2021 um 21:02
-- Server-Version: 10.4.14-MariaDB
-- PHP-Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `video-projekt`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `channels`
--

CREATE TABLE `channels` (
  `id` int(11) NOT NULL,
  `channel` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `channels`
--

INSERT INTO `channels` (`id`, `channel`) VALUES
(72657, 'Luna'),
(77112, 'Luna'),
(54592, 'sami'),
(77112, 'Sami');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `video` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `konto`
--

CREATE TABLE `konto` (
  `name` varchar(25) NOT NULL,
  `bDay` date NOT NULL,
  `country` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `creatingDate` date NOT NULL,
  `abos` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `konto`
--

INSERT INTO `konto` (`name`, `bDay`, `country`, `password`, `creatingDate`, `abos`, `id`, `email`) VALUES
('Sami', '2021-03-10', 'Afghanistan', 'f551fa19a42af59762ff96451ce286d1', '2021-03-29', 2, 77112, 'sami@gmx.net'),
('Luna', '1999-05-05', 'America', 'c502c7c92b52f26a0eba13cb071fccb6', '2021-03-30', 2, 72657, 'Luna@gmail.no'),
('Jon', '2021-03-29', 'England', 'f551fa19a42af59762ff96451ce286d1', '2021-04-01', 0, 54592, 'jon@gmail.com');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `likedvideos`
--

CREATE TABLE `likedvideos` (
  `id` int(11) NOT NULL,
  `video` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `videoinfos`
--

CREATE TABLE `videoinfos` (
  `name` varchar(50) NOT NULL,
  `id` varchar(30) NOT NULL,
  `owner` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `catag` varchar(100) NOT NULL,
  `tags` varchar(100) NOT NULL,
  `poster` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `video` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
