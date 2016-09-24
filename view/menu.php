<?php ob_start();?>
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
        <div class="collapse navbar-collapse">
             <ul class="nav navbar-nav">
                <li class="active"><a href="Index.php">Home <span class="sr-only">(current)</span></a></li>
                <?php foreach ($FirstMenu as $row){?>
                    <li class="dropdown"><!-- 1 -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php print $row["label"];?> 
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu multi-level">
                        <?php $SecondLevels = $this->accessService->getSecondLevel($row["Menu_id"]);         
                        foreach($SecondLevels as $SecondLevel){?>
                            <li class="dropdown-submenu"><!-- 2 -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php print $SecondLevel["label"];?> </a>
                                <?php $ThirdLevels = $this->accessService->getThirdLevel($Level,$SecondLevel["Menu_id"]);
                                foreach($ThirdLevels as $ThirdLevel){?>
                                    <ul class="dropdown-menu"><!-- 3 -->
                                       <li><a href="<?php print $ThirdLevel["link_url"]; ?> "><?php print $ThirdLevel["label"];?></a></li>
                                    </ul><?php }?>
                            </li><?php }?>
                        </ul>
                    </li><?php }?>
            </ul>       
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>