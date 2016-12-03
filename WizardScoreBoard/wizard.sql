
CREATE TABLE `game` (
  `Game_ID` int(11) NOT NULL AUTO_INCREMENT,
  `AmountPlayers` int(11) DEFAULT NULL,
   PRIMARY KEY (`Game_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
--
-- Tabelstructuur voor tabel `players`
--
CREATE TABLE `players` (
  `Player_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Game` int(11) DEFAULT NULL,
  PRIMARY KEY (`Player_ID`),
  FOREIGN KEY (Game) REFERENCES Game(Game_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

