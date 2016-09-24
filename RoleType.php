<?php
include_once 'header.php';
require_once 'controller/RoleTypeController.php';
$controller = new RoleTypeController();
$controller->handleRequest();
include 'footer.php';
