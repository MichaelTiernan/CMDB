<?php
print "<h2>" . htmlentities ($title) . "</h2>";
if ( $errors ) {
	print '<ul class="list-group">';
	foreach ( $errors as $field => $error ) {
		print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
	}
	print '</ul>';
}
?>
Please set the amount of players.
<form class="form-horizontal" action="" method="post">
	<div class="control-group ">
		<label class="control-label">Amount of players <span style="color: red;">*</span></label>
		<div class="controls">
			<input name="players" type="text" placeholder="Please enter the amount of players"
				value="<?php echo $Players;?>">
		</div>
	</div>
	<input type="hidden" name="form-submitted" value="1" /><br>
	<div class="form-actions">
		<button type="submit" class="btn btn-success">Next</button>
	</div>
	<div class="form-group">
		<span class="text-muted"><em><span style="color: red;">*</span>
				Indicates required field</em></span>
	</div>
</form>