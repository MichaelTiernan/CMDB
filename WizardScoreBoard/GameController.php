<?php
require_once 'GameService.php';
require_once 'ValidationException.php';
Class gameController{
	private $gameService;
	private $result;
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
	function Redirect($url)
	{
		ob_start();
		header("Location: ".$url);
		ob_end_flush();
		exit;
	}
	
	private function start(){
		$title = 'Welcome to Wizard';
		$errors = array();
		$Players  = '';
		if ( isset($_POST['form-submitted'])) {
			$Players  = isset($_POST['players']) ? $_POST['players'] :NULL;
			//print_r($_POST);
			try {
				$this->gameService->setAmountOfPlayers($Players);
				$this->redirect('PlayWizard.php?op=players');
//  				return ;
			} catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
		}
		include 'amountPlayers_form.php';
	}
	
	private function players(){
		$title = 'Players name';
		$errors = array();
		$amount = $this->gameService->getAmountOfPlayers();
		$Name = "";
		if ( isset($_POST['form-PlayersSubmitted'])) {
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
					default:
						throw new Exception("Not a valid amount");
						break;
				}
				$this->redirect('PlayWizard.php?op=round1');
// // 				die();
			} catch (ValidationException $e) {
				$errors = $e->getErrors();
			}
		}
		include 'PlayersName_form.php';
	}
	
	private function round1(){
		$amount = $this->gameService->getAmountOfPlayers();
		$title = 'Round 1';
		if ( isset($_POST['formRound1-submitted'])) {
			$players = $this->gameService->getPlayers($amount);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => 0);
				$i++;
			}
			$this->gameService->setRound1($Result);
			if (!$this->gameService->isThisTheLastRound(1, $amount)){
				$this->redirect('PlayWizard.php?op=round2');
// 				return;
			}
		}
		$players = $this->gameService->getPlayers($amount);
		include 'Round1_form.php';
	}
	
	private function round2(){
		$title = 'Round 2';
		$amount = $this->gameService->getAmountOfPlayers();
		$players = $this->gameService->getPlayers($amount);
		$results = $this->gameService->getRound1($amount);
		if ( isset($_POST['form-submitted'])) {
			
		}
		include 'Round2_form.php';
	}
}