<?php
print "<h2>".htmlentities($title)."</h2>";
if ( $errors ) {
    print '<ul class="list-group">';
    foreach ( $errors as $field => $error ) {
        print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
    }
    print '</ul>';
}

?>
<form class="form-horizontal" action="" method="post">
    <div class="control-group <?php echo !empty($FirstNameError)?'error':'';?>">
        <label class="control-label">First Name</label>
        <div class="controls">
            <input name="FirstName" type="text"  placeholder="FirstName" value="<?php echo $FristName;?>">
            <?php if (!empty($FirstNameError)): ?>
                <span class="text-danger"><?php echo $FirstNameError;?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="control-group <?php echo !empty($LastNameError)?'error':'';?>">
      <label class="control-label">Last Name</label>
      <div class="controls">
          <input name="LastName" type="text" placeholder="LastName" value="<?php echo $LastName;?>">
          <?php if (!empty($LastNameError)): ?>
              <span class="text-danger"><?php echo $LastNameError;?></span>
          <?php endif;?>
      </div>
    </div>
    <div class="control-group">
        <label class="control-label">UserID</label>
        <div class="controls">
            <input name="UserID" type="text"  placeholder="UserID" value="<?php echo $userid;?>">
        </div>
    </div>
    <div class="control-group <?php echo !empty($TypeError)?'error':'';?>">
        <label class="control-label">Type</label>
        <div class="controls">
            <?php if (!empty($TypeError)): ?>
              <span class="text-danger"> <?php echo $TypeError;?></span>
            <?php endif;?>
            <?php if(empty($type)){?>
                <select name="type" class="selectpicker">
                    <option value=""></option>
                    <option value="1">Internal</option>
                    <option value="2">External</option>
                </select>
            <?php } elseif ($type == 1) {?>
                <select name="type" class="selectpicker">
                    <option value=""></option>
                    <option value="1" selected>Internal</option>
                    <option value="2">External</option>
                </select>
            <?php  
            }elseif ($type == 2) {?>
                <select name="type" class="selectpicker">
                    <option value=""></option>
                    <option value="1">Internal</option>
                    <option value="2" selected>External</option>
                </select>
              <?php } ?>
        </div>
    </div>
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Create</button>
        <a class="btn" href="identity.php">Back</a>
    </div>
</form>