<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Test CMDB</title>
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
    $_SESSION["WhoName"] = "Root";
    $_SESSION["Level"]= 9;
    require_once 'controller/MenuController.php';
    $controller = new MenuController();
    $controller->handleRequest();
    ?>
<div class="container">