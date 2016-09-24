<?php
include_once 'header.php';
require_once 'controller/AccountTypeController.php';
$controller = new AccountTypeController();
$controller->handleRequest();
include 'footer.php';


