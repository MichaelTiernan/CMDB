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
	/**
	 * This function will set the amount of players
	 * @param int $amountPlayers
	 */
	public function setAmountOfPlayers($amountPlayers){
		$myfile = fopen("AmountPlayers.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $amountPlayers);
		fclose($myfile);
		$this->amountOfPlayers = $amountPlayers;
	}
	/**
	 * This function will return the amount of players
	 * @return int
	 */
	public function getAmountOfPlayers(){
		$myfile = fopen("AmountPlayers.txt", "r") or die("Unable to open file!");
		$Line= fread($myfile,filesize("AmountPlayers.txt"));
		fclose($myfile);
		$this->amountOfPlayers = $Line;
		return $this->amountOfPlayers;
	}
	
	public function Play3Players($player1, $player2, $player3){
		$myfile = fopen("Player1.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $player1);
		fclose($myfile);
		$myfile = fopen("Player2.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $player2);
		fclose($myfile);
		$myfile = fopen("Player3.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $player3);
		fclose($myfile);
	}
	
	public function Play4Players($player1, $player2, $player3,$player4){
		$this->Play3Players($player1, $player2, $player3);
		$myfile = fopen("Player4.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $player4);
		fclose($myfile);
	}
	
	public function Play5Players($player1,$player2,$player3,$player4,$player5){
		$this->Play4Players($player1, $player2, $player3,$player4);
		$myfile = fopen("Player5.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $player5);
		fclose($myfile);
	}
	
	public function Play6Players($player1,$player2,$player3,$player4,$player5,$player6){
		$this->Play5Players($player1, $player2, $player3,$player4,$player5);
		$myfile = fopen("Player6.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $player6);
		fclose($myfile);
	}
	
	public function getPlayers($AmountOfPlayers){
		$result = array();
		for ($i = 1; $i <= $AmountOfPlayers; $i++){
			$myfile = fopen("Player".$i.".txt", "r") or die("Unable to open file!");
			$Line= fgets($myfile);
			fclose($myfile);
			$result[] = array("Name" => $Line);
		}
		return $result;
	}
	
	public function setRound1($results){
		foreach ($results as $result){
			$scoreboard = new Scoreboard();
			$scoreboard->setPlayersName($result['PlayersName']);
			$scoreboard->predaction($result['Required']);
			$score= $scoreboard->result($result['Received']);
			$result['Score'] = $score;
			$myfile = fopen("Player".$result[ID].".txt", "w") or die("Unable to open file!");
			fwrite($myfile, $result['PlayersName']."\n");
			fwrite($myfile, "Required =>".$result['Required']."\n");
			fwrite($myfile, "Received =>".$result['Received']."\n");
			fwrite($myfile, "Score =>".$score);
			fclose($myfile);
		}
	}
	
	public function getRound1($AmountOfPlayers){
		$Result = array();
		for ($i = 1; $i <= $AmountOfPlayers; $i++){
			$myfile = fopen("Player".$i.".txt", "r") or die("Unable to open file!");	
			$Required = 0;
			$Received = 0;
			$Score = 0;
			while(!feof($myfile)) {
				$line = fgets($myfile);
				if (strstr($line, "Required")){
					$Required = substr($line,11);
				}elseif (strstr($line, "Received")){
					$Received = substr($line, 11);
				}elseif (strstr($line, "Score")){
					$Score = substr($line, 8);
				}
			}
			fclose($myfile);
			$Result[]= array("ID" => $i, "Required" => $Required,"Received" => $Received, "Score" => $Score);
		}
		return $Result;
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