-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 12 Kwi 2020, 21:01
-- Wersja serwera: 10.3.16-MariaDB
-- Wersja PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `forum_new`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dostepnosc`
--

CREATE TABLE `dostepnosc` (
  `id_tematu` int(11) DEFAULT NULL,
  `grupa` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `dostepnosc`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `id_kategorii` int(11) NOT NULL,
  `nazwa` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `dostep` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `opis` varchar(200) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `kategorie`
--

INSERT INTO `kategorie` (`id_kategorii`, `nazwa`, `dostep`, `opis`) VALUES
(1, 'Informatyka', "wszyscy", "Opis kategorii Opis kategorii Opis kategorii"),
(2, 'Matematyka', "wszyscy", "Lorem ipsum dolor sit amet, consectetur adipiscing elit."),
(3, 'Inne', "wszyscy", "Donec ultricies sapien sed semper vehicula."),
(4, 'Wiadomości od użytkowników', "administratorzy", "Wiadomości od uzytkowników - tylko dla administratorów"),
(5, 'Kategoria dla nauczycieli', "nauczyciele", "Opis"),
(6, 'Kategoria dla uczniów', "uczniowie", "");

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klasy`
--

CREATE TABLE `klasy` (
  `id_klasy` int(11) NOT NULL,
  `nazwa` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `klasy`
--

INSERT INTO `klasy` (`id_klasy`, `nazwa`) VALUES
(1, '1aL5'),
(2, '1bL5'),
(3, '1cL5'),
(4, '1dL5'),
(5, '1aL4'),
(6, '1bL4'),
(7, '1cL4'),
(8, '2aTL'),
(9, '2bTL'),
(10, '2cTL'),
(11, '2dTL'),
(12, '3aTL'),
(13, '3bTL'),
(14, '3cTL'),
(15, '3dTL'),
(16, '4aTL'),
(17, '4bTL'),
(18, '4cTL'),
(19, '1aI5'),
(20, '1bI5'),
(21, '1aI4'),
(22, '1bI4'),
(23, '2aTI'),
(24, '2bTI'),
(25, '2cTI'),
(26, '3aTI'),
(27, '3bTI'),
(28, '4aTI'),
(29, '4bTI'),
(30, '1E5'),
(31, '1E4'),
(32, '2TE'),
(33, '3TE'),
(34, '4TE'),
(35, '1M5'),
(36, '2TM'),
(37, '3TM'),
(38, '3TMP'),
(39, '4TM'),
(40, '1B4'),
(41, '1M4'),
(42, '1BG5'),
(43, '2TB'),
(44, '3TB'),
(45, '4TB'),
(46, '1G4'),
(47, '4TRC'),
(48, '1RM5'),
(49, '1RM4'),
(50, '2TMR 2(TMA)'),
(51, '3TMA 3TMR'),
(52, '4TMH'),
(53, '2TOR'),
(54, '3TOR'),
(55, '4TOR'),
(56, '1H5'),
(57, '1H4'),
(58, '2THo'),
(59, '1Ż5'),
(60, '1Ż4'),
(61, '2TŻA'),
(62, '3TŻ'),
(63, '4TŻ'),
(64, '1b'),
(65, '1c'),
(66, '1d'),
(67, '1e'),
(68, '1g'),
(69, '1bBS'),
(70, '1cBS'),
(71, '1dBS'),
(72, '1eBS'),
(73, '1gBS'),
(74, '2b 2bBS'),
(75, '2c 2cBS'),
(76, '2d 2dBS'),
(77, '3b 3bBS'),
(78, '3c 3cBS'),
(79, '3d 3dBS'),
(80, '3e 3eBS'),
(81, '1a'),
(82, '1f'),
(83, '1aBS'),
(84, '1fBS'),
(85, '2a 2aBS'),
(86, '2e 2eBS'),
(87, '2f 2fBS'),
(88, '3a 3aBS'),
(89, '3f 3fBS'),
(90, '8SPD'),
(91, 'absolwenci');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `nauczyciele_i_klasy`
--

CREATE TABLE `nauczyciele_i_klasy` (
  `nauczyciel_id` int(11) DEFAULT NULL,
  `klasa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `nauczyciele_i_klasy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posty`
--

CREATE TABLE `posty` (
  `id_posta` int(11) NOT NULL,
  `tresc` text COLLATE utf8_polish_ci NOT NULL,
  `data_publikacji` datetime NOT NULL,
  `temat_id` int(11) NOT NULL,
  `uzytkownik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `posty`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tematy`
--

CREATE TABLE `tematy` (
  `id_tematu` int(11) NOT NULL,
  `nazwa` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `data_utworzenia` datetime NOT NULL,
  `kategoria_id` int(11) NOT NULL,
  `uzytkownik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `tematy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uczniowie_i_klasy`
--

CREATE TABLE `uczniowie_i_klasy` (
  `uczen_id` int(11) DEFAULT NULL,
  `klasa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `uczniowie_i_klasy`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id_uzytkownika` int(11) NOT NULL,
  `login` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `imie` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `plec` varchar(1) COLLATE utf8_polish_ci NOT NULL,
  `czy_aktywne` tinyint(1) DEFAULT NULL,
  `typ_konta` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `data_zalozenia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id_uzytkownika`, `login`, `haslo`, `email`, `imie`, `nazwisko`, `plec`, `czy_aktywne`, `typ_konta`, `data_zalozenia`) VALUES
(-1, 'konto_usuniete', '', '', '', '', 'm', 1, NULL, NULL),
(1, 'admin', '$2y$10$dOqxmIcPCVC6RcX7MMJIVukOd3d7W18dDF//xe3kA88Vyl/.fTmbi', 'admin@wp.pl', 'Administrator', 'Administrator', 'm', 1, 'administrator', '2020-02-14');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zalaczniki`
--

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id_kategorii`);

--
-- Indeksy dla tabeli `klasy`
--
ALTER TABLE `klasy`
  ADD PRIMARY KEY (`id_klasy`);

--
-- Indeksy dla tabeli `nauczyciele_i_klasy`
--
ALTER TABLE `nauczyciele_i_klasy`
  ADD KEY `nauczyciel_id` (`nauczyciel_id`),
  ADD KEY `klasa_id` (`klasa_id`);

--
-- Indeksy dla tabeli `posty`
--
ALTER TABLE `posty`
  ADD PRIMARY KEY (`id_posta`),
  ADD KEY `temat_id` (`temat_id`),
  ADD KEY `uzytkownik_id` (`uzytkownik_id`);

--
-- Indeksy dla tabeli `tematy`
--
ALTER TABLE `tematy`
  ADD PRIMARY KEY (`id_tematu`),
  ADD KEY `kategoria_id` (`kategoria_id`),
  ADD KEY `uzytkownik_id` (`uzytkownik_id`);

--
-- Indeksy dla tabeli `uczniowie_i_klasy`
--
ALTER TABLE `uczniowie_i_klasy`
  ADD KEY `uczen_id` (`uczen_id`),
  ADD KEY `klasa_id` (`klasa_id`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id_uzytkownika`);

--
-- Indeksy dla tabeli `zalaczniki`
--

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id_kategorii` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `klasy`
--
ALTER TABLE `klasy`
  MODIFY `id_klasy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT dla tabeli `posty`
--
ALTER TABLE `posty`
  MODIFY `id_posta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT dla tabeli `tematy`
--
ALTER TABLE `tematy`
  MODIFY `id_tematu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id_uzytkownika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `nauczyciele_i_klasy`
--
ALTER TABLE `nauczyciele_i_klasy`
  ADD CONSTRAINT `nauczyciele_i_klasy_ibfk_1` FOREIGN KEY (`nauczyciel_id`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `nauczyciele_i_klasy_ibfk_2` FOREIGN KEY (`klasa_id`) REFERENCES `klasy` (`id_klasy`);

--
-- Ograniczenia dla tabeli `posty`
--
ALTER TABLE `posty`
  ADD CONSTRAINT `posty_ibfk_1` FOREIGN KEY (`temat_id`) REFERENCES `tematy` (`id_tematu`),
  ADD CONSTRAINT `posty_ibfk_2` FOREIGN KEY (`uzytkownik_id`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

--
-- Ograniczenia dla tabeli `tematy`
--
ALTER TABLE `tematy`
  ADD CONSTRAINT `tematy_ibfk_1` FOREIGN KEY (`kategoria_id`) REFERENCES `kategorie` (`id_kategorii`),
  ADD CONSTRAINT `tematy_ibfk_2` FOREIGN KEY (`uzytkownik_id`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

--
-- Ograniczenia dla tabeli `uczniowie_i_klasy`
--
ALTER TABLE `uczniowie_i_klasy`
  ADD CONSTRAINT `uczniowie_i_klasy_ibfk_1` FOREIGN KEY (`uczen_id`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `uczniowie_i_klasy_ibfk_2` FOREIGN KEY (`klasa_id`) REFERENCES `klasy` (`id_klasy`);

--
-- Ograniczenia dla tabeli `zalaczniki`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
