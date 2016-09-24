<?php
include_once 'header.php';
require_once 'controller/RoleController.php';
$controller = new RoleController();
$controller->handleRequest();
include 'footer.php';
