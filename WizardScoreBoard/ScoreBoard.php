<?php
require_once 'CalCulator.PHP';
class Scoreboard {
	private $calc;
	private $PlayersName;
	private $predation ;
	private $score = 0;
	
	public function __construct(){
		$this->calc = new Calculator();
	}
	
	public function getPlayersName() {
		return PlayersName;
	}
	
	public function setPlayersName($playersName) {
		$this->PlayersName = $playersName;
	}
	
	public function predaction($amount){
		$this->predation = $amount;
	}
	
	public function result($amout){
		$this->score = $this->score + $this->calc.calculate($this->predation, $amout);
		return $this->score;
	}
}