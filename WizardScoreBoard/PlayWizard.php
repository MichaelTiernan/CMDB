<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Wizard scoreboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dropdown.css" rel="stylesheet">
    <link href="css/bootstrap-select.min.css" rel="stylesheet">  
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/datepacker.css" rel="stylesheet">
    <link href="css/bootstrap2-toggle.min.css" rel="stylesheet">  
    <script src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="js/bootstrap-select.js" charset="UTF-8"></script>
    <script type="text/javascript" src="js/bootstrap-datepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="js/bootstrap2-toggle.min.js" charset="UTF-8"></script>
</head>
<body>
<?php
session_start();
//include_once '../header.php';
require_once 'Classes/GameController.php';
// if (empty($Game)){
// 	$Game = new gameController();
// }
$Game = new gameController();
$Game->handleRequest();