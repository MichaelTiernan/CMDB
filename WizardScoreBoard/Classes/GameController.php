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
			}elseif ($op == 'round3'){
				$this->round3();
			}elseif ($op == 'round4'){
				$this->round4();
			}elseif ($op == 'round5'){
				$this->round5();
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
		include 'View/error.php';
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
				$Game_ID = $this->gameService->setAmountOfPlayers($Players);
				$this->redirect('PlayWizard.php?op=players&gameid='.$Game_ID);
//  				return ;
			} catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
		}
		include 'View/amountPlayers_form.php';
	}
	
	private function players(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$title = 'Players name';
		$errors = array();
		$amount = $this->gameService->getAmountOfPlayers($id);
		$Name = "";
		if ( isset($_POST['form-PlayersSubmitted'])) {
			try {
				switch ($amount) {
					case 3:
						$this->gameService->Play3Players($id,$_POST["player1"], $_POST["player2"], $_POST["player3"]);
						break;
					case 4:
						$this->gameService->Play4Players($id,$_POST["player1"], $_POST["player2"], $_POST["player3"],$_POST["player4"]);
						break;
					case 5:
						$this->gameService->Play5Players($id,$_POST["player1"], $_POST["player2"], $_POST["player3"],$_POST["player4"],$_POST["player5"]);
						break;
					case 6:
						$this->gameService->Play6Players($id,$_POST["player1"], $_POST["player2"], $_POST["player3"],$_POST["player4"],$_POST["player5"],$_POST["player6"]);
						break;
					default:
						throw new Exception("Not a valid amount");
						break;
				}
				$this->redirect('PlayWizard.php?op=round1&gameid='.$id);
 // 				die();
			} catch (ValidationException $e) {
				$errors = $e->getErrors();
			}
		}
		include 'View/PlayersName_form.php';
	}
	
	private function round1(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 1';
		if ( isset($_POST['formRound1-submitted'])) {
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => 0);
				$i++;
			}
			$this->gameService->setResultRound($id,1,$Result);
			if (!$this->gameService->isThisTheLastRound(1, $amount)){
				$this->redirect('PlayWizard.php?op=round2&gameid='.$id);
// 				return;
			}
		}
		$players = $this->gameService->getPlayers($id);
		include 'View/Round1_form.php';
	}
	
	private function round2(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$title = 'Round 2';
		$amount = $this->gameService->getAmountOfPlayers($id);
		$results = $this->gameService->getResultRound($id,1);
		
		if ( isset($_POST['formRound2-submitted'])) {
			$results = $this->gameService->getResultRound($id,1);
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = $results[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,2,$Result);
			if (!$this->gameService->isThisTheLastRound(2, $amount)){
				$this->redirect('PlayWizard.php?op=round3&gameid='.$id);
				//return;
			}
		}
		$players = $this->gameService->getPlayers($id);
		include 'View/Round2_form.php';
	}
	/**
	 * Round 3
	 * @throws Exception
	 */
	private function round3(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$resulsRound1 = $this->gameService->getResultRound($id,1);
		$resulsRound2 = $this->gameService->getResultRound($id,2);
		$title = 'Round 3';
		if ( isset($_POST['formRound3-submitted'])) {
			$amount = $this->gameService->getAmountOfPlayers($id);
			$resulsRound1 = $this->gameService->getResultRound($id,1);
			$resulsRound2 = $this->gameService->getResultRound($id,2);
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = $results[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,3,$Result);
			if (!$this->gameService->isThisTheLastRound(3, $amount)){
				$this->redirect('PlayWizard.php?op=round4&gameid='.$id);
				//return;
			}
		}
		$players = $this->gameService->getPlayers($id);
		include 'View/Round3_form.php';
	}
	/**
	 * Round 4
	 * @throws Exception
	 */
	private function round4(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$resulsRound1 = $this->gameService->getResultRound($id,1);
		$resulsRound2 = $this->gameService->getResultRound($id,2);
		$resulsRound3 = $this->gameService->getResultRound($id,3);
		$title = 'Round 4';
		if ( isset($_POST['formRound4-submitted'])) {
			$amount = $this->gameService->getAmountOfPlayers($id);
			$resulsRound1 = $this->gameService->getResultRound($id,1);
			$resulsRound2 = $this->gameService->getResultRound($id,2);
			$resulsRound3 = $this->gameService->getResultRound($id,3);
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = $results[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,4,$Result);
			if (!$this->gameService->isThisTheLastRound(4, $amount)){
				$this->redirect('PlayWizard.php?op=round5&gameid='.$id);
				//return;
			}
		}
		$players = $this->gameService->getPlayers($id);
		include 'View/Round4_form.php';
	}
	
	private function round5(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$resulsRound1 = $this->gameService->getResultRound($id,1);
		$resulsRound2 = $this->gameService->getResultRound($id,2);
		$resulsRound3 = $this->gameService->getResultRound($id,3);
		$resulsRound4 = $this->gameService->getResultRound($id,4);
		$title = 'Round 5';
		if ( isset($_POST['formRound4-submitted'])) {
			$amount = $this->gameService->getAmountOfPlayers($id);
			$resulsRound1 = $this->gameService->getResultRound($id,1);
			$resulsRound2 = $this->gameService->getResultRound($id,2);
			$resulsRound3 = $this->gameService->getResultRound($id,3);
			$resulsRound4 = $this->gameService->getResultRound($id,4);
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = $results[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,5,$Result);
			if (!$this->gameService->isThisTheLastRound(5, $amount)){
				$this->redirect('PlayWizard.php?op=round6&gameid='.$id);
				//return;
			}
		}
		$players = $this->gameService->getPlayers($id);
		include 'View/Round5_form.php';
	}
}