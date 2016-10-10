<?php
print "<h2>".htmlentities($title)."</h2>";
if ( $errors ) {
    print '<ul class="list-group">';
    foreach ( $errors as $field => $error ) {
        print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
    }
    print '</ul>';
}
echo "<table class=\"table table-striped table-bordered\">";
echo "<thead>";
echo "<tr>";
echo "<th>Level</th>";
echo "<th>Menu</th>";
echo "<th>Permission</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
foreach ($rows as $row):
    echo "<tr>";
        echo "<td>".htmlentities($row['Level'])."</td>";
        echo "<td>".htmlentities($row['Menu'])."</td>";
        echo "<td>".htmlentities($row['Permission'])."</td>";
        echo "</tr>";
endforeach;
echo "</tbody>";
echo "</table>";
?>
<p></p>
<form class="form-horizontal" action="" method="post">
    <!-- <div class="control-group ">
        <label class="control-label">Reason <span style="color:red;">*</span></label>
        <div class="controls">
            <input name="reason" type="text"  placeholder="Please insert reason" value="<?php echo $Reason;?>">
        </div>
    </div>  -->
    This will delete the Permission from the Database.
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Delete</button>
        <a class="btn" href="Permission.php">Back</a>
    </div>
    <!-- <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div> -->
</form>