<?php
include_once 'header.php';
require_once 'controller/AssetTypeController.php';
//require_once 'Classes/IndentityController.php';
$controller = new AssetTypeController();
$controller->handleRequest();
include 'footer.php';