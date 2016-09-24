<?php
include_once 'header.php';
require_once 'controller/ApplicationController.php';
$controller = new ApplicationController();
$controller->handleRequest();
include 'footer.php';
