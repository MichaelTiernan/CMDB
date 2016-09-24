<?php
include_once 'header.php';
require_once 'controller/IdentityTypeController.php';
//require_once 'Classes/IndentityController.php';
$controller = new IdentityTypeController();
$controller->handleRequest();
include 'footer.php';