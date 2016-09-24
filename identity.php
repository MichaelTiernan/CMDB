<?php
include_once 'header.php';
require 'controller/IdentityController.php';
//require_once 'Classes/IndentityController.php';
$controller = new IdentityController();
$controller->handleRequest();
include 'footer.php';