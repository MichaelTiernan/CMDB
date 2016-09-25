<h2>Assign Identity</h2>
<?php 
if ($AssignAccess){
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
    echo "<th>UserID</th>";
    echo "<th>Application</th>";
    echo "<th>Type</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
    echo "<tr>";
    echo "<td>".htmlentities($row['UserID'])."</td>";
    echo "<td>".htmlentities($row['Application'])."</td>";
    echo "<td>".htmlentities($row['Type'])."</td>";
    echo "</tr>";
    endforeach;
    echo "</tbody>";
    echo "</table>";
    ?>
<p></p>
<form class="form-horizontal" action="" method="post">
    <div class="control-group ">
        <label class="control-label">Identity <span style="color:red;">*</span></label>
        <div class="controls">
            <select name="identity" class="selectpicker">
            <?php echo "<option value=\"\"></option>";
                if (empty($_POST["identity"])){
                    foreach ($identities as $identity){
                        echo "<option value=\"".$identity["Iden_ID"]."\">".$identity["Name"]." ".$identity["UserID"]."</option>";
                    }
                }  else {
                    foreach ($accounts as $account){
                        if ($_POST["account"] == $identity["Iden_ID"]){
                            echo "<option value=\"".$identity["Iden_ID"]."\" selected>".$identity["Name"]." ".$identity["UserID"]."</option>";
                        }else{
                            echo "<option value=\"".$identity["Iden_ID"]."\">".$identity["Name"]." ".$identity["UserID"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
    </div>
    <div class="control-group" id="sandbox-container">
        <label class="control-label">Period <span style="color:red;">*</span></label>
        <div class="input-daterange input-group" id="datepicker">
            <input class="input-sm form-control" name="start" type="text">
            <span class="input-group-addon"> until </span>
            <input class="input-sm form-control" name="end" type="text">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Assign</button>
        <a class="btn" href="Account.php">Back</a>
    </div>
    <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div>
</form>
<script type="text/javascript">
    $('#sandbox-container .input-daterange').datepicker({
        format: "dd/mm/yyyy",
        clearBtn: true
    });
</script> 
<?php }else{
    $this->showError("Application error", "You do not access to this page");
}
    
