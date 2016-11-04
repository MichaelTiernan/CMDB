<?php
require_once 'GameService.php';
require_once 'ValidationException.php';
Class gameController{
	private $gameService;
	public function __construct(){
		$this->gameService = new GameService();
	}
	/**
	 * This function is the main function of this call
	 * It will be used to call the other functions.
	 */
	public function handleRequest() {
		
		$op = isset($_GET['op'])?$_GET['op']:NULL;
		try {
			if ( !$op || $op == 'reset' ) {
				$this->start();
			}elseif ($op == 'players'){
				$this->players();
			}elseif ($op == 'round1'){
				$this->round1();
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
		header('Location: '.$location);
	}
	
	private function start(){
		$title = 'Welcome to Wizard';
		$errors = array();
		$Players  = '';
		if ( isset($_POST['form-submitted'])) {
			$Players  = isset($_POST['players']) ? $_POST['players'] :NULL;
			try {
				echo "<br>The amount of players is: ".$this->amount."<br>";
				$this->gameService->setAmountOfPlayers($Players);
				$_SESSION["Players"] = $Players;
				$this->redirect('PlayWizard.php?op=players');
				return;
			} catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
		}
		include 'amountPlayers_form.php';
	}
	
	private function players(){	
		$title = 'Players name';
		$errors = array();
		$amount = $_SESSION["Players"];
		$Name ="";
		if ( isset($_POST['form-submitted'])) {
			print_r($_POST);
			try {
				switch ($amount) {
					case 3:
						$this->gameService->Play3Players($_POST["player1"], $_POST["player2"], $_POST["player3"]);
						break;
					case 4:
						$this->gameService->Play4Players($_POST["player1"], $_POST["player2"], $_POST["player3"],$_POST["player4"]);
						break;
					case 5:
						$this->gameService->Play5Players($_POST["player1"], $_POST["player2"], $_POST["player3"],$_POST["player4"],$_POST["player5"]);
						break;
					case 6:
						$this->gameService->Play6Players($_POST["player1"], $_POST["player2"], $_POST["player3"],$_POST["player4"],$_POST["player5"],$_POST["player6"]);
						break;
				}
				//$this->redirect('PlayWizard.php?op=round1');
				//return;
			} catch (ValidationException $e) {
				$errors = $e->getErrors();
			}
		}
		include 'PlayersName_form.php';
	}
	
	private function round1(){
		$title = 'Round 1';
		$errors = array();
		$amount = $_SESSION["Players"];
		
		include 'Round_form.php';
	}
	
}