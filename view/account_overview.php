<h2>Account Details</h2>
<?php 
if ($ViewAccess){
    if ($AddAccess){
        echo "<a class=\"btn icon-btn btn-success\" href=\"Account.php?op=new\">";
        echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    }
    echo " <a href=\"Account.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
    echo "<p></p>";
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>UserID</th>";
    echo "<th>Application</th>";
    echo "<th>Type</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['UserID'])."</td>";
        echo "<td>".htmlentities($row['Application'])."</td>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "</tr>";
    endforeach;
    echo "</tbody>";
    echo "</table>";
    if ($AccAccess){
        echo "<H3>Identity overview</H3>";
        if (!empty($accrows)){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>UserID</th>";
            echo "<th>From</th>";
            echo "<th>Until</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($accrows as $account){
                $ValidEnd = NUll;
                If (isset($account["ValidEnd"])){
                    $ValidEnd = $account["ValidEnd"];
                }else {
                    $ValidEnd = "9999-12-31";
                }
                echo "<tr>";
                echo "<td class=\"small\">".htmlentities($account["Name"])."</td>";
                echo "<td class=\"small\">".htmlentities($account["UserID"])."</td>";
                echo "<td class=\"small\">".htmlentities(date("d-m-Y", strtotime($account["ValidFrom"])))."</td>";
                echo "<td class=\"small\">".htmlentities(date("d-m-Y", strtotime($ValidEnd)))."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }else {
            echo "No Accounts assigned to this Identity";
        }
    }
    echo "<H3>Log overview</H3>";
    if (!empty($logrows)){
        echo "<table class=\"table table-striped table-bordered\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Date</th>";
        echo "<th>Text</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($logrows as $log){
            echo "<tr>";
            echo "<td class=\"small\">".htmlentities(date("d-m-Y h:i:s", strtotime($log["Log_Date"])))."</td>";
            echo "<td class=\"small\">".htmlentities($log["Log_Text"])."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }  else {
        echo "No Log entries found for this Account";
    }
}else {
    $this->showError("Application error", "You do not access to this page");
}