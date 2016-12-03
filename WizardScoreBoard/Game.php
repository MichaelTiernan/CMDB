<?php
require_once 'ScoreBoard.php';
require_once 'Database.php';
require_once 'Collection.php';
Class Game extends Database{
	private $Rounds;
	private $amountOfPlayers = 0;
	private $count = 0;
	public function __construct(){
		$this->count++;
	}
	
	public function setAmountOfPlayers($amountPlayers){
		print "Game: This is the ".$this->count." occourence<br>";
// 		$pdo = $this::connect();
// 		$SQL = "insert into game (AmountPlayers) values (:amount)";
// 		$q = $pdo->prepare($SQL);
// 		$q->bindParam(':amount',$amountPlayers);
// 		if ($q->execute()){
// 			$UUIDQ = "Select game_id from game order by game_id desc limit 1";
// 			$stmnt = $pdo->prepare($UUIDQ);
// 			$stmnt->execute();
// 			$row = $stmnt->fetch(PDO::FETCH_ASSOC);
// 			return $row["game_id"];
// 		}
// 		$this::disconnect();
		print "Game: The amount of players will be set to: ".$amountPlayers."<br>";
		$this->amountOfPlayers = $amountPlayers;
	}
	
	public function getAmountOfPlayers(){
		print "Game: This is the ".$this->count." occourence<br>";
// 		$pdo = $this::connect();
// 		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 		$SQL = "Select AmountPlayers from game where game_id = :id";
// 		$q = $pdo->prepare($SQL);
// 		$q->bindParam(':id',$Game_ID);
// 		if ($q->execute()){
// 			$row = $q->fetch(PDO::FETCH_ASSOC);
// 			return $row["AmountPlayers"];
// 		}else{
// 			return 0;
// 		}
// 		$this::disconnect();
		print "Game: The amount of players: ".$this->amountOfPlayers."<br>";
		return $this->amountOfPlayers;
	}
	
	public function Play3Players($Game_ID,$player1, $player2, $player3){
		$pdo = $this::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//Player1
		$SQL = "Insert into players (Name,Game) values (:player,:game)";
		$q = $pdo->prepare($SQL);
		$q->bindParam(':player',$player1);
		$q->bindParam(':game',$Game_ID);
		$q->execute();
		//Player2
		$SQL = "Insert into players (Name,Game) values (:player,:game)";
		$q = $pdo->prepare($SQL);
		$q->bindParam(':player',$player2);
		$q->bindParam(':game',$Game_ID);
		$q->execute();
		//Player3
		$SQL = "Insert into players (Name,Game) values (:player,:game)";
		$q = $pdo->prepare($SQL);
		$q->bindParam(':player',$player3);
		$q->bindParam(':game',$Game_ID);
		$q->execute();
		$this::disconnect();
	}
	
	public function Play4Players($Game_ID,$player1, $player2, $player3,$player4){
		$this->Play3Players($Game_ID,$player1, $player2, $player3);
		$pdo = $this::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//Player4
		$SQL = "Insert into players (Name,Game) values (:player,:game)";
		$q = $pdo->prepare($SQL);
		$q->bindParam(':player',$player4);
		$q->bindParam(':game',$Game_ID);
		$q->execute();
		$this::disconnect();
	}
	
	public function Play5Players($Game_ID,$player1,$player2,$player3,$player4,$player5){
		$this->Play4Players($Game_ID,$player1, $player2, $player3,$player4);
		$pdo = $this::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//Player5
		$SQL = "Insert into players (Name,Game) values (:player,:game)";
		$q = $pdo->prepare($SQL);
		$q->bindParam(':player',$player5);
		$q->bindParam(':game',$Game_ID);
		$q->execute();
		$this::disconnect();
	}
	
	public function Play6Players($Game_ID,$player1,$player2,$player3,$player4,$player5,$player6){
		$this->Play5Players($Game_ID,$player1, $player2, $player3,$player4,$player5);
		$pdo = $this::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//Player6
		$SQL = "Insert into players (Name,Game) values (:player,:game)";
		$q = $pdo->prepare($SQL);
		$q->bindParam(':player',$player6);
		$q->bindParam(':game',$Game_ID);
		$q->execute();
		$this::disconnect();
	}
	
	public function getPlayers($Game_ID){
		$pdo = $this::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$SQL = "Select Name from players where Game = :id";
		$q = $pdo->prepare($SQL);
		$q->bindParam(':id',$Game_ID);
		if ($q->execute()){
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	public function setRound1($results){
		foreach ($results as $result){
			$scoreboard = new Scoreboard();
			$scoreboard->setPlayersName($result['PlayersName']);
			$scoreboard->predaction($result['Required']);
			$score= $scoreboard->result($result['Received']);
			$result['Score'] = $score;
		}
		return $results;
	}
	
	public function isThisTheLastRound($curentRound,$amountOfPlayers){
		print "<br>The amount of players ".$amountOfPlayers."<br>";
		print "<br>We are playing in round ".$curentRound."<br>";
		switch ($amountOfPlayers){
			case 3:
				$this->Rounds = 20;
				break;
			case 4:
				$this->Rounds = 15;
				break;
			case 5:
				$this->Rounds = 12;
				break;
			case 6:
				$this->Rounds = 10;
				break;
		}
		print "The amount of rounds to play ".$this->Rounds."<br>";
		if ($curentRound == $this->Rounds){
			print "This is the last round!<br>";
			return TRUE;
		} else {
			print "This is not the last round, happy playing<br>";
			return FALSE;
		}
	}
}