<?php
include_once 'header.php';
require_once 'controller/KensingtonController.php';
$controller = new KensingtonController();
$controller->handleRequest();
include 'footer.php';
