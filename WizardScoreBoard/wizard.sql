
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

CREATE TABLE `round` (
  `Round_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Round` int(11) DEFAULT NULL,
  `Player` int(11) DEFAULT NULL,
  `Required` int(11) DEFAULT NULL,
  `Received` int(11) DEFAULT NULL,
  `Score` int(11) DEFAULT NULL,
  PRIMARY KEY (`Round_ID`),
 FOREIGN KEY (Player) REFERENCES players(Player_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;