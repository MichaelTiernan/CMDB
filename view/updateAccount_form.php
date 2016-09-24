<?php print "<h2>".htmlentities($title)."</h2>"; 
if ($UpdateAccess){?>
<form class="form-horizontal" action="" method="post">
    <div class="control-group">
        <label class="control-label">UserID <span style="color:red;">*</span></label>
        <div class="controls">
            <input name="UserID" type="text"  placeholder="UserID" value="<?php echo $UserID;?>">
        </div>
    </div>
    <div class="control-group ">
        <label class="control-label">Type <span style="color:red;">*</span></label>
        <div class="controls">
            <select name="type" class="selectpicker">
            <?php echo "<option value=\"\"></option>";
                if (empty($Type)){
                    foreach ($types as $type){
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]." ".$type["Description"]."</option>";
                    }
                }  else {
                    foreach ($types as $type){
                        if ($Type == $type["Type_ID"]){
                            echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Type"]." ".$type["Description"]."</option>";
                        }else{
                            echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]." ".$type["Description"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
    </div>
    <div class="control-group ">
        <label class="control-label">Application <span style="color:red;">*</span></label>
        <div class="controls">
            <select name="Application" class="selectpicker">
            <?php echo "<option value=\"\"></option>";
                if (empty($Application)){
                    foreach ($applications as $application){
                        echo "<option value=\"".$application["App_ID"]."\">".$application["Name"]."</option>";
                    }
                }  else {
                    foreach ($applications as $application){
                        if ($Application == $application["App_ID"]){
                            echo "<option value=\"".$application["App_ID"]."\" selected>".$application["Name"]."</option>";
                        }else{
                            echo "<option value=\"".$application["App_ID"]."\">".$application["Name"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
    </div> 
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Update</button>
        <a class="btn" href="account.php">Back</a>
    </div>
    <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div>
</form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}