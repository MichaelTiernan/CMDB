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
        <div class="control-group ">
            <label class="control-label">Name <span style="color:red;">*</span></label>
            <div class="controls">
                <input name="Name" type="text"  placeholder="Please enter a name" value="<?php echo $Name;?>">
            </div>
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Update</button>
            <a class="btn" href="Application.php">Back</a>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}
