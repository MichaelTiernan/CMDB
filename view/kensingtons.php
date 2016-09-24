<h2>Kensington</h2>
<?php
echo "<div class=\"container\">";
echo "<div class=\"row\">";
if ($AddAccess){
    echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Kensington.php?op=new\">";
    echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    echo "</div>";
}
echo "<div class=\"col-md-6 text-right\">";
?>
<form class="form-inline" role="search" action="Kensington.php?op=search" method="post">
    <div class="form-group">
       <input name="search" type="text" class="form-control" placeholder="Search">
    </div>
       <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
</form>
<?php
echo "</div>";
echo "</div>";
if (count($rows)>0){
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th><a href=\"Kensington.php?orderby=Type\">Type</a></th>";
    echo "<th><a href=\"Kensington.php?orderby=Serial\">Serialnumber</a></th>";
    echo "<th><a href=\"Kensington.php?orderby=AmountKeys\"># Keys</a></th>";
    echo "<th><a href=\"Kensington.php?orderby=hasLock\">Lock</a></th>";
    echo "<th><a href=\"Kensington.php?orderby=Active\">Active</a></th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['Type'])."</a></td>";
        echo "<td>".htmlentities($row['Serial'])."</a></td>";
        echo "<td>".htmlentities($row['AmountKeys'])."</td>";
        echo "<td>".htmlentities($row['hasLock'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "<td>"; 
        IF ($UpdateAccess){
            echo "<a class=\"btn btn-primary\" href=\"Kensington.php?op=edit&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
            echo "<span class=\"fa fa-pencil\"></span></a>";
        }
        if ($row["Active"] == "Active" and $DeleteAccess){
            echo "<a class=\"btn btn-danger\" href=\"Kensington.php?op=delete&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
            echo "<span class=\"fa fa-toggle-off\"></span></a>";
        }elseif ($ActiveAccess){
            echo "<a class=\"btn btn-glyphicon\" href=\"Kensington.php?op=activate&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
            echo "<span class=\"fa fa-toggle-on\"></span></a>";
        }
        if ($InfoAccess) {
            echo "<a class=\"btn btn-info\" href=\"Kensington.php?op=show&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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