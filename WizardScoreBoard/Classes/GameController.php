<?php
require_once 'GameService.php';
require_once 'ValidationException.php';
Class gameController{
	private $gameService;
	private $result;
	private $count = 0;
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
			}elseif ($op == 'round2'){
				$this->round2();
			}elseif ($op == 'round3'){
				$this->round3();
			}elseif ($op == 'round4'){
				$this->round4();
			}elseif ($op == 'round5'){
				$this->round5();
			}elseif ($op == 'round6'){
				$this->round6();
			}elseif ($op == 'round7'){
				$this->round7();
			}elseif ($op == 'round8'){
				$this->round8();
			}elseif ($op == 'round9'){
				$this->round9();
			}elseif ($op == 'round10'){
				$this->round10();
			}elseif ($op == 'round11'){
				$this->round11();
			}elseif ($op == 'round12'){
				$this->round12();
			}elseif ($op == 'round13'){
				$this->round13();
			}elseif ($op == 'round14'){
				$this->round14();
			}elseif ($op == 'round15'){
				$this->round15();
			}elseif ($op == 'round16'){
				$this->round16();
			}elseif ($op == 'round17'){
				$this->round17();
			}elseif ($op == 'round18'){
				$this->round18();
			}elseif ($op == 'round18'){
				$this->round18();
			}elseif ($op == 'round19'){
				$this->round19();
			}elseif ($op == 'round20'){
				$this->round20();
			}elseif ($op == 'last'){
				$this->Last_round();
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
			} catch (PDOException $ex){
            	$this->showError("Database exception",$ex);
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
		$resulsRound1 = $this->gameService->getResultRound($id,1);
		$round = 2;
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$resulsRound1 = $this->gameService->getResultRound($id,1);
			$players = $this->gameService->getPlayers($id);
			$round = 2;
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$resultRound = $round -1;
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round3&gameid='.$id);
				//return;
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
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
		$round = 3;
		$title = 'Round 3';
		$resultRound = $round -1;
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$resultRound = $round -1;
			$round = 3;
			$amount = $this->gameService->getAmountOfPlayers($id);
			$players = $this->gameService->getPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round4&gameid='.$id);
				//return;
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
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
		$round = 4;
		$title = 'Round 4';
		$resultRound = $round -1;
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 4;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			$players = $this->gameService->getPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round5&gameid='.$id);
				//return;
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	
	private function round5(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 5';
		$round = 5;
		$resultRound = $round -1;
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 5;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			$players = $this->gameService->getPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round6&gameid='.$id);
				//return;
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 6
	 * @throws Exception
	 */
	private function round6(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 6';
		$round = 6;
		$resultRound = $round -1;
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 6;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			$players = $this->gameService->getPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round7&gameid='.$id);
				//return;
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 7
	 * @throws Exception
	 */
	private function round7(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 7';
		$round = 7;
		$resultRound = $round -1;
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 7;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			$players = $this->gameService->getPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resulsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round8&gameid='.$id);
				//return;
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 8
	 * @throws Exception
	 */
	private function round8(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 8';
		$round = 8;
		$resultRound = $round -1;
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 8;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			$players = $this->gameService->getPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round9&gameid='.$id);
				//return;
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 9
	 * @throws Exception
	 */
	private function round9(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 9';
		$round = 9;
		$resultRound = $round -1;
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 9;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			$players = $this->gameService->getPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round10&gameid='.$id);
				//return;
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round10(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 10';
		$round = 10;
		$resultRound = $round -1;
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 10;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			$players = $this->gameService->getPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round10&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round11(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 11';
		$round = 11;
		$resultRound = $round -1;
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 11;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			$players = $this->gameService->getPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resulsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round11&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round12(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 12';
		$round = 12;
		$resultRound = $round -1;
		$amount = $this->gameService->getAmountOfPlayers($id);
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 12;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round13&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round13(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 13';
		$round = 13;
		$resultRound = $round -1;
		$amount = $this->gameService->getAmountOfPlayers($id);
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 13;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round14&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round14(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 14';
		$round = 14;
		$resultRound = $round -1;
		$amount = $this->gameService->getAmountOfPlayers($id);
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 14;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round15&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round15(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 15';
		$round = 15;
		$resultRound = $round -1;
		$amount = $this->gameService->getAmountOfPlayers($id);
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 15;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round16&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round16(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 16';
		$round = 16;
		$resultRound = $round -1;
		$amount = $this->gameService->getAmountOfPlayers($id);
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 16;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round17&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round17(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 17';
		$round = 17;
		$resultRound = $round -1;
		$amount = $this->gameService->getAmountOfPlayers($id);
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 17;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round18&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round18(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 18';
		$round = 18;
		$resultRound = $round -1;
		$amount = $this->gameService->getAmountOfPlayers($id);
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 18;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round19&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round19(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 19';
		$round = 19;
		$resultRound = $round -1;
		$amount = $this->gameService->getAmountOfPlayers($id);
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 19;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round20&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used in round 10
	 * @throws Exception
	 */
	private function round20(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$title = 'Round 20';
		$round = 20;
		$resultRound = $round -1;
		$amount = $this->gameService->getAmountOfPlayers($id);
		for ($i = 1; $i <= $resultRound; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		if ( isset($_POST["formRound".$round."-submitted"])) {
			$round = 20;
			$resultRound = $round -1;
			$amount = $this->gameService->getAmountOfPlayers($id);
			for ($i = 1; $i <= $resultRound; $i++){
				${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
			}
			$players = $this->gameService->getPlayers($id);
			$Result = array();
			$i = 1;
			foreach ($players as $player){
				$ReceivedPlayer = "ReceivedPlayer".$i;
				$RequiredPlayer = "RequiredPlayer".$i;
				$score = ${"resultsRound".$resultRound}[$i-1]["Score"];
				$Result[]= array("ID" => $i, "PlayersName" => $player['Name'],"Required" => $_POST[$RequiredPlayer],"Received" => $_POST[$ReceivedPlayer], "Score" => $score);
				$i++;
			}
			$this->gameService->setResultRound($id,$round,$Result);
			if (!$this->gameService->isThisTheLastRound($round, $amount)){
				$this->redirect('PlayWizard.php?op=round21&gameid='.$id);
				//return;
			}else{
				$this->redirect('PlayWizard.php?op=last&gameid='.$id);
			}
		}
		$lastround = 0;
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
	/**
	 * This will be used for the last round
	 * @throws Exception
	 */
	private function Last_round(){
		$id = isset($_GET['gameid'])?$_GET['gameid']:NULL;
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
		$amount = $this->gameService->getAmountOfPlayers($id);
		$rounds = $this->gameService->getAmountofRounds($amount);
		for ($i = 1; $i <= $rounds; $i++){
			${"resultsRound".$i} = $this->gameService->getResultRound($id,$i);
		}
		$lastround = 1;
		$title = 'End result';
		$players = $this->gameService->getPlayers($id);
		include 'View/Rounds_form.php';
	}
}