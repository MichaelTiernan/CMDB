<?php
require_once 'Game.php';
require_once 'ValidationException.php';
Class GameService{
	private $game;
	private $count = 0;
	public function __construct(){
		$this->game = new Game();
	}
	/**
	 * This function will set the amount of players
	 * @param int $Players
	 * @throws ValidationException
	 */
	public function setAmountOfPlayers($Players){
		try{
			$this->validateTypeParams($Players);
			return $this->game->setAmountOfPlayers($Players);
		}catch (ValidationException $ex){
			throw $ex;
		}
	}
	
	public function getAmountOfPlayers($Game_ID){
		return $this->game->getAmountOfPlayers($Game_ID);	
	}
	/**
	 * this function will be used when playing with 3 players
	 * @param string $player1
	 * @param string $player2
	 * @param string $player3
	 * @throws ValidationException
	 * @throws PDOException
	 */
	public function Play3Players($Game_ID,$player1,$player2,$player3){
		print "Let's play with 3<br>";
		try{
			$this->validate3Players($player1, $player2, $player3);
// 			Print "WHY <br>";
			$this->game->Play3Players($Game_ID,$player1, $player2, $player3);
		}catch (ValidationException $ex){
			throw $ex;
		}catch (PDOException $e){
			throw $e;
		}
	}	
	/**
	 * 
	 * @param string $player1
	 * @param string $player2
	 * @param string $player3
	 * @param string $player4
	 * @throws ValidationException
	 */
	public function Play4Players($Game_ID,$player1,$player2,$player3,$player4){
		try{
			$this->validate4Players($player1, $player2, $player3,$player4);
			$this->game->Play4Players($Game_ID,$player1, $player2, $player3,$player4);
		}catch (ValidationException $ex){
			throw $ex;
		}catch (ValidationException $ex){
			throw $ex;
		}
	}
	
	public function Play5Players($Game_ID,$player1,$player2,$player3,$player4,$player5){
		try{
			$this->validate5Players($player1, $player2, $player3,$player4,$player5);
			$this->game->Play5Players($Game_ID,$player1, $player2, $player3,$player4,$player5);
		}catch (ValidationException $ex){
			throw $ex;
		}catch (ValidationException $ex){
			throw $ex;
		}
	}
	
	public function Play6Players($Game_ID,$player1,$player2,$player3,$player4,$player5,$player6){
		try{
			$this->validate6Players($player1, $player2, $player3,$player4,$player5,$player6);
			$this->game->Play6Players($Game_ID,$player1, $player2, $player3,$player4,$player5,$player6);
		}catch (ValidationException $ex){
			throw $ex;
		}catch (ValidationException $ex){
			throw $ex;
		}
	}
	
	public function isThisTheLastRound($curentRound,$amountOfPlayers){
		return $this->game->isThisTheLastRound($curentRound, $amountOfPlayers);
	}
	
	public function getPlayers($Game_ID){
		return $this->game->getPlayers($Game_ID);
	}
	
	public function setResultRound($Game_ID,$round, $results){
		$this->game->setResultRound($Game_ID,$round, $results);
	}
		
	public function getResultRound($Game_ID,$Round){
		return $this->game->getResultRound($Game_ID,$Round);
	}
	
	public function getAmountofRounds($amountOfPlayers){
		return $this->game->getAmountofRounds($amountOfPlayers);
	}
	
	private function validate3Players($player1,$player2,$player3){
		$errors = array();
		if (empty($player1)) {
			$errors[] = 'Please enter a name for player 1';
		}
		if (empty($player2)) {
			$errors[] = 'Please enter a name for player 2';
		}
		if (empty($player3)) {
			$errors[] = 'Please enter a name for player 3';
		}
		if ( empty($errors) ) {
			return true;
		}
		throw new ValidationException($errors);
	}
	
	private function validate4Players($player1,$player2,$player3,$player4){
		$errors = array();
		if (empty($player1)) {
			$errors[] = 'Please enter a name for player 1';
		}
		if (empty($player2)) {
			$errors[] = 'Please enter a name for player 2';
		}
		if (empty($player3)) {
			$errors[] = 'Please enter a name for player 3';
		}
		if (empty($player4)) {
			$errors[] = 'Please enter a name for player 4';
		}
		if ( empty($errors) ) {
			return true;
		}
		throw new ValidationException($errors);
	}
	
	private function validate5Players($player1,$player2,$player3,$player4,$player5){
		$errors = array();
		if (empty($player1)) {
			$errors[] = 'Please enter a name for player 1';
		}
		if (empty($player2)) {
			$errors[] = 'Please enter a name for player 2';
		}
		if (empty($player3)) {
			$errors[] = 'Please enter a name for player 3';
		}
		if (empty($player4)) {
			$errors[] = 'Please enter a name for player 4';
		}
		if (empty($player5)) {
			$errors[] = 'Please enter a name for player 5';
		}
		if ( empty($errors) ) {
			return true;
		}
		throw new ValidationException($errors);
	}
	
	private function validate6Players($player1,$player2,$player3,$player4,$player5,$player6){
		$errors = array();
		if (empty($player1)) {
			$errors[] = 'Please enter a name for player 1';
		}
		if (empty($player2)) {
			$errors[] = 'Please enter a name for player 2';
		}
		if (empty($player3)) {
			$errors[] = 'Please enter a name for player 3';
		}
		if (empty($player4)) {
			$errors[] = 'Please enter a name for player 4';
		}
		if (empty($player5)) {
			$errors[] = 'Please enter a name for player 5';
		}
		if (empty($player6)) {
			$errors[] = 'Please enter a name for player 6';
		}
		if ( empty($errors) ) {
			return true;
		}
		throw new ValidationException($errors);
	}

	private function validateTypeParams($Players){
		$errors = array();
		if (empty($Players)) {
			$errors[] = 'Please enter an amount of players';
		}
		if ($Players < 3 or $Players > 6){
			$errors[] = 'The amount of players can only be between 3 and 6';
		}
		if ( empty($errors) ) {
			return true;
		}
	
		throw new ValidationException($errors);
	}
}