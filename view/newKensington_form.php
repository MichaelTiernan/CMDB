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
            <label class="control-label">Type <span style="color:red;">*</span></label>
            <div class="controls">
                <select name="Type" class="selectpicker">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Type"])){
                        foreach ($types as $type){
                            echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                        }
                    }  else {
                        foreach ($types as $type){
                            if ($_POST["Type"] == $type["Type_ID"]){
                                echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Vendor"]." ".$type["Type"]."</option>";
                            }else{
                                echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                            }
                        }
                    }
                ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Serial Number <span style="color:red;">*</span></label>
            <div class="controls">
                <input name="SerialNumber" type="text" placeholder="Please enter a SerialNumber" value="<?php echo $Serial;?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Amount of keys <span style="color:red;">*</span></label>
            <div class="controls">
                <input name="Keys" type="text" placeholder="Please enter a amount" value="<?php echo $NrKeys;?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Has lock <span style="color:red;">*</span></label>
            <div class="controls">
                <?php if (empty($hasLock)){ ?>
                <label class="radio-inline"><input type="radio" name="Lock" value="1">Yes</label>
                <label class="radio-inline"><input type="radio" name="Lock" value="0">No</label>
                <?php }elseif ($hasLock == 1){ ?>
                <label class="radio-inline"><input type="radio" checked name="Lock" value="1">Yes</label>
                <label class="radio-inline"><input type="radio" name="Lock" value="0">No</label>
                <?php }else { ?>
                <label class="radio-inline"><input type="radio" name="Lock" value="1">Yes</label>
                <label class="radio-inline"><input type="radio" checked name="Lock" value="0">No</label>
                <?php } ?>
            </div>
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Create</button>
            <?php echo "<a class=\"btn\" href=\"Kensington.php\">Back</a>"; ?>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php  
}  else {
    $this->showError("Application error", "You do not access to this page");
}