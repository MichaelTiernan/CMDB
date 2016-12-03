<?php
require_once 'GameService.php';
require_once 'ValidationException.php';
Class gameController{
	private $gameService;
	private $reslult;
	private $count = 0;
	public function __construct(){
		$this->count++;
		$this->gameService = new GameService();
	}
	/**
	 * This function is the main function of this call
	 * It will be used to call the other functions.
	 */
	public function handleRequest() {
		print "GameController: This is the ".$this->count." occourence<br>";
		$op = isset($_GET['op'])?$_GET['op']:NULL;
		try {
			if ( !$op || $op == 'reset' ) {
				$this->start();
			}elseif ($op == 'players'){
				$this->players();
			}elseif ($op == 'round1'){
				$this->round1();
			}elseif ($op == 'round2'){
				$this->round2();
			}
		}catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->showError("Application error", $e->getMessage());
        } 
        
	}
	/**
	 * This function will show an given error
	 * @param string $title The title of the error
	 * @param string $message The message
	 */
	public function showError($title, $message) {
		include 'error.php';
	}
	/**
	 * This function will redirect to the the given location
	 * @param string $location
	 */
	public function redirect($location) {
		header('Location: '.$location, TRUE, 301);
	}
	
	private function start(){
		$title = 'Welcome to Wizard';
		$errors = array();
		$Players  = '';
		if ( isset($_POST['form-submitted'])) {
			$Players  = isset($_POST['players']) ? $_POST['players'] :NULL;
			try {
				print "GameController: The amount of players will be set to: ".$Players."<br>";
				//$_SESSION["Game_ID"] = $this->gameService->setAmountOfPlayers($Players);
				$this->gameService->setAmountOfPlayers($Players);
// 				print "The Game_ID = ".$_SESSION["Game_ID"]."<br>";
 				$this->redirect('PlayWizard.php?op=players');
 				return;
			} catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
		}
		include 'amountPlayers_form.php';
	}
	
	private function players(){
		print_r($_POST);
		$title = 'Players name';
		$errors = array();
		$Game_ID = $_SESSION["Game_ID"];
		$amount = $this->gameService->getAmountOfPlayers();
		print "The Amount of  players= ".$amount."<br>";
		$Name ="";
		if ( isset($_POST['form-submitted'])) {
			try {
				switch ($amount) {
					case 3:
						$this->gameService->Play3Players($Game_ID,$_POST["player1"], $_POST["player2"], $_POST["player3"]);
						break;
					case 4:
						$this->gameService->Play4Players($Game_ID,$_POST["player1"], $_POST["player2"], $_POST["player3"],$_POST["player4"]);
						break;
					case 5:
						$this->gameService->Play5Players($Game_ID,$_POST["player1"], $_POST["player2"], $_POST["player3"],$_POST["player4"],$_POST["player5"]);
						break;
					case 6:
						$this->gameService->Play6Players($Game_ID,$_POST["player1"], $_POST["player2"], $_POST["player3"],$_POST["player4"],$_POST["player5"],$_POST["player6"]);
						break;
				}
				$this->redirect('PlayWizard.php?op=round1');
				return;
			} catch (ValidationException $e) {
				$errors = $e->getErrors();
			}
		}
		include 'PlayersName_form.php';
	}
	
	private function round1(){
		$title = 'Round 1';
		$Game_ID = $_SESSION["Game_ID"];
		$amount = $this->gameService->getAmountOfPlayers($Game_ID);
		$players = $this->gameService->getPlayers($Game_ID);
		if ( isset($_POST['form-submitted'])) {
			print_r($_POST);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$Result[]= array("PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => 0);
				$i++;
			}
			$this->reslult = $this->gameService->setRound1($Result);
			if (!$this->gameService->isThisTheLastRound(1, $amount)){
				$this->redirect('PlayWizard.php?op=round2');
				return;
			}
		}	
		include 'Round1_form.php';
	}
	
	private function round2(){
		$title = 'Round 2';
		$Game_ID = $_SESSION["Game_ID"];
		$amount = $this->gameService->getAmountOfPlayers($Game_ID);
		$players = $this->gameService->getPlayers($Game_ID);
		if ( isset($_POST['form-submitted'])) {
			
		}
		include 'Round2_form.php';
	}
}