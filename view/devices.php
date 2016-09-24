<?php
echo "<h2>".htmlentities($title)."</h2>";
echo "<div class=\"container\">";
echo "<div class=\"row\">";
if ($AddAccess){
    echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Devices.php?Category=".$this->Category."&op=new\">";
    echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    echo "</div>";
}
echo "<div class=\"col-md-6 text-right\">";
echo "<form class=\"form-inline\" role=\"search\" action=\"Devices.php?Category=".$this->Category."&op=search\" method=\"post\">";
echo "<div class=\"form-group\">";
echo "<input name=\"search\" type=\"text\" class=\"form-control\" placeholder=\"Search\">";
echo "</div>";
echo "<button type=\"submit\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-search\"></i></button>";
echo "</form>";
echo "</div>";
echo "</div>";
if (count($rows)>0){
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=AssetTag\">AssetTag</a></th>";
    echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=SerialNumber\">SerialNumber</a></th>";
    echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=Type\">Type</a></th>";
    echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=Active\">Active</a></th>";
    echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=ussage\">ussage</a></th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['AssetTag'])."</td>";
        echo "<td>".htmlentities($row['SerialNumber'])."</td>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "<td>".htmlentities($row['ussage'])."</td>";
        echo "<td>";
        IF ($UpdateAccess){
            echo "<a class=\"btn btn-primary\" href=\"Devices.php?Category=".$this->Category."&op=edit&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
            echo "<span class=\"fa fa-pencil\"></span></a>";
        }
        if ($row["Active"] == "Active" and $DeleteAccess){
            echo "<a class=\"btn btn-danger\" href=\"Devices.php?Category=".$this->Category."&op=delete&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
            echo "<span class=\"fa fa-toggle-off\"></span></a>";
        }elseif ($ActiveAccess){
            echo "<a class=\"btn btn-glyphicon\" href=\"Devices.php?Category=".$this->Category."&op=activate&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
            echo "<span class=\"fa fa-toggle-on\"></span></a>";
        }
        if ($row["Active"] == "Active" and $AssignAccess){
            echo "<a class=\"btn btn-success\" href=\"Devices.php?Category=".$this->Category."&op=assign&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
            echo "<span class=\"fa fa-user-plus\"></span></a>";
        }
        if ($InfoAccess) {
            echo "<a class=\"btn btn-info\" href=\"Devices.php?Category=".$this->Category."&op=show&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
            echo "<span class=\"fa fa-info\"></span></a>";
        }    
        echo "</td>"; 
        echo "</tr>";     
    endforeach;
    echo "</tbody>";
    echo "</table>";
}  else {
    echo "<div class=\"alert alert-danger\">No rows found, please add a new record</div>";
}
?>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>