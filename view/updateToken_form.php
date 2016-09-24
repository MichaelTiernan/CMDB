<?php
print "<h2>".htmlentities($title)."</h2>";
if ($UpdateAccess){
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
            <label class="control-label">AssetTag <span style="color:red;">*</span></label>
            <div class="controls">
                <input name="AssetTag" type="text"  placeholder="Please insert a AssetTag" value="<?php echo $AssetTag;?>" disabled>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Serial Number <span style="color:red;">*</span></label>
            <div class="controls">
                <input name="SerialNumber" type="text"  placeholder="Please enter a SerialNumber" value="<?php echo $SerialNumber;?>">
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label">Type <span style="color:red;">*</span></label>
            <div class="controls">
                <select name="Type" class="selectpicker">
                <?php echo "<option value=\"\"></option>";
                    if (empty($Type)){
                        foreach ($typerows as $type){
                            echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                        }
                    }  else {
                        foreach ($typerows as $type){
                            if ($Type == $type["Type_ID"]){
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
        <input type="hidden" name="form-submitted" value="1" /><br>
        <input type="hidden" name="AssetTag" value="<?php echo $AssetTag;?>" />
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Update</button>
            <?php echo "<a class=\"btn\" href=\"Token.php\">Back</a>"; ?>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}