-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 03. Jun 2021 um 23:21
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
  `follower` varchar(50) NOT NULL,
  `channel` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `channels`
--

INSERT INTO `channels` (`follower`, `channel`) VALUES
('79704', '77112'),
('79704', '142296'),
('79704', '331425'),
('104200', '79704'),
('103800', '103800'),
('128502', '128502'),
('132616', '132616'),
('132616', '54592');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dailydata`
--

CREATE TABLE `dailydata` (
  `video` int(15) NOT NULL,
  `likes` int(11) DEFAULT 0,
  `clicks` int(15) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `dailydata`
--

INSERT INTO `dailydata` (`video`, `likes`, `clicks`) VALUES
(42, 0, 0),
(44, 1, 0),
(45, 3, 0),
(46, 1, 0),
(47, 1, 0),
(48, 0, 0),
(49, 0, 0),
(50, 1, 0),
(51, 0, 0),
(52, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dailydatadate`
--

CREATE TABLE `dailydatadate` (
  `lastdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `dailydatadate`
--

INSERT INTO `dailydatadate` (`lastdate`) VALUES
('2021-06-03 11:29:35');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `history`
--

CREATE TABLE `history` (
  `id` varchar(50) NOT NULL,
  `video` int(15) NOT NULL
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
  `id` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `konto`
--

INSERT INTO `konto` (`name`, `bDay`, `country`, `password`, `creatingDate`, `abos`, `id`, `email`) VALUES
('sami3', '2021-06-02', 'österreich', 'f551fa19a42af59762ff96451ce286d1', '2021-06-03', 0, '103500', 'sami@gmx.com'),
('sami5', '2021-05-31', 'Österreich', 'f551fa19a42af59762ff96451ce286d1', '2021-06-03', 0, '103700', 'sami@gmx.at'),
('Sami8', '2021-05-31', 'Österreich', 'f551fa19a42af59762ff96451ce286d1', '2021-06-03', 1, '103800', 'sami@gmx.com'),
('Najib', '2021-05-11', 'Italy', 'f551fa19a42af59762ff96451ce286d1', '2021-05-24', 0, '104200', 'Najib@gmail.co'),
('Yonus', '2021-05-05', 'Afghanistan', 'f551fa19a42af59762ff96451ce286d1', '2021-05-23', 0, '110000', 'yonus@gangsullah.com'),
('Sami66', '2021-06-01', 'Österreich', 'f551fa19a42af59762ff96451ce286d1', '2021-06-03', 1, '128502', 'sami@gmx.com'),
('Sami889', '2021-05-31', 'Österreich', 'f551fa19a42af59762ff96451ce286d1', '2021-06-03', 1, '132616', 'sami@gmail.at'),
('Julien Bam', '2021-05-05', 'Germany', 'f551fa19a42af59762ff96451ce286d1', '2021-05-23', 0, '141449', 'julian@mail.com'),
('Aurora', '2021-05-03', 'Norway', 'f551fa19a42af59762ff96451ce286d1', '2021-05-23', 1, '142296', 'aurora@gmx.at'),
('Ju Bam', '2021-05-13', 'Deutschland', 'f551fa19a42af59762ff96451ce286d1', '2021-05-23', 1, '331425', 'ju@gmx.at'),
('Jon', '2021-03-29', 'England', 'f551fa19a42af59762ff96451ce286d1', '2021-04-01', 1, '54592', 'jon@gmail.com'),
('Max Mustermann', '2021-04-07', 'Österreich', 'f551fa19a42af59762ff96451ce286d1', '2021-04-30', 0, '703950', 'max@gmail.com'),
('Luna', '1999-05-05', 'America', 'c502c7c92b52f26a0eba13cb071fccb6', '2021-03-30', 2, '72657', 'Luna@gmail.no'),
('Bart', '2021-05-04', 'America', 'f551fa19a42af59762ff96451ce286d1', '2021-05-23', 0, '77031', 'bart@gmail.com'),
('Sami2', '2021-03-10', 'Afghanistan', 'f551fa19a42af59762ff96451ce286d1', '2021-03-29', 7, '77112', 'sami@gmx.net'),
('Anya Taylor-Joy', '2021-05-12', 'Argentinien', 'f551fa19a42af59762ff96451ce286d1', '2021-05-23', 0, '787600', 'Anya@gmail.org'),
('sami', '2021-05-04', 'kljlkj', 'f551fa19a42af59762ff96451ce286d1', '2021-05-07', 1, '79704', 'lkjlkjlkö');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `likedvideos`
--

CREATE TABLE `likedvideos` (
  `id` varchar(50) NOT NULL,
  `video` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `likedvideos`
--

INSERT INTO `likedvideos` (`id`, `video`) VALUES
('54592', 45),
('54592', 46),
('77112', 47),
('77112', 45),
('132616', 50),
('132616', 44),
('132616', 45);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `profilandcoverpic`
--

CREATE TABLE `profilandcoverpic` (
  `id` varchar(50) NOT NULL,
  `pName` varchar(100) NOT NULL,
  `cName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `profilandcoverpic`
--

INSERT INTO `profilandcoverpic` (`id`, `pName`, `cName`) VALUES
('77112', 'SamiProfImg.jpg', 'SamiCoverImg.jpg'),
('79704', 'samiProfImg.jpg', 'samiCoverImg.jpg'),
('77031', 'BartProfImg.jpg', 'BartCoverImg.jpg'),
('142296', 'AuroraProfImg.jpg', 'AuroraCoverImg.jpg'),
('787600', 'Anya Taylor-JoyCoverImg.png', ''),
('141449', 'JulianCoverImg.png', 'Julien BamCoverImg.jpg'),
('331425', 'Julian BamCoverImg.png', ''),
('104200', 'NajibProfImg.png', 'standart.jpg'),
('54592', 'JonProfImg.jpg', ''),
('103500', 'sami3ProfImg.png', 'standart.jpg'),
('103700', 'sami5ProfImg.png', 'standart.jpg'),
('103800', 'sami6ProfImg.png', 'standart.jpg'),
('128502', 'Sami77ProfImg.png', 'standart.jpg'),
('132616', 'sami88ProfImg.png', 'standart.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `videos`
--

CREATE TABLE `videos` (
  `name` varchar(50) NOT NULL,
  `id` int(15) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `catag` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`catag`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`tags`)),
  `poster` varchar(40) NOT NULL,
  `uploadDate` datetime NOT NULL,
  `clicks` int(15) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `videos`
--

INSERT INTO `videos` (`name`, `id`, `owner`, `title`, `likes`, `catag`, `tags`, `poster`, `uploadDate`, `clicks`) VALUES
('10.mp4', 42, '79704', 'Ozean', 0, '[\"ozean\", \"wasser\", \"relaxing\"]', '[\"ozean\", \"ocean\", \"wasser\", \"water\"]', '10.jpg', '2021-06-03 17:51:15', 0),
('11.mp4', 44, '79704', 'Blätter', 1, '[\"blatt\", \"natur\", \"sonne\"]', '[\"blatt\", \"blätter\", \"sun\", \"sunny\"]', '11.jpg', '2021-06-03 17:55:27', 0),
('12.mp4', 45, '54592', 'Meeseeks Battle', 3, '[\"short film\", \"cartoon\", \"game\", \"battle\"]', '[\"rickAndMorty\", \"cartoon\", \"meeseeks\", \"game\", \"battle\"]', '12.jpg', '2021-06-03 18:04:53', 0),
('13.mp4', 46, '104200', 'EPIC MIRROR DASH', 1, '[\"unterhaltung\", \"stop motion\", \"mirror challange\"]', '[\"ju\", \"julien bam\", \"mirror challange\"]', '13.jpg', '2021-06-03 18:24:04', 0),
('14.mp4', 47, '104200', 'WandaVision Mid-Season Trailer', 1, '[\"serie\", \"tv show\", \"film\", \"mcu\"]', '[\"marvel\", \"mcu\", \"wanda\", \"vision\", \"wandavision\"]', '14.jpg', '2021-06-03 20:30:09', 0),
('15.mp4', 48, '103800', 'Mirros Dash', 0, '[\"challange\", \"unterhaltung\"]', '[\"Ju\", \"Julien bam\", \"youtube\"]', '15.jpg', '2021-06-03 22:56:40', 0),
('16.mp4', 49, '128502', 'Mirror Dash', 0, '[\"challange\", \"unterhaltung\"]', '[\"ju\", \"julien bam\"]', '16.jpg', '2021-06-03 23:01:36', 0),
('17.mp4', 50, '128502', 'Mirror Dash', 1, '[\"challange\", \"unterhaltung\"]', '[\"ju\", \"julien bam\", \"mirror dash\"]', '17.jpg', '2021-06-03 23:05:16', 0),
('18.mp4', 52, '132616', 'Mirror Dash', 0, '[\"unterhaltung\", \"challange\"]', '[\"ju\", \"julien bam\"]', '18.jpg', '2021-06-03 23:12:24', 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `dailydata`
--
ALTER TABLE `dailydata`
  ADD PRIMARY KEY (`video`);

--
-- Indizes für die Tabelle `konto`
--
ALTER TABLE `konto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `poster` (`poster`),
  ADD KEY `owner` (`owner`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `konto` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
