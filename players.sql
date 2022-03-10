-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost
-- Vytvořeno: Čtv 10. bře 2022, 23:35
-- Verze serveru: 10.4.22-MariaDB
-- Verze PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `chessclub`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `players`
--

CREATE TABLE `players` (
  `player_id` bigint(20) UNSIGNED NOT NULL,
  `nickname` varchar(60) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `player_email` varchar(255) NOT NULL,
  `player_phone` varchar(255) NOT NULL,
  `date_joined` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `players`
--

INSERT INTO `players` (`player_id`, `nickname`, `player_name`, `player_email`, `player_phone`, `date_joined`) VALUES
(3, 'beard', 'Ria Beard', 'varius.et@protonmail.net', '225 375 196', '2017-06-15'),
(2, 'mueller', 'Noel Mueller', 'arcu.vestibulum@google.edu', '885 133 104', '2019-11-04'),
(1, 'duncan', 'Yvette Duncan', 'dui.in.sodales@outlook.edu', '123456789', '2012-12-15'),
(4, 'carver', 'Brady Carver', 'eget.volutpat.ornare@yahoo.net', '377 247 733', '2016-09-25'),
(5, 'gibson', 'Sydnee Gibson', 'lorem@icloud.edu', '406 676 217', '2010-01-01'),
(6, 'freeman', 'Deanna Freeman', 'duis@outlook.org', '696 862 157', '2012-02-26'),
(7, 'moore', 'Sydney Moore', 'ullamcorper.magna.sed@aol.net', '663 481 147', '2018-06-10'),
(8, 'sherman', 'Jolie Sherman', 'ac.tellus.suspendisse@google.ca', '571 941 526', '2018-04-03'),
(9, 'cooley', 'Eaton Cooley', 'duis.gravida.praesent@yahoo.couk', '445 627 395', '2012-10-23'),
(10, 'mcfarland', 'Libby Mcfarland', 'integer.eu@aol.org', '182 044 238', '2020-12-12'),
(11, 'schwartz', 'Ferdinand Schwartz', 'ipsum@google.edu', '788 570 695', '2017-07-12'),
(12, 'robinson', 'Garth Robinson', 'maecenas@icloud.couk', '935 124 201', '2020-08-07'),
(13, 'grimes', 'Inga Grimes', 'lacus.varius@protonmail.ca', '614 852 127', '2017-09-08'),
(14, 'pitts', 'Chaney Pitts', 'risus.nunc@icloud.couk', '186 255 274', '2019-07-06'),
(15, 'dennis', 'Thane Dennis', 'luctus.ut.pellentesque@hotmail.net', '418 767 668', '2020-09-04');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`player_id`),
  ADD UNIQUE KEY `player_id` (`player_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `players`
--
ALTER TABLE `players`
  MODIFY `player_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
