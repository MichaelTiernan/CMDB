<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Test CMDB</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dropdown.css" rel="stylesheet">
    <link href="css/bootstrap-select.min.css" rel="stylesheet">    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
</head>
<body>
    <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">CMDB</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="Index.php">Home <span class="sr-only">(current)</span></a></li>
        <li class="dropdown"> <!-- Identity -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Identity <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="identity.php">Overview</a></li>
                <li><a href="#">Change</a></li>
                <li><a href="#">Add</a></li>
                <li><a href="#">Update</a></li>
                <li><a href="#">Delete</a></li>
            </ul>
        </li>
        <li class="dropdown"> <!-- Account -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#">Overview</a></li>
                <li><a href="#">Change</a></li>
                <li><a href="#">Add</a></li>
                <li><a href="#">Update</a></li>
                <li><a href="#">Delete</a></li>
            </ul>
        </li>
        <li class="dropdown"> <!-- HW -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hardware <span class="caret"></span></a>
            <ul class="dropdown-menu multi-level">
                <li class="dropdown-submenu"><!-- Laptop -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Laptop</a>
                    <ul class="dropdown-menu">
                       <li><a href="#">Overview</a></li>
                        <li><a href="#">Change</a></li>
                        <li><a href="#">Add</a></li>
                        <li><a href="#">Update</a></li>
                        <li><a href="#">Delete</a></li>
                    </ul>
                </li>
                <li class="dropdown-submenu"><!-- Desktop -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Desktop</a>
                    <ul class="dropdown-menu">
                       <li><a href="#">Overview</a></li>
                        <li><a href="#">Change</a></li>
                        <li><a href="#">Add</a></li>
                        <li><a href="#">Update</a></li>
                        <li><a href="#">Delete</a></li>
                    </ul>
                </li>
            </ul>
        </li>
      </ul>      
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="container">