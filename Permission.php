<?php
include_once 'header.php';
require_once 'controller/PermissionController.php';
//require_once 'Classes/IndentityController.php';
$controller = new PermissionController();
$controller->handleRequest();
include 'footer.php';