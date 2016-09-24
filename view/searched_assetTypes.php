<h2>Asset Types</h2>
<?php
echo "<div class=\"container\">";
echo "<div class=\"row\">";
if ($AddAccess){
    echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"AssetType.php?op=new\">";
    echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    echo " <a href=\"AssetType.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
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
    echo "<th><a href=\"AssetType.php?orderby=Category\">Category</a></th>";
    echo "<th><a href=\"AssetType.php?orderby=Vendor\">Vendor</a></th>";
    echo "<th><a href=\"AssetType.php?orderby=Type\">Type</a></th>";
    echo "<th><a href=\"AssetType.php?orderby=Active\">Active</a></th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['Category'])."</a></td>";
        echo "<td>".htmlentities($row['Vendor'])."</td>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "<td>"; 
        IF ($UpdateAccess){
            echo "<a class=\"btn btn-primary\" href=\"AssetType.php?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
            echo "<span class=\"fa fa-pencil\"></span></a>";
        }
        if ($row["Active"] == "Active" and $DeleteAccess){
            echo "<a class=\"btn btn-danger\" href=\"AssetType.php?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
            echo "<span class=\"fa fa-toggle-off\"></span></a>";
        }elseif ($ActiveAccess){
            echo "<a class=\"btn btn-glyphicon\" href=\"AssetType.php?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
            echo "<span class=\"fa fa-toggle-on\"></span></a>";
        }
        if ($InfoAccess) {
            echo "<a class=\"btn btn-info\" href=\"AssetType.php?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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