<?php
require_once 'Game.php';
require_once 'ValidationException.php';
Class GameService{
	private $game;
	public function __construct(){
		$this->game = new Game();
	}
	
	public function setAmountOfPlayers($Players){
		try{
			$this->validateTypeParams($Players);
			$this->game->setAmountOfPlayers($Players);
		}catch (ValidationException $ex){
			throw $ex;
		}
	}
	
	public function Play3Players($player1,$player2,$player3){
		try{
			$this->validate3Players($player1, $player2, $player3);
			$this->game->Play3Players($player1, $player2, $player3);
		}catch (ValidationException $ex){
			throw $ex;
		}
	}
	
	public function Play4Players($player1,$player2,$player3,$player4){
		try{
			$this->validate4Players($player1, $player2, $player3,$player4);
			$this->game->Play4Players($player1, $player2, $player3,$player4);
		}catch (ValidationException $ex){
			throw $ex;
		}
	}
	
	public function Play5Players($player1,$player2,$player3,$player4,$player5){
		try{
			$this->validate5Players($player1, $player2, $player3,$player4,$player5);
			$this->game->Play5Players($player1, $player2, $player3,$player4,$player5);
		}catch (ValidationException $ex){
			throw $ex;
		}
	}
	
	public function Play6Players($player1,$player2,$player3,$player4,$player5,$player6){
		try{
			$this->validate6Players($player1, $player2, $player3,$player4,$player5,$player6);
			$this->game->Play6Players($player1, $player2, $player3,$player4,$player5,$player6);
		}catch (ValidationException $ex){
			throw $ex;
		}
	}
	public function getRounds(){
		return $this->game->getRounds();
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
			return;
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
			return;
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
			return;
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
			return;
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
			return;
		}
	
		throw new ValidationException($errors);
	}
}