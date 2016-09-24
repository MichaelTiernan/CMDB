<h2>Permissions</h2>
<?php
echo "<div class=\"container\">";
echo "<div class=\"row\">";
if ($AddAccess){
    echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Permission.php?op=new\">";
    echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    echo "</div>";
}
echo "<div class=\"col-md-6 text-right\">";
?>
<form class="form-inline" role="search" action="Permission.php?op=search" method="post">
    <div class="form-group">
       <input name="search" type="text" class="form-control" placeholder="Search">
    </div>
       <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
</form>
<?php
echo "</div>";
echo "</div>";
echo "<table class=\"table table-striped table-bordered\">";
echo "<thead>";
echo "<tr>";
echo "<th><a href=\"Permission.php?orderby=Level\">Level</a></th>";
echo "<th><a href=\"Permission.php?orderby=Menu\">Menu</a></th>";
echo "<th><a href=\"Permission.php?orderby=Permission\">Permission</a></th>";
echo "<th>Actions</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
foreach ($rows as $row):
    echo "<tr>";
    echo "<td>".htmlentities($row['Level'])."</td>";
    echo "<td>".htmlentities($row['Menu'])."</td>";
    echo "<td>".htmlentities($row['Permission'])."</td>";
    echo "<td>";
    IF ($UpdateAccess){
        echo "<a class=\"btn btn-primary\" href=\"Permission.php?op=edit&id=".$row["role_perm_id"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
        echo "<span class=\"fa fa-pencil\"></span></a>";
    }
    if ($DeleteAccess){
        echo "<a class=\"btn btn-danger\" href=\"Permission.php?op=delete&id=".$row["role_perm_id"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
        echo "<span class=\"fa fa-trash\"></span></a>";
    }
    if ($InfoAccess) {
        echo "<a class=\"btn btn-info\" href=\"Permission.php?op=show&id=".$row["role_perm_id"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
        echo "<span class=\"fa fa-info\"></span></a>";
    }    
    echo "</td>"; 
    echo "</tr>";     
endforeach;
echo "</tbody>";
echo "</table>";
?>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>