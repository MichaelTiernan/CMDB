<?php
session_start();
//include_once '../header.php';
require_once 'GameController.php';
if (empty($Game)){
	$Game = new gameController();
}
$Game->handleRequest();