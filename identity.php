<?php
include 'header.php';
require_once 'Classes/IndentityController.php';
$controller = new IndentityController();
$controller->handleRequest();