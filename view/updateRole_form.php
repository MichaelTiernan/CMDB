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
        <div class="control-group">
            <label class="control-label">Name <span style="color:red;">*</span></label>
            <div class="controls">
                <input name="Name" type="text"  placeholder="Pleae insert Name" value="<?php echo $Name;?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Description</label>
            <div class="controls">
                <input name="Description" type="text"  placeholder="Please insert description" value="<?php echo $Description;?>">
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
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Update</button>
            <a class="btn" href="Role.php">Back</a>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}