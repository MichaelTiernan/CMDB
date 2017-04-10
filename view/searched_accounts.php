<h2>Accounts</h2>
<?php
echo "<div class=\"row\">";
if ($AddAccess){
    echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Account.php?op=new\">";
    echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    echo " <a href=\"Account.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
    echo "</div>";
}
echo "<div class=\"col-md-6 text-right\">";
?>
<form class="form-inline" role="search" action="Account.php?op=search" method="post">
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
    echo "<th>UserID</th>";
    echo "<th>Type</th>";
    echo "<th>Application</th>";
    echo "<th>Active</th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['UserID'])."</td>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['Application'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "<td>";
        IF ($UpdateAccess){
            echo "<a class=\"btn btn-primary\" href=\"Account.php?op=edit&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
            echo "<span class=\"fa fa-pencil\"></span></a>";
        }
        if ($row["Active"] == "Active" and $DeleteAccess){
            echo "<a class=\"btn btn-danger\" href=\"Account.php?op=delete&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
            echo "<span class=\"fa fa-toggle-off\"></span></a>";
        }elseif ($ActiveAccess){
            echo "<a class=\"btn btn-glyphicon\" href=\"Account.php?op=activate&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
            echo "<span class=\"fa fa-toggle-on\"></span></a>";
        }
        if ($row["Active"] == "Active" and $AssignAccess){
            echo "<a class=\"btn btn-success\" href=\"Account.php?op=assign&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
            echo "<span class=\"fa fa-user-plus\"></span></a>";
        }
        if ($InfoAccess) {
            echo "<a class=\"btn btn-info\" href=\"Account.php?op=show&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
            echo "<span class=\"fa fa-info\"></span></a>";
        }    
        echo "</td>"; 
        echo "</tr>";     
    endforeach;
    echo "</tbody>";
    echo "</table>";
}  else {
    echo "<div class=\"alert alert-danger\">No rows returned with the search criteria: ".htmlentities($search)."</div>";
}
?>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>