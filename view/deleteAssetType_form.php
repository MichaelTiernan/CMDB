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
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Category</th>
            <th>Vendor</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php print htmlentities($Category); ?></td>
            <td><?php print htmlentities($Vendor); ?></td>
            <td><?php print htmlentities($Type); ?></td>
        </tr>
    </tbody>
</table>
<p></p>
<form class="form-horizontal" action="" method="post">
    <div class="control-group ">
        <label class="control-label">Reason <span style="color:red;">*</span></label>
        <div class="controls">
            <input name="reason" type="text"  placeholder="Please insert reason" value="<?php echo $Reason;?>">
        </div>
    </div>
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Delete</button>
        <a class="btn" href="AssetType.php">Back</a>
    </div>
    <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div>
</form>