<?php
include_once 'header.php';
require_once 'controller/AdminController.php';
$controller = new AdminController();
$controller->handleRequest();
include 'footer.php';