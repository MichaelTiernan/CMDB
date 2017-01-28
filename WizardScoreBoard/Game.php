<?php
require_once 'ScoreBoard.php';
require_once 'Database.php';
require_once 'Collection.php';
Class Game extends Database{
	private $Rounds;
	private $amountOfPlayers = 0;
	private $count = 0;
	private $ScoreboardCollaction;
	public function __construct(){
		$this->count++;
		$this->ScoreboardCollaction = new Collection();
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
	
	public function Play3Players($player1, $player2, $player3){
		$scoreboard1 = new Scoreboard();
		$scoreboard1->setPlayersName($player1);
		$this->ScoreboardCollaction->addItem($scoreboard1, 1);
		$scoreboard2 = new Scoreboard();
		$scoreboard2->setPlayersName($player2);
		$this->ScoreboardCollaction->addItem($scoreboard2, 2);
		$scoreboard3 = new Scoreboard();
		$scoreboard3->setPlayersName($player3);
		$this->ScoreboardCollaction->addItem($scoreboard3, 3);
		print "Amount of keys after Play3Players <br>";
		print_r($this->ScoreboardCollaction->keys());
		return;
	}
	
	public function Play4Players($player1, $player2, $player3,$player4){
		$this->Play3Players($player1, $player2, $player3);
		$scoreboard4 = new Scoreboard();
		$scoreboard4->setPlayersName($player4);
		$this->ScoreboardCollaction->addItem($scoreboard4, 4);
	}
	
	public function Play5Players($player1,$player2,$player3,$player4,$player5){
		$this->Play4Players($player1, $player2, $player3,$player4);
		$scoreboard5 = new Scoreboard();
		$scoreboard5->setPlayersName($player5);
		$this->ScoreboardCollaction->addItem($scoreboard4, 5);
	}
	
	public function Play6Players($player1,$player2,$player3,$player4,$player5,$player6){
		$this->Play5Players($player1, $player2, $player3,$player4,$player5);
		$scoreboard6 = new Scoreboard();
		$scoreboard6->setPlayersName($player6);
		$this->ScoreboardCollaction->addItem($scoreboard6, 6);
	}
	
	public function getPlayers($AmountOfPlayers){
		$this->ScoreboardCollaction->length();
		print_r($this->ScoreboardCollaction->keys());
// 		$result = array();
// 		for ($i=1; $i<=$AmountOfPlayers; $i++){
// 			$scoreboard = $this->ScoreboardCollaction->getItem($i);
// 			$result[] = array("Name" => $scoreboard->getPlayersName());
// 		}
// 		return $result;
	}
	
	public function setRound1($results){
		foreach ($results as $result){
			print_r($results);
// 			$scoreboard = new Scoreboard();
// 			$scoreboard->setPlayersName($result['PlayersName']);
// 			$scoreboard->predaction($result['Required']);
// 			$score= $scoreboard->result($result['Received']);
// 			$result['Score'] = $score;
		}
		//return $results;
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