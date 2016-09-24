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
                    </ul>
                </li>
                <li class="dropdown"> <!-- Account -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Overview</a></li>
                    </ul>
                </li>
                <li class="dropdown"> <!-- HW -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hardware <span class="caret"></span></a>
                    <ul class="dropdown-menu multi-level">
                        <li class="dropdown-submenu"><!-- Laptop -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Laptop</a>
                            <ul class="dropdown-menu">
                               <li><a href="#">Overview</a></li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu"><!-- Desktop -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Desktop</a>
                            <ul class="dropdown-menu">
                               <li><a href="#">Overview</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>      
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>