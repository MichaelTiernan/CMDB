<?php
session_start();
require_once 'GameController.php';
$Game = new gameController();
$Game->handleRequest();