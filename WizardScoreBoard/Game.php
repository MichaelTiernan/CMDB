<?php
require_once 'ScoreBoard.php';
require_once 'Collection.php';

Class Game{
	private $amountPlayers;
	private $ScorebordCollection;
	private $Rounds;
	public function __construct(){
		$this->ScorebordCollection = new Collection();
	}
	
	public function setAmountOfPlayers($amountPlayers){
		$this->amountPlayers = $amountPlayers;
	}
	
	public function Play3Players($player1, $player2, $player3){
		$this->ScorebordCollection->addItem(new Scoreboard(),1);
		$scoreboard = $this->ScorebordCollection->getItem(1);
		$scoreboard->setPlayersName($player1);
		$this->ScorebordCollection->addItem(new Scoreboard(),2);
		$scoreboard = $this->ScorebordCollection->getItem(1);
		$scoreboard->setPlayersName($player2);
		$this->ScorebordCollection->addItem(new Scoreboard(),3);
		$scoreboard = $this->ScorebordCollection->getItem(1);
		$scoreboard->setPlayersName($player3);
		$this->Rounds = 20;
	}
	
	public function Play4Players($player1, $player2, $player3,$player4){
		$this->Play3Players($player1, $player2, $player3);
		$this->ScorebordCollection->addItem(new Scoreboard(),4);
		$scoreboard = $this->ScorebordCollection->getItem(1);
		$scoreboard->setPlayersName($player4);
		$this->Rounds = 15;
	}
	
	public function Play5Players($player1,$player2,$player3,$player4,$player5){
		$this->Play4Players($player1, $player2, $player3,$player4);
		$this->ScorebordCollection->addItem(new Scoreboard(),5);
		$scoreboard = $this->ScorebordCollection->getItem(1);
		$scoreboard->setPlayersName($player5);
		$this->Rounds = 12;
	}
	
	public function Play6Players($player1,$player2,$player3,$player4,$player5,$player6){
		$this->Play5Players($player1, $player2, $player3,$player4,$player5);
		$this->ScorebordCollection->addItem(new Scoreboard(),6);
		$scoreboard = $this->ScorebordCollection->getItem(1);
		$scoreboard->setPlayersName($player6);
		$this->Rounds = 10;
	}
	
	public function getRounds(){
		return $this->Rounds;
	}
}