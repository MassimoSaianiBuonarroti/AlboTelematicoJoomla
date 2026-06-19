-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Giu 17, 2026 alle 15:01
-- Versione del server: 10.11.18-MariaDB-cll-lve-log
-- Versione PHP: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gvgtcyej_zvzg1`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `dppn_albo_categorie`
--

CREATE TABLE `dppn_albo_categorie` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT 1,
  `ordering` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `dppn_albo_categorie`
--

INSERT INTO `dppn_albo_categorie` (`id`, `title`, `state`, `ordering`) VALUES
(2, 'Determinazione Dirigente 2025', 1, 2),
(3, 'Nomine Personale ATA', 1, 1),
(5, 'Delibere Consiglio dell\'Istituzione 2025', 1, 3),
(6, 'Graduatorie', 1, 0),
(7, 'Determinazioni Dirigente 2026', 1, 0),
(8, 'Delibere Consiglio dell\'Istituzione 2026', 1, 0),
(9, 'Pubblicazioni PNRR', 1, 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `dppn_albo_categorie`
--
ALTER TABLE `dppn_albo_categorie`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `dppn_albo_categorie`
--
ALTER TABLE `dppn_albo_categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
