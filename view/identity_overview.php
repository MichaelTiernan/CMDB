<h2>Identity details</h2>
<?php 
if ($ViewAccess){
    if ($AddAccess){
        echo "<a class=\"btn icon-btn btn-success\" href=\"identity.php?op=new\">";
        echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    }
    echo " <a href=\"Identity.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
    echo "<p></p>";
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>UserID</th>";
    echo "<th>E Mail</th>";
    echo "<th>Language</th>";
    echo "<th>Type</th>";
    echo "<th>Active</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['Name'])."</td>";
        echo "<td>".htmlentities($row['UserID'])."</td>";
        echo "<td>".htmlentities($row['E_Mail'])."</td>";
        echo "<td>".htmlentities($row['Language'])."</td>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "</tr>";
    endforeach;
    echo "</tbody>";
    echo "</table>";
    if ($AccAccess){
        echo "<H3>Account overview</H3>";
        if (!empty($accrows)){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>UserID</th>";
            echo "<th>Application</th>";
            echo "<th>From</th>";
            echo "<th>Until</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($accrows as $account){
                echo "<tr>";
                echo "<td class=\"small\">".htmlentities($account["UserID"])."</td>";
                echo "<td class=\"small\">".htmlentities($account["Application"])."</td>";
                echo "<td class=\"small\">".htmlentities(date($this->getDateFormat(), strtotime($account["ValidFrom"])))."</td>";
                if (!empty($account["ValidEnd"])){
                    echo "<td class=\"small\">".htmlentities(date($this->getDateFormat(), strtotime($account["ValidEnd"])))."</td>";
                }else{
                    echo "<td class=\"small\">".date($this->getDateFormat(),strtotime("now +1 year"))."</td>";   
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }else {
            echo "No Accounts assigned to this Identity";
        }
    }
    if ($DevAccess){
        echo "<H3>Device overview</H3>";
        if (!empty($devicerows)){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Category</th>";
            echo "<th>Type</th>";
            echo "<th>AssetTag</th>";
            echo "<th>SerialNumber</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($devicerows as $device){
                echo "<tr>";
                echo "<td class=\"small\">".htmlentities($device["Category"])."</td>";
                echo "<td class=\"small\">".htmlentities($device["Vendor"])." ".htmlentities($device["Type"])."</td>";
                echo "<td class=\"small\">".htmlentities($device["AssetTag"])."</td>";
                echo "<td class=\"small\">".htmlentities($device["SerialNumber"])."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }else{
            echo "No Devices assigned to this Identity";
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
            echo "<td class=\"small\">".htmlentities(date($this->getLogDateFormat(), strtotime($log["Log_Date"])))."</td>";
            echo "<td class=\"small\">".htmlentities($log["Log_Text"])."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }  else {
        echo "No Log entries found for this Identity";
    }
}else {
    $this->showError("Application error", "You do not access to this page");
}