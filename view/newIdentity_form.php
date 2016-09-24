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
            <label class="control-label">First Name <span style="color:red;">*</span></label>
            <div class="controls">
                <input name="FirstName" type="text"  placeholder="FirstName" value="<?php echo $FristName;?>">
            </div>
        </div>
        <div class="control-group ">
          <label class="control-label">Last Name <span style="color:red;">*</span></label>
          <div class="controls">
              <input name="LastName" type="text" placeholder="LastName" value="<?php echo $LastName;?>">
          </div>
        </div>
        <div class="control-group">
            <label class="control-label">UserID <span style="color:red;">*</span></label>
            <div class="controls">
                <input name="UserID" type="text"  placeholder="UserID" value="<?php echo $userid;?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Company</label>
            <div class="controls">
                <input name="Company" type="text"  placeholder="Company" value="<?php echo $company;?>">
            </div>
        </div>
        <div class="control-group ">
          <label class="control-label">E-Mail Address <span style="color:red;">*</span></label>
          <div class="controls">
              <input name="EMail" type="text" placeholder="E-Mail Address" value="<?php echo $EMail;?>">
          </div>
        </div>
        <div class="control-group ">
            <label class="control-label">Language <span style="color:red;">*</span></label>
            <div class="controls">
                <select name="Language" class="selectpicker">
                <?php if(empty($Language)){?>
                    <option value=""></option>
                    <option value="NL">Dutch</option>
                    <option value="FR">French</option>
                    <option value="EN">English</option>
                <?php } elseif ($Language == "NL") {?>
                    <option value=""></option>
                    <option value="NL" selected>Dutch</option>
                    <option value="FR">French</option>
                    <option value="EN">English</option>
                <?php } elseif ($Language == "FR") {?>
                    <option value=""></option>
                    <option value="NL">Dutch</option>
                    <option value="FR" selected>French</option>
                    <option value="EN">English</option>
                <?php } elseif ($Language == "EN"){ ?>
                    <option value=""></option>
                    <option value="NL">Dutch</option>
                    <option value="FR" >French</option>
                    <option value="EN" selected>English</option>
                <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label">Type <span style="color:red;">*</span></label>
            <div class="controls">
                <select name="type" class="selectpicker">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["type"])){
                        foreach ($types as $type){
                            echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]." ".$type["Description"]."</option>";
                        }
                    }  else {
                        foreach ($types as $type){
                            if ($_POST["type"] == $type["Type_ID"]){
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
            <button type="submit" class="btn btn-success">Create</button>
            <a class="btn" href="identity.php">Back</a>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}