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
		$this->ScoreboardCollaction = new Collection();
	}
	/**
	 * This function will set the amount of players
	 * @param int $amountPlayers
	 * @return int the Id of the Game
	 */
	public function setAmountOfPlayers($amountPlayers){
		$pdo = $this::connect();
  		$SQL = "insert into game (AmountPlayers) values (:amount)";
  		$q = $pdo->prepare($SQL);
  		$q->bindParam(':amount',$amountPlayers);
  		if ($q->execute()){
  			$UUIDQ = "Select game_id from game order by game_id desc limit 1";
  			$stmnt = $pdo->prepare($UUIDQ);
  			$stmnt->execute();
  			$row = $stmnt->fetch(PDO::FETCH_ASSOC);
  			return $row["game_id"];
  		}
  		$this::disconnect();
	}
	/**
	 * This function will return the amount of players
	 * @return int the amount of players
	 */
	public function getAmountOfPlayers($Game_ID){
		$pdo = $this::connect();
  		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  		$SQL = "Select AmountPlayers from game where game_id = :id";
  		$q = $pdo->prepare($SQL);
  		$q->bindParam(':id',$Game_ID);
  		if ($q->execute()){
  			$row = $q->fetch(PDO::FETCH_ASSOC);
  			return $row["AmountPlayers"];
  		}else{
  			return 0;
 		}
	}
	/**
	 * This function will set names of the players
	 * @param int $Game_ID
	 * @param string $player1
	 * @param string $player2
	 * @param string $player3
	 */
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
	/**
	 * 
	 * @param unknown $Game_ID
	 * @param string $player1
	 * @param string $player2
	 * @param string $player3
	 * @param string $player4
	 */
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
	/**
	 * 
	 * @param int $Game_ID
	 * @param string $player1
	 * @param string $player2
	 * @param string $player3
	 * @param string $player4
	 * @param string $player5
	 */
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
	/**
	 * 
	 * @param int $Game_ID
	 * @param string $player1
	 * @param string $player2
	 * @param string $player3
	 * @param string $player4
	 * @param string $player5
	 * @param string $player6
	 */
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
	/**
	 * Return the names of the players
	 * @param unknown $Game_ID
	 */
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
	/**
	 * This function will set The result of a round
	 * @param int $Game_ID The Unique ID of THe Game
	 * @param int $round The number of round we are in
	 * @param array $results
	 */
	public function setResultRound($Game_ID, $round, $results){
		foreach ($results as $result){
			$scoreboard = new Scoreboard();
			$scoreboard->setPlayersName($result['PlayersName']);
			$scoreboard->predaction($result['Required']);
			$scoreboard->setScore($result['Score']);
			$score= $scoreboard->result($result['Received']);
			$result['Score'] = $score;
			$PlayerSQL = "Select Player_ID from players where Name = :playername and Game = :id";
			$pdo = $this::connect();
			$q = $pdo->prepare($PlayerSQL);
			$q->bindParam(':playername',$result['PlayersName']);
			$q->bindParam(':id',$Game_ID);
			if ($q->execute()){
				$row = $q->fetch(PDO::FETCH_ASSOC);
				$player= $row["Player_ID"];
			}
			$sql = "Insert into round (Round, Player, Required, Received, Score) values (:round,:player,:required, :received, :score)";
			$q = $pdo->prepare($sql);
			$q->bindParam(':round',$round);
			$q->bindParam(':player',$player);
			$q->bindParam(':required',$result['Required']);
			$q->bindParam(':received',$result['Received']);
			$q->bindParam(':score',$score);
			$q->execute();
		}
	}
	/**
	 * This function will return the results of a round
	 * @param int $Game_ID The unique ID of the Game
	 * @param int $round The indication of the round we are in
	 */
	public function getResultRound($Game_ID,$round){
// 		$Result = array();
		$pdo = $this::connect();
		$sql = "Select Required, Received, Score from round r " 
				."join players p on r.player = p.Player_ID "
				."where p.Game = :id and r.Round = :round";
		$q = $pdo->prepare($sql);
		$q->bindParam(':id',$Game_ID);
		$q->bindParam(':round',$round);
// 		$Result[]= array("Required" => $Required,"Received" => $Received, "Score" => $Score);
		if ($q->execute()){
 			return $q->fetchAll(PDO::FETCH_ASSOC);
 		}
	}
	/**
	 * This function will check if a current round is the last one or not
	 * @param int $curentRound
	 * @param int $amountOfPlayers
	 * @return boolean
	 */
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
	/**
	 * This will return the amount of rounds
	 * @param number $amountOfPlayers
	 * @return number
	 */
	public function getAmountofRounds($amountOfPlayers){
		switch ($amountOfPlayers){
			case 3:
				return 20;
				break;
			case 4:
				return 15;
				break;
			case 5:
				return 12;
				break;
			case 6:
				return 10;
				break;
		}
	}
}