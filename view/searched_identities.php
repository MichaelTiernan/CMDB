<h2>Identities</h2>
<?php
echo "<div class=\"container\">";
echo "<div class=\"row\">";
if ($AddAccess){
    echo "<div class=\"col-md-6\"><a class=\"btn icon-btn btn-success\" href=\"identity.php?op=new\">";
    echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    echo " <a href=\"Identity.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
    echo "</div>";
}
echo "<div class=\"col-md-6 text-right\">";
?>
<form class="form-inline" role="search" action="identity.php?op=search" method="post">
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
    echo "<th>Name</th>";
    echo "<th>UserID</th>";
    echo "<th>E Mail</th>";
    echo "<th>Language</th>";
    echo "<th>Type</th>";
    echo "<th>Active</th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['Name'])."</a></td>";
        echo "<td>".htmlentities($row['UserID'])."</td>";
        echo "<td>".htmlentities($row['E_Mail'])."</td>";
        echo "<td>".htmlentities($row['Language'])."</td>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "<td>";
        if ($row['Iden_Id'] >1){
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"identity.php?op=edit&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo "<span class=\"fa fa-pencil\"></span></a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"identity.php?op=delete&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Deactivate\">";
                echo "<span class=\"fa fa fa-toggle-off\"></span></a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"identity.php?op=activate&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo "<span class=\"fa fa fa-toggle-on\"></span></a>";
            }
            if ($row["Active"] == "Active" and $AssignAccess){
                echo "<a class=\"btn btn-success\" href=\"identity.php?op=assign&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Account\">";
                echo "<span class=\"fa fa-user-plus\"></span></a>";
            }
        }
        if ($InfoAccess) {
            echo "<a class=\"btn btn-info\" href=\"identity.php?op=show&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
            echo "<span class=\"fa fa-info\"></span></a>";
        }    
        echo "</td>"; 
        echo "</tr>";     
    endforeach;
    echo "</tbody>";
    echo "</table>";
}else {
    echo "<div class=\"alert alert-danger\">No rows returned with the search criteria: ".htmlentities($search)."</div>";
}
?>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
 