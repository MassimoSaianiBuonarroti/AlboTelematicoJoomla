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
-- Struttura della tabella `dppn_albo_atti`
--

CREATE TABLE `dppn_albo_atti` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `document_number` varchar(50) NOT NULL,
  `albo_number` int(11) NOT NULL DEFAULT 0,
  `albo_year` int(4) NOT NULL DEFAULT 0,
  `document_date` date NOT NULL,
  `publish_start` date NOT NULL,
  `publish_end` date NOT NULL,
  `category` int(11) NOT NULL DEFAULT 0,
  `state` tinyint(1) NOT NULL DEFAULT 1,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `file` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `dppn_albo_atti`
--
ALTER TABLE `dppn_albo_atti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_albo_year_number` (`albo_year`,`albo_number`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `dppn_albo_atti`
--
ALTER TABLE `dppn_albo_atti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1032;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
