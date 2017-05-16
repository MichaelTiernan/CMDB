<?php
echo "<H2>".htmlentities($title)."</H2>";
if ( $errors ) {
	print '<ul class="list-group">';
	foreach ( $errors as $field => $error ) {
		print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
	}
	print '</ul>';
}
if ($AssignAccess){
	echo " <a href=\"Devices.php?Category=".$this->Category."\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
	echo "<p></p>";
	echo "<table class=\"table table-striped table-bordered\">";
	echo "<thead>";
	echo "<tr>";
	echo "<th>AssetTag</th>";
	echo "<th>SerialNumber</th>";
	echo "<th>Type</th>";
	echo "<th>Active</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach ($rows as $row):
	echo "<tr>";
	echo "<td>".htmlentities($row['AssetTag'])."</td>";
	echo "<td>".htmlentities($row['SerialNumber'])."</td>";
	echo "<td>".htmlentities($row['Type'])."</td>";
	echo "<td>".htmlentities($row['Active'])."</td>";
	echo "</tr>";
	endforeach;
	echo "</tbody>";
	echo "</table>";
	?>
	<form class="form-horizontal" action="" method="post">
    	<div class="control-group ">
    		<label class="control-label">Identity <span style="color:red;">*</span></label>
            <div class="controls">
                <select name="Identity" class="selectpicker">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Identity"])){
                        foreach ($identities as $type){
                            echo "<option value=\"".$type["Iden_ID"]."\">Name: ".$type["Name"].", UserID: ".$type["UserID"]."</option>";
                        }
                    }  else {
                        foreach ($identities as $type){
                            if ($_POST["Identity"] == $type["Iden_ID"]){
                                echo "<option value=\"".$type["Iden_ID"]."\" selected>Name: ".$type["Name"].", UserID: ".$type["UserID"]."</option>";
                            }else{
                                echo "<option value=\"".$type["Iden_ID"]."\">Name: ".$type["Name"].", UserID: ".$type["UserID"]."</option>";
                            }
                        }
                    }
                ?>
                </select>
            </div>
        </div>
        <input type="hidden" name="AssetTag" value="<?php echo $row['AssetTag'];?>" /><br>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Assign</button>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
	<?php
}else {
    $this->showError("Application error", "You do not access to this page");
}