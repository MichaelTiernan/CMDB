<?php
print "<h2>".htmlentities($title)."</h2>";
if ($AddAccess){
	if ( $errors ) {
		print '<ul class="list-group">';
		foreach ( $errors as $field => $error ) {
			print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
		}
		print '</ul>';
	}
?>
	<form class="form-horizontal" action="" method="post">
		<div class="control-group ">
            <label class="control-label">Admin <span style="color:red;">*</span></label>
            <div class="controls">
                <select name="Admin" class="selectpicker">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Admin"])){
                        foreach ($Accounts as $Account){
                            echo "<option value=\"".$Account["Acc_ID"]."\">".$Account["UserID"]."</option>";
                        }
                    }  else {
                        foreach ($Accounts as $Account){
                            if ($_POST["Admin"] == $Account["Acc_ID"]){
                                echo "<option value=\"".$Account["Acc_ID"]."\" selected>".$Account["UserID"]."</option>";
                            }else{
                                echo "<option value=\"".$Account["Acc_ID"]."\">".$Account["UserID"]."</option>";
                            }
                        }
                    }
                ?>
                </select>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label">Level <span style="color:red;">*</span></label>
            <div class="controls">
                <select name="Level" class="selectpicker">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Level"])){
                        foreach ($Levels as $level){
                            echo "<option value=\"".$level["Level"]."\">".$level["Level"]."</option>";
                        }
                    }  else {
                        foreach ($Levels as $level){
                            if ($_POST["Level"] == $level["Level"]){
                                echo "<option value=\"".$level["Level"]."\" selected>".$level["Level"]."</option>";
                            }else{
                                echo "<option value=\"".$level["Level"]."\">".$level["Level"]."</option>";
                            }
                        }
                    }
                ?>
                </select>
            </div>
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Create</button>
            <a class="btn" href="Admin.php">Back</a>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
	</form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}