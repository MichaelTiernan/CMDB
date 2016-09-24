<?php
include_once 'header.php';
require_once 'controller/AccountController.php';
$controller = new AccountController();
$controller->handleRequest();
include 'footer.php';
